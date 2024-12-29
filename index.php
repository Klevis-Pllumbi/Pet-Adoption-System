<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poiret+One&amp;family=Righteous&amp;display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="icon.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="index-style.css">
    <title>Pet Adoption System</title>
</head>
<body>
    <h1>Welcome to <span>Pet Adoption System</span></h1>
    <p>Our mission is to connect loving families with pets in need.</p>
    <p class="typewriter"></p>
    <div class="login-or-signup">
        <button class="btn-login" onclick="redirectTo('login')">Log In</button>
        <button class="btn-signup" onclick="redirectTo('signup')">Sign Up</button>
    </div>
    <footer>
        &copy; 2024 Pet Adoption System. All Rights Reserved.
    </footer>

    <script>
        const text = "Whether you’re looking to adopt a furry friend or contribute to their care, we’re here to make it easy and meaningful.";
        const typewriterElement = document.querySelector(".typewriter");

        let index = 0;

        function typeWriter() {
            if (index < text.length) {
                typewriterElement.textContent += text.charAt(index);
                index++;
                setTimeout(typeWriter, 40); // Adjust typing speed in milliseconds
            }
        }

        function redirectTo(page) {
            if (page === 'login') {
                window.location.href = 'login.php';
            } else if (page === 'signup') {
                window.location.href = 'signup.php';
            }
        }

        typeWriter();
    </script>
</body>
</html>