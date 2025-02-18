document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById("header");
    const urlParams = new URLSearchParams(window.location.search);
    const shouldApplyClasses = urlParams.has('Projet_Pro') || urlParams.has('projet_id');

    const updateHeaderClasses = () => {
        const isScrolled = window.scrollY > 30;
        header.classList.toggle("small", shouldApplyClasses || isScrolled);
        header.classList.toggle("small-image", shouldApplyClasses || isScrolled);
    };

    updateHeaderClasses();
    window.addEventListener('scroll', updateHeaderClasses);
});