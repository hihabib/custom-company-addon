class CustomCompanyAddon {
    iconsContainerSelector = "";
    isHeader = false;

    /**
     * CustomCompanyAddon Class Constructor
     * @param iconsContainerSelector
     * @param isHeader
     */
    constructor(iconsContainerSelector, isHeader) {
        // Set values to instance variables
        this.iconsContainerSelector = iconsContainerSelector;
        this.isHeader = isHeader;


        // is iconsContainerSelector is valid
        this.iconsContainerLoad()
            .then(() => {
                // Beautify Rating Icons
                this.ratingIconBeautify();
                // Add Rating tag in header
                this.addRatingTag();
            })

    }

    iconsContainerLoad() {
        return new Promise((resolve) => {
            let intervalId = 0;
            intervalId = setInterval(() => {
                try {
                    if(document.querySelector(this.iconsContainerSelector) !== null){
                        clearInterval(intervalId);
                        resolve();
                    }
                } catch (err) {
                }
            }, 1000)

        });
    }

    /**
     * Rating Icons
     * fontawesome i elements
     * @returns {array}
     */
    ratingIconsArrayList() {
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
        const icons = this.ratingIconsArrayList()[index];
        return Array.from(icons).reduce((acc, next) => next.classList.contains('rated') ? ++acc : acc, 0);
    }

    /**
     * Top rating beatification
     * @returns {void}
     */
    ratingIconBeautify() {
        const iconsContainer = this.ratingIconsArrayList();
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
            const reviewDescription = document.querySelector('.stars-avg-rating .rating-text');
            if (ratingLength <= 2) {
                reviewDescription.innerText += "&nbsp;&nbsp;•&nbsp;&nbsp;Below Average";
            } else if (ratingLength <= 3) {
                reviewDescription.innerText += "&nbsp;&nbsp;•&nbsp;&nbsp;Average";
            } else if (ratingLength <= 4) {
                reviewDescription.innerText += "&nbsp;&nbsp;•&nbsp;&nbsp;Great";
            } else {
                reviewDescription.innerText += "&nbsp;&nbsp;•&nbsp;&nbsp;Excellent";
            }
        }
    }
}

// Single Page
if (!Boolean(pageInfo.isArchive)) {
    // Header
    new CustomCompanyAddon(".rating-stars", true);
    // Reviews
    new CustomCompanyAddon(".comment-list .rating-stars", false);
} else { // archive page
    new CustomCompanyAddon(".rating-stars", false);
}

