document.addEventListener("DOMContentLoaded", function () { 
    const dropdownButtons = document.querySelectorAll(".dropdown-btn");
    dropdownButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.stopPropagation();
            const menu = this.nextElementSibling;
            menu.classList.toggle("active");
            document.querySelectorAll(".dropdown-menu").forEach(dropdown => {
                if (dropdown !== menu) {
                    dropdown.classList.remove("active");
                }
            });
        });
    });
    document.addEventListener("click", function () {
        document.querySelectorAll(".dropdown-menu").forEach(menu => {
            menu.classList.remove("active");
        });
    });
    const hamburgerMenu = document.querySelector(".hamburger-menu");
    const navbar = document.getElementById("navbar-sticky");
    hamburgerMenu.addEventListener("click", function () {
        document.body.classList.toggle("navbar-active");
        navbar.classList.toggle("active");
    });
});
const trustedSwiper = new Swiper('.trusted-people .swiper-container', {
    loop: true,
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },
    spaceBetween: 30,
    navigation: {
        nextEl: '.trusted-people .swiper-button-next',
        prevEl: '.trusted-people .swiper-button-prev',
    },
    breakpoints: {
        1024: {
            slidesPerView: 3,
        },
        768: {
            slidesPerView: 2,
        },
        320: {
            slidesPerView: 1,
        }
    }
});




document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.categories-pagination-container');
    const debugEl = container.querySelector('#pagination-debug');
    const pagesContainer = container.querySelector('.categories-grid-pages');
    const pages = container.querySelectorAll('.categories-page');
    const prevBtn = container.querySelector('.prev-btn');
    const nextBtn = container.querySelector('.next-btn');
    const currentPageEl = container.querySelector('.current-page');
    const totalPagesEl = container.querySelector('.total-pages');
    debugEl.textContent = `Found ${pages.length} pages`;
    let currentPage = 0;
    const totalPages = pages.length;
    function updatePagination() {
        debugEl.textContent += `\nUpdating to page ${currentPage}`;
        console.log(`Updating to page ${currentPage} of ${totalPages}`);
        pages.forEach(page => {
            page.style.display = 'none';
            page.classList.remove('active');
        });
        if (pages[currentPage]) {
            pages[currentPage].style.display = 'block';
            pages[currentPage].classList.add('active');
            console.log(`Page ${currentPage} display:`, pages[currentPage].style.display);
        } else {
            console.error(`Page ${currentPage} doesn't exist`);
        }
        if (prevBtn) prevBtn.disabled = currentPage === 0;
        if (nextBtn) nextBtn.disabled = currentPage >= totalPages - 1;
        if (currentPageEl) currentPageEl.textContent = currentPage + 1;
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (currentPage > 0) {
                currentPage--;
                updatePagination();
            }
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages - 1) {
                currentPage++;
                updatePagination();
            }
        });
    }
    updatePagination();
});

const swiper = new Swiper(".swiper-container", {
    loop: true,
    autoplay: {
        delay: 2000,
        disableOnInteraction: false,
    },
    spaceBetween: 30,
    centeredSlides: true,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    breakpoints: {
        1025: {
            slidesPerView: 3,
        },
        768: {
            slidesPerView: 2,
        },
        0: {
            slidesPerView: 1,
        },
    },
});
const swiper1 = new Swiper('.company-trusted-logos-swiper', {
    slidesPerView: 3,
    spaceBetween: 20,
    loop: true,
    autoplay: {
        delay: 2000,
        speed: 3000,
        disableOnInteraction: false,
    },
    slidesPerGroup: 1,
    allowTouchMove: false,
});

