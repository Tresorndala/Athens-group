document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const passwordRequirements = document.getElementById('passwordRequirements');

    // Show password requirements on focus
    passwordInput.addEventListener('focus', function() {
        passwordRequirements.style.display = 'block';
    });

    // Hide password requirements when clicking outside
    document.addEventListener('click', function(e) {
        if (!passwordInput.contains(e.target) && 
            !passwordRequirements.contains(e.target)) {
            passwordRequirements.style.display = 'none';
        }
    });

    // Keep requirements visible when hovering over them
    passwordRequirements.addEventListener('mouseenter', function() {
        this.style.display = 'block';
    });

    // Email validation function
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Password validation function
    function validatePassword(password) {
        const minLength = password.length >= 8;
        const hasUpper = /[A-Z]/.test(password);
        const hasLower = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

        // Update requirement indicators
        document.getElementById('lengthReq').className = 
            'requirement ' + (minLength ? 'valid' : 'invalid');
        document.getElementById('upperReq').className = 
            'requirement ' + (hasUpper ? 'valid' : 'invalid');
        document.getElementById('lowerReq').className = 
            'requirement ' + (hasLower ? 'valid' : 'invalid');
        document.getElementById('numberReq').className = 
            'requirement ' + (hasNumber ? 'valid' : 'invalid');
        document.getElementById('specialReq').className = 
            'requirement ' + (hasSpecial ? 'valid' : 'invalid');

        return minLength && hasUpper && hasLower && hasNumber && hasSpecial;
    }

    // Real-time email validation
    emailInput.addEventListener('input', function() {
        const isValid = validateEmail(this.value);
        if (!isValid && this.value) {
            emailError.textContent = 'Please enter a valid email address';
            emailError.style.display = 'block';
            this.parentElement.classList.add('error');
        } else {
            emailError.style.display = 'none';
            this.parentElement.classList.remove('error');
        }
    });

    // Real-time password validation
    passwordInput.addEventListener('input', function() {
        validatePassword(this.value);
    });

    // Form submission handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let isValid = true;

        // Validate email
        if (!validateEmail(emailInput.value)) {
            emailError.textContent = 'Please enter a valid email address';
            emailError.style.display = 'block';
            emailInput.parentElement.classList.add('error');
            isValid = false;
        }

        // Validate password
        if (!validatePassword(passwordInput.value)) {
            passwordError.textContent = 'Password does not meet requirements';
            passwordError.style.display = 'block';
            passwordInput.parentElement.classList.add('error');
            isValid = false;
        }

        // If valid, submit the form
        if (isValid) {
            console.log('Form is valid, ready to submit');
            // Add your form submission logic here
        }
    });
});