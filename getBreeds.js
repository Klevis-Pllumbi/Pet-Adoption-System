document.addEventListener("DOMContentLoaded", function () {
    const speciesSelect = document.getElementById("select-species");
    const breedSelect = document.getElementById("select-breed");
    const otherSpecies = document.getElementById("other-species-group");

    speciesSelect.addEventListener("change", function () {
        let species;
        if(speciesSelect.value === 'cat') {
            species = 'cat_breeds';
        } else if(speciesSelect.value === 'dog') {
            species = 'dog_breeds';
        } else {
            species = 'other';
        }

        if (species !== 'other') {
            fetch(`getBreeds.php?species=${species}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((breeds) => {
                    otherSpecies.style.display = 'none';
                    otherSpecies.firstElementChild.required = false;
                    breedSelect.style.display = "block";
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
            breedSelect.style.display = "none";
            otherSpecies.style.display = 'block';
            otherSpecies.firstElementChild.required = true;
        }
    });
});

document.getElementById("surrender").addEventListener("change", function () {
    const reason = document.getElementById("reason");
    if (this.checked) {
        reason.style.display = "block";
        reason.required = true;
    } else {
        reason.style.display = "none";
        reason.value = "";
        reason.required = false;
    }
});