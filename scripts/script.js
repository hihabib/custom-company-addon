class CustomCompanyAddon {
    constructor() {
        this.ratingIconBeautify();
        this.addRatingTag();
    }

    /**
     * Rating Icons
     * fontawesome i elements
     * @returns {NodeListOf<Element>}
     */
    ratingIconsNodeList(){
        const iconsContainer = document.querySelector(".rating-stars");
        return iconsContainer.querySelectorAll('.fa.stars-style-solid');
    }

    /**
     * Average rating length out of five;
     * @returns {unknown}
     */
    averageRatingLength(){
        const icons = this.ratingIconsNodeList();
        return Array.from(icons).reduce((acc, next) => next.classList.contains('rated') ? ++acc : acc, 0);
    }
    /**
     * Top rating beatification
     */
    ratingIconBeautify(){
        const icons = this.ratingIconsNodeList();
        const ratingLength = this.averageRatingLength();
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

    addRatingTag(){
        const ratingLength = this.averageRatingLength();
        const reviewDescription =document.querySelector('.review-description-header-custom-company-addon');
        if(ratingLength <= 2){
            reviewDescription.innerText += "Below Average";
        } else if(ratingLength <= 3){
            reviewDescription.innerText += "Average";
        } else if(ratingLength <= 4){
            reviewDescription.innerText += "Great";
        } else {
            reviewDescription.innerText += "Excellent";
        }
    }
}

new CustomCompanyAddon();