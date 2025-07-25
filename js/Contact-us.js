document.addEventListener('DOMContentLoaded', function() {
    // ========== FAQ FUNCTIONALITY ==========
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            const isOpen = question.classList.contains('active');
            faqQuestions.forEach(q => {
                q.classList.remove('active');
                q.nextElementSibling.classList.remove('show');
            });
            if (!isOpen) {
                question.classList.add('active');
                answer.classList.add('show');
            }
        });
    });


    // ========== MOBILE MENU TOGGLE ==========
    const hamburgerMenu = document.querySelector('.hamburger-menu');
    const navbarSticky = document.getElementById('navbar-sticky');
    if (hamburgerMenu && navbarSticky) {
        hamburgerMenu.addEventListener('click', function() {
            navbarSticky.classList.toggle('hidden');
        });
    }


    // ========== DROPDOWN FUNCTIONALITY ==========
    const dropdownBtns = document.querySelectorAll('.dropdown-btn');
    dropdownBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const dropdownMenu = this.nextElementSibling;
            dropdownMenu.classList.toggle('show');
        });
    });
    window.addEventListener('click', function(e) {
        if (!e.target.matches('.dropdown-btn')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });

    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const submitText = this.querySelector('#submitText');
            const formData = new FormData(this);
            if (submitText) {
                submitText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            }
            submitBtn.disabled = true;
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification('Your message has been sent successfully!', 'success');
                    contactForm.reset();
                }
            })
            .catch(error => {
                if (error.errors) {
                    document.querySelectorAll('.error').forEach(el => el.remove());
                    Object.entries(error.errors).forEach(([field, message]) => {
                        const input = contactForm.querySelector(`[name="${field}"]`);
                        if (input) {
                            const errorSpan = document.createElement('span');
                            errorSpan.className = 'error';
                            errorSpan.textContent = message;
                            input.parentNode.insertBefore(errorSpan, input.nextSibling);
                        }
                    });
                    showNotification('Please fix the errors in the form.', 'error');
                } else {
                    showNotification('There was an error sending your message. Please try again.', 'error');
                    console.error('Error:', error);
                }
            })
            .finally(() => {
                if (submitText) {
                    submitText.textContent = 'Send Message';
                }
                submitBtn.disabled = false;
            });
        });
    }

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const messageEl = document.getElementById('notification-message');
        const icon = notification.querySelector('i');
        messageEl.textContent = message;
        notification.className = 'notification ' + type;
        icon.className = type === 'error' 
            ? 'fas fa-exclamation-circle' 
            : 'fas fa-check-circle';
        notification.classList.add('show');
        setTimeout(() => {
            notification.classList.remove('show');
        }, 5000);
        
        notification.addEventListener('click', () => {
            notification.classList.remove('show');
        }, { once: true });
    }
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        showNotification('Your message has been sent successfully!', 'success');
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }
});