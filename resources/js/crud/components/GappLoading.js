// Loading ...

export function showLoading() {
    let card_crud = document.getElementById('card_crud');
    if (card_crud) {
        let loadingDiv = document.createElement('div');
        loadingDiv.id = 'loading';
        loadingDiv.innerHTML = '<div class="spinner-border"></div>';
        card_crud.appendChild(loadingDiv);
    }else{
        return false;
    }
}
export function hideLoading() {
    let card_crud = document.getElementById('card_crud');
    let loadingDiv = document.getElementById('loading');
    loadingDiv.remove();
}
