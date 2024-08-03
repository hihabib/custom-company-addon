/**
 *
 * @type {HTMLUListElement | null}
 */
const companySearchResult = document.querySelector('#company_search_result');

let isCompanySearchResultHovered = false;
if(companySearchResult !== null){
    companySearchResult.addEventListener('mouseover', () => {
        isCompanySearchResultHovered = true
    });
    companySearchResult.addEventListener('mouseout', () => {
        isCompanySearchResultHovered = false
    });
}

/**
 * @type {HTMLInputElement | null}
 */
const companySearchInput = document.querySelector('#company_search_form input');
if (companySearchInput !== null) {
    // clear all search result on focus out
    companySearchInput.addEventListener('focusout', () => {
        if (companySearchResult !== null && !isCompanySearchResultHovered) {
            companySearchResult.innerHTML = '';
        }
    })

    // search company

    companySearchInput.addEventListener('focus', searchCompanyAndAddResult);
    companySearchInput.addEventListener('input', searchCompanyAndAddResult)
}

/**
 * Search Company and add search result to the dom
 * @param {Event} e
 */
function searchCompanyAndAddResult(e) {
    if(companySearchResult !== null){
        companySearchResult.innerHTML = `
                <li>
                    <span class="company-search-loading">Loading...</span>
                </li>`;
    }
    (async () => {
        /**
         * @type {function(...[*]): Promise<*>}
         */
        const getResult = debounce(searchCompany, 500);
        const companies = await getResult(e.target.value);
        addResults(companies);
    })()
}

/**
 * Add new company search results
 * @param {object} companies
 * @returns {void}
 */
function addResults(companies) {
    if (companySearchResult !== null) {
        // clear previous results
        companySearchResult.innerHTML = ``;

        if(companies.length !== 0){
            // add new results
            companies.forEach(({thumbnailUrl, title, permalink, exceprt}) => {
                const li = document.createElement('li');
                li.innerHTML = `
                        <div>
                            <div>
                                <a href="${permalink}"><img src="${thumbnailUrl}" alt=""></a>
                            </div>
                            <div>
                                <a href="${permalink}"><h4>${title}</h4></a>
                                <a href="${permalink}"><p>${exceprt}</p></a>
                            </div>
                        </div>
                    `;
                companySearchResult.append(li);
            });
        } else {
            // Not found
            companySearchResult.innerHTML = `
                <li>
                    ${pageInfo.notFoundShortcodeHTML}
                </li>`;
        }

    }
}

let timerId = null;

/**
 * Debounce Input Result
 * @param fn
 * @param delay
 * @returns {function(...[*]): Promise<unknown>}
 */
function debounce(fn, delay = 1000) {
    return (...args) => {
        return new Promise((resolve) => {
            clearTimeout(timerId);
            timerId = setTimeout(() => {
                resolve(fn(...args));
            }, delay);
        });

    };
};

/**
 * Get Company Search Result
 * @returns {void}
 */
async function searchCompany(searchQuery) {
    const res = await fetch(pageInfo.ajaxUrl, {
        'method': 'post',
        'body': (() => {
            const formData = new FormData();
            formData.append('action', "search_company");
            formData.append('search_query', searchQuery);
            formData.append('nonce', pageInfo.nonce);
            return formData;
        })()
    });
    return res.json();
}
