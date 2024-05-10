let profileDropdownList = document.querySelector(".profile-dropdown-list");
let btns = document.querySelector(".profile-dropdown-btn");

let classList = profileDropdownList.classList;

const toggle = () => classList.toggle("active");

window.addEventListener("click", function (e) {
if (!btns.contains(e.target)) classList.remove("active");
});