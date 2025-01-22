<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateAdmin($connection);
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);

    $reportId = (int)$data['id'];

    $stmt = $connection->prepare("UPDATE reports SET status = 1 WHERE id = ?");
    $stmt->bind_param("i", $reportId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $connection->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Reports</title>
    <style>
        .details > p {
            grid-column: 1 / -1;
        }
        .search-bar button {
            width: 80px;
        }
    </style>
</head>
<body>

<?php require 'admin-navbar.php' ?>

<div class="search-bar">
    <p>Filter : </p>
    <button type="button" data-filter="all" class="filter-btn">All</button>
    <button type="button" data-filter="solved" class="filter-btn">Solved</button>
    <button type="button" data-filter="unsolved" class="filter-btn">Unsolved</button>
</div>

<div class="card-container" id="card-container"></div>

<div class="error-container" id="error-container"></div>

<script>
    const elementsToHide = document.getElementsByClassName("show");
    setTimeout(() => {
        Array.from(elementsToHide).forEach((el) => el.classList.remove("show"))
    }, 5500);
</script>
<script>
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cardContainer = document.getElementById('card-container');

    async function fetchPets(filter) {
        try {
            const response = await fetch('filter-reports.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `filter=${encodeURIComponent(filter)}`,
            });

            cardContainer.innerHTML = await response.text();
        } catch (error) {
            console.error('Error fetching pets:', error);
            cardContainer.innerHTML = '<div class="errors show"><p>An error occurred while fetching data.</p></div>';
        }
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            fetchPets(filter);
        });
    });

    fetchPets('all');
</script>
<script>
    document.getElementById('card-container').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('details-button')) {
            const reportId = e.target.getAttribute('data-report-id');
            window.location.href = `details.php?report_id=${reportId}`;
        }
    });
</script>
<script>
    document.getElementById('card-container').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('solved-button')) {
            const reportId = e.target.getAttribute('data-report-id');
            const button = e.target;
            const errorContainer = document.getElementById('error-container');
            if (confirm('Are you sure you want to mark this report as solved?')) {
                fetch('reports.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: reportId }),
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            errorContainer.innerHTML = '<div class="errors show" style="background-color: rgba(131, 173, 68)">' +
                                '<p style="color: antiquewhite;">Report marked as solved.</p>' +
                                '</div>';
                            button.style.display = 'none';
                        } else {
                            errorContainer.innerHTML = '<div class="errors show">' +
                                '<p>Error updating report status.</p>' +
                                '</div>';
                        }
                    })
                    .catch(error => {
                        errorContainer.innerHTML = '<div class="errors show">' +
                            '<p>Something went wrong.</p>' +
                            '</div>';
                    });
            }
        }
    });
</script>
</body>
</html>