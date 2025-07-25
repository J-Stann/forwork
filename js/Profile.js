document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons and contents
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Show corresponding content
            const tabId = this.getAttribute('data-tab') + '-tab';
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Portfolio image hover effect
    const portfolioItems = document.querySelectorAll('.portfolio-item');
    portfolioItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.querySelector('.portfolio-overlay').style.transform = 'translateY(0)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.querySelector('.portfolio-overlay').style.transform = 'translateY(100%)';
        });
    });
    
    // FAQ accordion functionality
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('i');
            
            // Toggle answer visibility
            if (answer.style.maxHeight) {
                answer.style.maxHeight = null;
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            } else {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        });
    });
    
    // Load more reviews functionality
    const loadMoreBtn = document.querySelector('.load-more-reviews');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            // In a real app, this would fetch more reviews from the server
            alert('Loading more reviews...');
        });
    }
    
    // Job status filtering
    const jobFilter = document.querySelector('.jobs-filter select');
    if (jobFilter) {
        jobFilter.addEventListener('change', function() {
            const status = this.value;
            const jobItems = document.querySelectorAll('.job-item');
            
            jobItems.forEach(item => {
                const itemStatus = item.querySelector('.job-status').classList[1];
                
                if (status === 'All Jobs' || itemStatus === status.toLowerCase()) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Job search functionality
    const jobSearch = document.querySelector('.search-jobs input');
    const jobSearchBtn = document.querySelector('.search-jobs button');
    
    if (jobSearch && jobSearchBtn) {
        jobSearchBtn.addEventListener('click', function() {
            const searchTerm = jobSearch.value.toLowerCase();
            const jobItems = document.querySelectorAll('.job-item');
            
            jobItems.forEach(item => {
                const title = item.querySelector('h3').textContent.toLowerCase();
                const description = item.querySelector('.job-description').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});