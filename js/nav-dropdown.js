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

    $('.dropdown-menu li.dropdown').dropdown(function () {

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

    });

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

    $('.nav-link').click(function () {
        var href = $(this).attr('href');
        window.location.href = href;
    });


    $('.navbar-toggle').hover(function () {
        $('.navbar-nav').toggleClass('slide-in');
        $('.side-body').toggleClass('body-slide-in');
        $('#search').removeClass('in').addClass('collapse').slideUp(200);

        /// uncomment code for absolute positioning tweek see top comment in css
        //$('.absolute-wrapper').toggleClass('slide-in');

    });

    // Remove menu for searching
    $('#search-trigger').click(function () {
        $('.navbar-nav').removeClass('slide-in');
        $('.side-body').removeClass('body-slide-in');

        /// uncomment code for absolute positioning tweek see top comment in css
        //$('.absolute-wrapper').removeClass('slide-in');

    });
});
