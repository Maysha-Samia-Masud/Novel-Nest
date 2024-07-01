function validateForm(event, formType) {
    event.preventDefault();

    let name, email, password;
    let errors = [];

    // Improved regex patterns
    const nameREGEX = /^[A-Za-z0-9_]+$/;
    const emailREGEX = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
    const passREGEX = /^\S+$/;

    // Fetch the values based on form type
    if (formType === 'signup') {
        name = document.getElementById('signupName').value.trim();
        email = document.getElementById('signupEmail').value.trim();
        password = document.getElementById('signupPassword').value.trim();
    } else if (formType === 'login') {
        name = document.getElementById('loginName').value.trim();
        password = document.getElementById('loginPassword').value.trim();
    }

    // Validation for both form types
    if (!name || (formType === 'signup' && !email) || !password) {
        errors.push('All fields are required!');
    }
    if (name && !nameREGEX.test(name)) {
        errors.push('Name can only contain letters, numbers, and underscores.');
    }
    if (formType === 'signup' && email && !emailREGEX.test(email)) {
        errors.push('Email must be in a valid format (e.g., name@domain.com).');
    }
    if (password && !passREGEX.test(password)) {
        errors.push('Password cannot contain spaces.');
    }

    // Display errors if there are any
    if (errors.length > 0) {
        window.alert(errors.join('\n'));
        return false;
    }

    // Submit the form if validation passed
    if (formType === 'signup') {
        document.getElementById('signupForm').submit();
    } else if (formType === 'login') {
        document.getElementById('loginForm').submit();
    }
    return true;
}
