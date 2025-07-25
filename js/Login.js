document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.form');
    const errorMessage = document.getElementById('error-message');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            if (!email || !password) {
                e.preventDefault();
                errorMessage.textContent = 'Please fill in all fields';
                errorMessage.classList.remove('hidden');
            }
        });
    }
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (errorMessage && !errorMessage.classList.contains('hidden')) {
                errorMessage.classList.add('hidden');
            }
        });
    });
});