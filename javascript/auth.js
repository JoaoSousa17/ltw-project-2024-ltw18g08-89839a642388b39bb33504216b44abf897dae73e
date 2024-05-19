document.addEventListener('DOMContentLoaded', function() {
    const showPasswordCheckbox = document.getElementById('show-password');
    const passwordInput = document.getElementById('password');

    showPasswordCheckbox.addEventListener('change', function() {
        if (showPasswordCheckbox.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordMatchMsg = document.getElementById('password-match-msg');

    confirmPasswordInput.addEventListener('input', function() {
        if (passwordInput.value === confirmPasswordInput.value) {
            passwordMatchMsg.textContent = 'Passwords match';
            passwordMatchMsg.style.color = 'green';
        } else {
            passwordMatchMsg.textContent = 'Passwords do not match';
            passwordMatchMsg.style.color = 'red';
        }
    });
});
