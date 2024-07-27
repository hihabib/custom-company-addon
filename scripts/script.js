class CustomCompanyAddon {
    constructor() {
        this.ratingIconBeautify();
    }

    ratingIconBeautify(){
        const iconsContainer = document.querySelector(".rating-stars");
        const icons = iconsContainer.querySelectorAll('.fa.stars-style-solid');
        // calculate total average rating
        const ratingLength = Array.from(icons).reduce((acc, next) => next.classList.contains('rated') ? ++acc : acc, 0);
        // add classes for styling.
        icons.forEach(icon => {
            const isRated = icon.classList.contains("rated")
            if(isRated && ratingLength <= 2){
                icon.classList.add('red-rating');
            } else if(isRated && ratingLength <= 3){
                icon.classList.add('orange-rating');
            } else if(isRated && ratingLength <= 5){
                icon.classList.add('green-rating');
            } else {
                icon.classList.add('gray-rating');
            }
        })
    }
}

new CustomCompanyAddon();