document.addEventListener("DOMContentLoaded", function () {
    const speciesSelect = document.getElementById("select-species");
    const breedSelect = document.getElementById("select-breed");

    speciesSelect.addEventListener("change", function () {
        const species = speciesSelect.value === 'cat' ? 'cat_breeds' : 'dog_breeds';

        if (species !== 'other') {
            fetch(`getBreeds.php?species=${species}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((breeds) => {
                    breedSelect.innerHTML = '<option value="">-- Select Breed --</option>';
                    breeds.forEach((breed) => {
                        const option = document.createElement("option");
                        option.value = breed;
                        option.textContent = breed;
                        breedSelect.appendChild(option);
                    });
                })
                .catch((error) => {
                    console.error("There was a problem with the fetch operation:", error);
                    alert("Failed to fetch breeds. Please try again.");
                });
        } else {
            breedSelect.innerHTML = '<option value="">-- Select Breed --</option>';
        }
    });
});