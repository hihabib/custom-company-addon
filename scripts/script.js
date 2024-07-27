class CustomCompanyAddon {
    constructor() {
        this.ratingIconBeautify();
    }

    ratingIconBeautify(){
        const iconsContainer = document.querySelector(".rating-stars");
        const icons = iconsContainer.querySelectorAll('.fa.stars-style-solid');
        icons.forEach(icon => {
            const isRated = icon.classList.contains("rated")
            if(isRated){
                icon.classList.add('green-rating');
            } else {
                icon.classList.add('gray-rating');
            }
        })
    }
}

new CustomCompanyAddon();