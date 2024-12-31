const loginForm = document.getElementById("login-form");
const email = document.getElementById("email");
const emailError = document.getElementById("email-error");
const password = document.getElementById("password");
const passwordError = document.getElementById("password-error");

const validateLoginForm = (event) => {

    manageInputs([email, password]);

    if(!isValidEmail(email)) {
        showError(email, emailError);
        event.preventDefault();
    }
}

loginForm.addEventListener("submit", validateLoginForm);