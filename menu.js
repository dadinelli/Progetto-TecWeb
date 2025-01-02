/*$(document).ready(function(){
    $('.top_menu').click(function(){
        $('.top_menu ul').toggleClass('open');
    });
});*/

function toggleMenu() {
    const menu = document.querySelector('.top_menu ul');
    menu.classList.toggle('open');
}