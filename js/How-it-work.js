// Initialize Swiper
const testimonialSwiper = new Swiper('.testimonials-swiper', {
    loop: true,
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    breakpoints: {
        640: {
            slidesPerView: 1,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 30,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 40,
        },
    }
});

// Tab functionality
const tabButtons = document.querySelectorAll('.tab-btn');
tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Remove active class from all buttons and content
        tabButtons.forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Add active class to clicked button
        button.classList.add('active');
        
        // Show corresponding content
        const tabId = button.getAttribute('data-tab') + '-tab';
        document.getElementById(tabId).classList.add('active');
    });
});

// Video modal functionality
const videoWrapper = document.querySelector('.video-wrapper');
const videoModal = document.querySelector('.video-modal');
const closeModal = document.querySelector('.close-modal');

if(videoWrapper) {
    videoWrapper.addEventListener('click', () => {
        videoModal.style.display = 'flex';
    });
}

if(closeModal) {
    closeModal.addEventListener('click', () => {
        videoModal.style.display = 'none';
    });
}

// FAQ functionality
const faqQuestions = document.querySelectorAll('.faq-question');
faqQuestions.forEach(question => {
    question.addEventListener('click', () => {
        const answer = question.nextElementSibling;
        const isActive = answer.classList.contains('active');
        
        // Close all answers first
        document.querySelectorAll('.faq-answer').forEach(ans => {
            ans.classList.remove('active');
        });
        document.querySelectorAll('.faq-question').forEach(q => {
            q.classList.remove('active');
        });
        
        // Open clicked answer if it wasn't already active
        if(!isActive) {
            answer.classList.add('active');
            question.classList.add('active');
        }
    });
});