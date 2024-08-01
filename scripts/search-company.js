


/**
 * Get Company Search Result
 * @returns {Promise<object>}
 */
async function searchCompany(){
    const res = await fetch(pageInfo.ajaxUrl, {
        'method': 'post',
        'body': (() => {
            const formData = new FormData();
            formData.append('action', "search_company");
            formData.append('search_query', "Urban");
            formData.append('nonce', pageInfo.nonce);
            return formData;
        })()
    });
    return res.json();
}
