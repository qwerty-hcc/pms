function validateEmail() {
    const email = document.getElementById("email").value;
    const errorMsg = document.getElementById('dateError');

    if (!isValidEmail(email())) {
        errorMsg.textContent = 'Please enter a valid email address.';
        return false; // Prevent form submission
    }  else {
        errorMsg.textContent = ''; // Clear error message
    }
    return true; // Allow form submission
}

function validatePhone() {
    const phone = document.getElementById("phone").value;
    const errorMsg = document.getElementById('dateError');

    if (!/^9\d{9}$/.test(phone)) {
        errorMsg.textContent = 'Please enter a valid phone number.';
        return false; // Prevent form submission
    } else {
        errorMsg.textContent = ''; // Clear error message
    }
    return true; // Allow form submission
}

function confirmRegister() {
    return confirm("Are you sure you want to register this employee?");
}
