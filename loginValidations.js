const loginForm = document.getElementById("login-form");
const email = document.getElementById("email");
const emailError = document.getElementById("email-error");

const validateLoginForm = (event) => {

    manageInputs([email]);

    if(!isValidEmail(email)) {
        showError(email, emailError);
        event.preventDefault();
    }
}

loginForm.addEventListener("submit", validateLoginForm);