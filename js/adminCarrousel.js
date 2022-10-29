/*                                  * * * * * * * * * * * * * * * * * * * *
                                    *                                     *
                                    *              Fichier JS             *
                                    *        Fonction du carrousel        *
                                    *                                     *
                                    * * * * * * * * * * * * * * * * * * * *    
*/

/***** PAGE MENU ADMIN *****/

/* carrousel */
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("slide");
    if (n > slides.length) {
        slideIndex = 1
    }
    if (n < 1) {
        slideIndex = slides.length
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[slideIndex - 1].style.display = "flex";
}

/* révélation des boutons modif/suppr lors du passage de la souris */
document.querySelectorAll(".boutonSelectAdmin").forEach(element => {
    element.addEventListener("mouseenter", function (event) {
        let childrens = event.target.children
        for (let i = 0; i < childrens.length; i++) {
            if (childrens[i].classList.contains("containerBoutonAdmin")) {
                childrens[i].style.display = 'block';
            }
        }
    });

    element.addEventListener("mouseleave", function (event) {
        let childrens = event.target.children
        for (let i = 0; i < childrens.length; i++) {
            if (childrens[i].classList.contains("containerBoutonAdmin")) {
                childrens[i].style.display = 'none';
            }
        }
    });
});