const searchInput = document.getElementById("search");
const shopCards = document.querySelectorAll(".shop-card-link");

searchInput.addEventListener("input", () => {
    const keyword = searchInput.value;
    console.log(keyword);
    shopCards.forEach(card => {
        const address = card.querySelector(".shop-address").textContent;
        console.log(address);
        if (address.includes(keyword)) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
});