const age = document.getElementById('age');
const ageError = document.getElementById('age-error');
const addPetForm = document.getElementById('add-pet-form');
const image = document.getElementById('file-input');
const imageError = document.getElementById('image-error');
const selectSpecies = document.getElementById('select-species');
const selectBreed = document.getElementById('select-breed');
const breedError = document.getElementById('breed-error');

const validateAddPetForm = (event) => {

    manageInputs([age]);

    if(!checkAge(age)) {
        showError(age, ageError);
        event.preventDefault();
    }

    if(!image.value) {
        imageError.classList.add("show");
        setTimeout(() => imageError.classList.remove("show"), 5500);
        event.preventDefault();
    }

    if(selectSpecies.value !== "other" && !selectBreed.value) {
        breedError.classList.add("show");
        setTimeout(() => breedError.classList.remove("show"), 5500);
        event.preventDefault();
    }
}

addPetForm.addEventListener("submit", validateAddPetForm);