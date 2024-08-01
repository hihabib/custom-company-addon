/**
 * @type {HTMLInputElement | null}
 */
const companySearchInput = document.querySelector('#company_search_form input');
if (companySearchInput !== null) {
    companySearchInput.addEventListener('input', (e) => {
        (async () => {
            /**
             * @type {function(...[*]): Promise<Object>}
             */
            const getResult = debounce(searchCompany, 500);
            const result = await getResult(e.target.value);
            console.log(result)
        })()
    })
}

/**
 * Debounce search input
 * @param fn
 * @param delay
 * @returns {function(...[*]): Promise<object>}
 */
function debounce(fn, delay = 1000) {
    let timerId = null;
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
 * @returns {Promise<object>}
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
