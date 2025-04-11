const container = document.querySelector(".container");
const registerBtn = document.getElementById("register");
const loginBtn = document.getElementById("login");
const backHomeLinks = document.querySelectorAll(".back-home");

registerBtn.addEventListener("click", () => {
    container.classList.add("active");
});

loginBtn.addEventListener("click", () => {
    container.classList.remove("active");
});
