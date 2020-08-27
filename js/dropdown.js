$(document).ready(function () {

    $('.dropdown-menu li.dropdown ul').hide();
    $('.dropdown-menu li.dropdown').hover(
            function () {
                $(this).find('ul:first').stop(true, true);
                $(this).find('ul:first').slideDown('fast').css('z-index', '100');
            },
            function () {
                $(this).find('ul:first').slideUp().css('z-index', '10');
            }
    );

    $.fn.dropdown = function () {

        return this.each(function () {

            $(this).hover(function () {
                $(this).addClass("hover");
                $('> .dir', this).addClass("open");
                $('ul:first', this).css('visibility', 'visible');
            }, function () {
                $(this).removeClass("hover");
                $('.open', this).removeClass("open");
                $('ul:first', this).css('visibility', 'hidden');
            });

        });

    };
    if ($("ul.dropdown").length) {
        $("ul.dropdown-menu li").dropdown();
    }
    $('.dropdown-menu a.dropdown-toggle').mouseover(function (e) {
        var $el = $(this);
        var $parent = $(this).offsetParent(".dropdown-menu");
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
        }
        var $subMenu = $(this).next(".dropdown-menu");
        $subMenu.toggleClass('show');

        $(this).parent("li").toggleClass('show');

        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
            $('.dropdown-menu .show').removeClass("show");
        });

        if (!$parent.parent().hasClass('navbar-nav')) {
            $el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 4});
        }

        return false;
    });
    $('.dropdown-submenu').mouseover(function (e) {
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });
    /*
     $('.nav-link').click(function(){
     var href = $(this).attr('href');       
     window.location.href = href;
     });
     */
});


window.onscroll = function () {
    growShrinkLogo();
};

var logo = document.querySelector(".navbar-brand");
var endOfDocumentTop = 150;
var size = 0;

function growShrinkLogo() {
    var scroll = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;

    if (size === 0 && scroll > endOfDocumentTop) {
        logo.style.width = '209px';
        logo.style.height = '70px';
        size = 1;
    } else if (size === 1 && scroll <= endOfDocumentTop) {
        logo.style.width = '269px';
        logo.style.height = '90px';
        size = 0;
    }
}
$(window).scroll(function () {
    $('.navbar-logo img').css({width: $(this).scrollTop() > 100 ? "185px" : "251px"});
    $('.navbar-logo img').css({height: $(this).scrollTop() > 100 ? "62px" : "84px"});
    var scrolled = $(window).scrollTop();
    if (scrolled > 48) {
        $(".menu-logo").css({'width': '227px', 'height': '76px'});
    } else {
        $(".menu-logo").css({'width': '281px', 'height': '94px'});
    }
});