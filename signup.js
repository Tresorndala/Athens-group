// Get all form elements
const form = document.querySelector('form');
const fullNameInput = document.querySelector('input[name="full_name"]');
const emailInput = document.querySelector('input[name="email"]');
const passwordInput = document.querySelector('input[name="password"]');
const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');

// Create password requirements div
const passwordRequirements = document.createElement('div');
passwordRequirements.className = 'password-requirements';
passwordRequirements.innerHTML = `
    <div class="requirement length">At least 8 characters long</div>
    <div class="requirement uppercase">Contains uppercase letter</div>
    <div class="requirement lowercase">Contains lowercase letter</div>
    <div class="requirement number">Contains number</div>
    <div class="requirement special">Contains special character</div>
`;

// Insert password requirements after password input
passwordInput.parentElement.appendChild(passwordRequirements);

// Validation patterns
const patterns = {
    fullName: /^[a-zA-Z\s]{2,50}$/,
    email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    password: {
        length: /.{8,}/,
        uppercase: /[A-Z]/,
        lowercase: /[a-z]/,
        number: /[0-9]/,
        special: /[!@#$%^&*(),.?":{}|<>]/
    }
};

// Show error message
function showError(input, message) {
    const inputSpace = input.parentElement;
    inputSpace.classList.add('error');
    
    let errorMessage = inputSpace.querySelector('.error-message');
    if (!errorMessage) {
        errorMessage = document.createElement('div');
        errorMessage.className = 'error-message';
        inputSpace.appendChild(errorMessage);
    }
    
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';
}

// Clear error message
function clearError(input) {
    const inputSpace = input.parentElement;
    inputSpace.classList.remove('error');
    const errorMessage = inputSpace.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.style.display = 'none';
    }
}

// Validate full name
function validateFullName() {
    const fullName = fullNameInput.value.trim();
    if (!patterns.fullName.test(fullName)) {
        showError(fullNameInput, 'Please enter a valid full name (letters and spaces only)');
        return false;
    }
    clearError(fullNameInput);
    return true;
}

// Validate email
function validateEmail() {
    const email = emailInput.value.trim();
    if (!patterns.email.test(email)) {
        showError(emailInput, 'Please enter a valid email address');
        return false;
    }
    clearError(emailInput);
    return true;
}

// Validate password
function validatePassword() {
    const password = passwordInput.value;
    let isValid = true;
    
    // Check each password requirement
    Object.keys(patterns.password).forEach(requirement => {
        const requirementElement = passwordRequirements.querySelector(`.${requirement}`);
        if (patterns.password[requirement].test(password)) {
            requirementElement.classList.add('valid');
        } else {
            requirementElement.classList.remove('valid');
            isValid = false;
        }
    });
    
    if (!isValid) {
        showError(passwordInput, 'Password does not meet all requirements');
        return false;
    }
    
    clearError(passwordInput);
    return true;
}

// Validate confirm password
function validateConfirmPassword() {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    
    if (password !== confirmPassword) {
        showError(confirmPasswordInput, 'Passwords do not match');
        return false;
    }
    clearError(confirmPasswordInput);
    return true;
}

// Show/hide password requirements
passwordInput.addEventListener('focus', () => {
    passwordRequirements.style.display = 'block';
});

passwordInput.addEventListener('blur', () => {
    passwordRequirements.style.display = 'none';
});

// Real-time validation
fullNameInput.addEventListener('input', validateFullName);
emailInput.addEventListener('input', validateEmail);
passwordInput.addEventListener('input', validatePassword);
confirmPasswordInput.addEventListener('input', validateConfirmPassword);

// Form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();
    
    // Validate all fields
    const isFullNameValid = validateFullName();
    const isEmailValid = validateEmail();
    const isPasswordValid = validatePassword();
    const isConfirmPasswordValid = validateConfirmPassword();
    
    // If all validations pass, submit the form
    if (isFullNameValid && isEmailValid && isPasswordValid && isConfirmPasswordValid) {
        // Here you would typically send the data to your server
        console.log('Form submitted successfully');
        form.submit();
    }
});