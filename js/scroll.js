$(document).ready(function () {
    $('.contentHeader').css("margin-top", "70px");
    $(window).on("load, resize", function () {
        if ($(window).width() > 768) {
            $(".navbar").addClass("fixed-top");
            $(".header-cards").addClass("section-overlapping");            
        } else {
            $(".navbar").removeClass("fixed-top");
            $('.navbar-logo img').css({height: "62px"});
            $('.navbar-logo img').css({width: "216.7px"});
            $('.carousel-caption').css({top: "1px"});            
            $(".header-cards").removeClass("section-overlapping");
        }
    });
});
window.onscroll = function () {
    growShrinkLogo();
};

var logo = document.querySelector(".navbar-brand");
var endOfDocumentTop = 120;
var size = 0;

function growShrinkLogo() {
    var scroll = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;

    if (size === 0 && scroll > endOfDocumentTop) {
        logo.style.height = '52px';
        size = 1;
    } else if (size === 1 && scroll <= endOfDocumentTop) {
        logo.style.height = '70px';
        size = 0;
    }
}
$(window).scroll(function () {
    $('.navbar-logo img').css({width: $(this).scrollTop() > 100 ? "215px" : "290px"});
    $('.navbar-logo img').css({height: $(this).scrollTop() > 100 ? "52px" : "70px"});

    var scrolled = $(window).scrollTop();
    if (scrolled > 48) {
        $(".menu-logo").css({'height': '62px'});
        $('.top-content').css({'margin-top': "0px"});
    } else {
        $(".menu-logo").css({'height': '80px'});
        $('.top-content').css({'margin-top': "90px"});
    }
});
