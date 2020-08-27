(function () {

    var classTop = "social-ontop";
    if ($(window).width() > 800) {


        // we start hidden, to avoid flickering

        document.write("<style id='temp-social-ontop'>.social {height:100px; transition: none !important}</style>")

        function update() {
            // toggle classTop based on the scrollTop property of document

            var social = document.querySelector(".social");

            if (window.scrollY > 15) {

                social.classList.remove(classTop);
            } else {

                social.classList.add(classTop);
            }
        }

        document.addEventListener("DOMContentLoaded", function (event) {
            $(window).on('show.bs.collapse', function (e) {
                $(e.target).closest("." + classTop).removeClass(classTop);
            });

            $(window).on('hidden.bs.collapse', function (e) {
                update();
            });
            update();
            // still hacking to avoid flickering
            setTimeout(function () {

                document.querySelector("#temp-social-ontop").remove();
            });
        });

        window.addEventListener("scroll", function () {
            update();
        });
    }
})();