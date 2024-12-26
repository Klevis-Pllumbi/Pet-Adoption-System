const loginForm = document.getElementById("login-form");
const email = document.getElementById("email");
const emailError = document.getElementById("email-error");
const password = document.getElementById("password");
const passwordError = document.getElementById("password-error");

const validateLoginForm = (event) => {

    event.preventDefault();

    manageInputs([email, password]);

    let formIsValid = true;

    if(!isValidEmail(email)) {
        showError(email, emailError);
        formIsValid = false;
    }

    if(!isValidPassword(password)) {
        showError(password, passwordError);
        formIsValid = false;
    }

    if (formIsValid) {
        this.submit();
    }
}

loginForm.addEventListener("submit", validateLoginForm);