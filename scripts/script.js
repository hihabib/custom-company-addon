class CustomCompanyAddon {
    iconsContainerSelector = "";
    isHeader = false;

    constructor(iconsContainerSelector, isHeader) {
        // Set values to instance variables
        this.iconsContainerSelector = iconsContainerSelector;
        this.isHeader = isHeader;

        // is iconsContainerSelector is valid
        if(document.querySelector(iconsContainerSelector)){
            // Beautify Rating Icons
            this.ratingIconBeautify();
            // Add Rating tag in header
            this.addRatingTag();
        }
    }

    /**
     * Rating Icons
     * fontawesome i elements
     * @returns {array}
     */
    ratingIconsNodeList() {
        /**
         * @type {array}
         */
        const iconsContainer = Array.from(document.querySelectorAll(this.iconsContainerSelector));
        return iconsContainer.reduce((acc, next) => {
            acc.push(next.querySelectorAll('.fa.stars-style-solid'));
            return acc;
        }, []);
    }

    /**
     * Average rating length out of five;
     * @param index
     * @returns {number}
     */
    averageRatingLength(index) {
        const icons = this.ratingIconsNodeList()[index];
        return Array.from(icons).reduce((acc, next) => next.classList.contains('rated') ? ++acc : acc, 0);
    }

    /**
     * Top rating beatification
     * @returns {void}
     */
    ratingIconBeautify() {
        const iconsContainer = this.ratingIconsNodeList();
        // add classes for styling.
        iconsContainer.forEach((icons, index) => {
            const ratingLength = this.averageRatingLength(index);
            icons.forEach((icon) => {
                const isRated = icon.classList.contains("rated")
                if (isRated && ratingLength <= 2) {
                    icon.classList.add('red-rating');
                } else if (isRated && ratingLength <= 3) {
                    icon.classList.add('orange-rating');
                } else if (isRated && ratingLength <= 5) {
                    icon.classList.add('green-rating');
                } else {
                    icon.classList.add('gray-rating');
                }
            })
        })
    }

    /**
     * Add rating Tag after rating number
     * Only for Header
     * `Below Average`, `Average`, `Great`, `Excellent`
     * @returns {void}
     */
    addRatingTag() {
        if (this.isHeader) {
            const ratingLength = this.averageRatingLength(0);
            const reviewDescription = document.querySelector('.review-description-header-custom-company-addon');
            if (ratingLength <= 2) {
                reviewDescription.innerText += " Below Average";
            } else if (ratingLength <= 3) {
                reviewDescription.innerText += " Average";
            } else if (ratingLength <= 4) {
                reviewDescription.innerText += " Great";
            } else {
                reviewDescription.innerText += " Excellent";
            }
        }
    }
}

// Header
new CustomCompanyAddon(".rating-stars", true);

// Reviews
new CustomCompanyAddon(".comment-list .rating-stars", false);