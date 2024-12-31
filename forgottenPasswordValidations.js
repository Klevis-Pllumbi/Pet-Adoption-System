const forgottenPasswordForm = document.getElementById("forgotten-password-form");
const email = document.getElementById("email");
const emailError = document.getElementById("email-error");
const password = document.getElementById("password");
const passwordError = document.getElementById("password-error");
const passwordConfirm = document.getElementById("password-confirm");
const passwordConfirmError = document.getElementById("password-confirm-error");

const validateForgottenPasswordForm = (event) => {

    manageInputs([email, password, passwordConfirm]);

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

forgottenPasswordForm.addEventListener("submit", validateForgottenPasswordForm);