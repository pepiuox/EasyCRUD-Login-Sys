window.onscroll = function () {
    growShrinkLogo();
};

var logo = document.querySelector(".navbar-brand");
var endOfDocumentTop = 150;
var size = 0;

function growShrinkLogo() {
    var scroll = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;

    if (size === 0 && scroll > endOfDocumentTop) {
        logo.style.height = '62px';
        size = 1;
    } else if (size === 1 && scroll <= endOfDocumentTop) {
        logo.style.height = '80px';
        size = 0;
    }
}
$(window).scroll(function () {
    $('.navbar-logo img').css({width: $(this).scrollTop() > 100 ? "186px" : "240px"});
    $('.navbar-logo img').css({height: $(this).scrollTop() > 100 ? "62px" : "80px"});
    $('.navbar').addClass('fixed-top');
    
    var scrolled = $(window).scrollTop();
    if (scrolled > 48) {
        $(".menu-logo").css({'height': '60px'});
        $('.navbar').addClass('fixed-top');
    } else {
        $(".menu-logo").css({'height': '80px'});
        $('.navbar').removeClass('fixed-top');
    }
});