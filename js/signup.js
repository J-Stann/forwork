document.addEventListener('DOMContentLoaded', function() {
    // Get the current form based on step
    const getCurrentForm = () => {
        if (document.getElementById('step1-form')) return document.getElementById('step1-form');
        if (document.getElementById('step2-form')) return document.getElementById('step2-form');
        if (document.getElementById('step3-form')) return document.getElementById('step3-form');
        return null;
    };

    const form = getCurrentForm();
    if (!form) return;

    // For steps 1 and 2, let the form submit normally
    if (form.id === 'step1-form' || form.id === 'step2-form') {
        return;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('step3-form');
        if (!form) return;
    
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!validateForm()) return;
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            
            // AJAX request
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            })
            .then(response => {
                if (response.redirected) {
                    // If server redirected, follow that redirect
                    window.location.href = response.url;
                } else {
                    return response.text();
                }
            })
            .then(data => {
                if (data) {
                    // Handle any errors returned
                    const errorContainer = document.createElement('div');
                    errorContainer.innerHTML = data;
                    const newErrors = errorContainer.querySelector('.signup__error');
                    
                    if (newErrors) {
                        const formErrors = document.querySelector('.signup__error');
                        if (formErrors) {
                            formErrors.innerHTML = newErrors.innerHTML;
                            formErrors.classList.remove('hidden');
                        }
                    }
                }
            })
            .catch(error => {
                const formErrors = document.querySelector('.signup__error');
                if (formErrors) {
                    formErrors.textContent = 'Network error. Please try again.';
                    formErrors.classList.remove('hidden');
                }
                console.error('Error:', error);
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Complete Registration';
                }
            });
        });
    
        function validateForm() {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (field.type === 'hidden') return;
                
                if (!field.value.trim()) {
                    showFieldError(field, 'This field is required');
                    isValid = false;
                }
            });
            
            return isValid;
        }
    
        function showFieldError(field, message) {
            field.classList.add('error');
            let errorSpan = field.nextElementSibling;
            
            if (!errorSpan || !errorSpan.classList.contains('signup__error-text')) {
                errorSpan = document.createElement('span');
                errorSpan.className = 'signup__error-text';
                errorSpan.textContent = message;
                field.parentNode.insertBefore(errorSpan, field.nextSibling);
            } else {
                errorSpan.textContent = message;
            }
            errorSpan.style.display = 'block';
        }
    });
});


setTimeout(() => {
    document.getElementById('loading-screen').style.display = 'none';
    document.getElementById('success-message').style.display = 'flex';

    // Redirect based on user type
    const userType = "<?= $user['user_type'] ?>";
    const redirectUrl = (userType === 'client') ? 'post_task.php' : 'browse_jobs.php';

    setTimeout(() => {
        window.location.href = redirectUrl;
    }, 3000);
}, 3000);