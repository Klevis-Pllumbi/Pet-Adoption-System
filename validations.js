
const isValidNameOrSurname = (element) => {
    return /^[a-zA-Z\s]+$/.test(element.value.trim());
}

const isValidEmail = (element) => {
    return /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(element.value.trim());
}

const isValidPassword = (element) => {
    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(element.value.trim());
}

const checkAge = (element) => {
    return /^(\d+)\s*(year|years|month|months)(\s*old)?\s*$/i.test(element.value.trim());
}

const checkEqualityOfPasswords = (pass, confPass) => {
    return pass.value.trim() === confPass.value.trim();
}

const showError = (element, elementError) => {
    element.classList.add("error");
    element.parentElement.lastElementChild.classList.add("error");
    elementError.classList.add("show");
    setTimeout(() => elementError.classList.remove("show"), 5500);
}

const manageInputs = (inputs) => {
    inputs.forEach(input => {
        input.addEventListener("focus", () => {
            input.classList.remove("error");
            input.parentElement.lastElementChild.classList.remove("error");
        });
    });
}