const signupForm = document.getElementById("signup-form");
const name = document.getElementById("name");
const nameError = document.getElementById("name-error");
const surname = document.getElementById("surname");
const surnameError = document.getElementById("surname-error");
const email = document.getElementById("email");
const emailError = document.getElementById("email-error");
const password = document.getElementById("password");
const passwordError = document.getElementById("password-error");
const passwordConfirm = document.getElementById("password-confirm");
const passwordConfirmError = document.getElementById("password-confirm-error");

const validateSignupForm = (event) => {
    event.preventDefault();

    manageInputs([name, surname, email, password, passwordConfirm]);

    let formIsValid = true;

    if(!isValidNameOrSurname(name)) {
        showError(name, nameError);
        formIsValid = false;
    }

    if(!isValidNameOrSurname(surname)) {
        showError(surname, surnameError);
        formIsValid = false;
    }

    if(!isValidEmail(email)) {
        showError(email, emailError);
        formIsValid = false;
    }

    if(!isValidPassword(password)) {
        showError(password, passwordError);
        formIsValid = false;
    }

    if(!checkEqualityOfPasswords(password, passwordConfirm)) {
        showError(passwordConfirm, passwordConfirmError);
        formIsValid = false;
    }

    if (formIsValid) {
        this.submit();
    }
}