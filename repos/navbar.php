
<style>
    .navbar-light .navbar-nav .nav-link {
        color: rgb(64, 64, 64);
    }
    .btco-menu li > a {
        padding: 10px 15px;
        color: #000;
    }

    .btco-menu .active a:focus,
    .btco-menu li a:focus ,
    .navbar > .show > a:focus{
        background: transparent;
        outline: 0;
    }

    .dropdown-menu .show > .dropdown-toggle::after{
        transform: rotate(-90deg);
    }
    @media (min-width: 979px) {
        ul.nav li.dropdown:hover > ul.dropdown-menu {
            display: block;
            margin-top:0px;
        }
    }
</style>
<script>
    $(document).ready(function () {
        $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
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
    });
</script>
<!-- new menu -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Easy CRUD Vue</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item mx-1 align-items-center d-flex justify-content-center">
                    <a class="nav-link" href="index.php?w=select">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="table_config.php">Configure table</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="showCols.php">Show Cols</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="queryCols.php">Query Cols</a>
                </li>
                <?php
                $tq = "SELECT * FROM table_config WHERE tcon_Id='1'";
                $rTQ = $conn->query($tq);
                $nr = $rTQ->num_rows;
                if ($nr > 0) {
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Tables
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <?php
                            $rwtq = $rTQ->fetch_array();
                            $mtq = explode(",", $rwtq['table_name']);
                            foreach ($mtq as $v) {
                                $rv = str_replace("_", " ", $v);
                                echo'<li><a class="dropdown-item" href="index.php?view=list&tbl=' . $v . '">' . ucfirst($rv) . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }
                ?>                                                

                <li class="nav-item">
                    <a class="nav-link" href="querybuilder.php">Query Builder</a>
                </li>

            </ul>
        </div>
    </div>
</nav>
<!-- /container -->
