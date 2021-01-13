const modal = document.querySelector(".modal");
modal.style.display = "flex";
const url = new URL(window.location);


const closeModal = () => {
    modal.style.display = "none";
    window.location = url.origin + url.pathname;
}

window.onclick = (event) => {
    if (event.target == modal) {
        modal.style.display = "none";
        window.location = url.origin + url.pathname;
    }
}