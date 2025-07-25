

// This Function do a animate the count-up effect
document.addEventListener("DOMContentLoaded", function () {
    const counters = document.querySelectorAll('.count');

    counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        let count = 0;
        const speed = 10000;

        const updateCount = () => {
            const increment = target / speed * 100;
            if (count < target) {
                count += increment;
                counter.innerText = Math.floor(count);
                setTimeout(updateCount, 50);
            } else {
                counter.innerText = target;
            }
        };

        updateCount();
    });
});



// Function to handle the intersection observer
const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible'); // Add 'visible' class when section is in view
            observer.unobserve(entry.target); // Stop observing after animation starts
        }
    });
}, { threshold: 0.5 }); // Trigger when 50% of the element is visible

// Observe the element with the class "our-story-text-content"
const ourStoryText = document.querySelector('.our-story-text-content');
observer.observe(ourStoryText);
