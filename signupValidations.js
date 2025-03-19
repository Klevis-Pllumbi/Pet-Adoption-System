const signupForm = document.getElementById("signup-form");
const name = document.getElementById("name");const nameError = document.getElementById("name-error");
const surname = document.getElementById("surname");
const surnameError = document.getElementById("surname-error");
const email = document.getElementById("email");
const emailError = document.getElementById("email-error");
const password = document.getElementById("password");
const passwordError = document.getElementById("password-error");
const passwordConfirm = document.getElementById("password-confirm");
const passwordConfirmError = document.getElementById("password-confirm-error");

const validateSignupForm = (event) => {

    manageInputs([name, surname, email, password, passwordConfirm]);

    if(!isValidNameOrSurname(name)) {
        showError(name, nameError);
        event.preventDefault();
    }

    if(!isValidNameOrSurname(surname)) {
        showError(surname, surnameError);
        event.preventDefault();
    }

    if(!isValidEmail(email)) {
        showError(email, emailError);
        event.preventDefault();
    }

    if(!isValidPassword(password)) {
        showError(password, passwordError);
        event.preventDefault();
    }

    if(!checkEqualityOfPasswords(password, passwordConfirm)) {
        showError(passwordConfirm, passwordConfirmError);
        event.preventDefault();
    }
}

signupForm.addEventListener("submit", validateSignupForm);
