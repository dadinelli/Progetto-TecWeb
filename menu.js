const navToggle = document.getElementById('hamb-button');
const menuList = document.getElementById('menu-list');

navToggle.addEventListener('click', () => {
    menuList.classList.toggle('visible');
});

document.addEventListener('click', (event) => {
    if (!menuList.contains(event.target) && !navToggle.contains(event.target)) {
        menuList.classList.remove('visible');
    }
});