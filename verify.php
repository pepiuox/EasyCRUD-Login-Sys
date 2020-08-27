<?php
if (!isset($_SESSION)) {
    session_start();
}
require 'conn.php';
require 'autoload.php';
$verify = new UserClass();
include 'top.php';
?>
</head>
<body>
    <?php
    include 'header.php';
    ?>
    <div class="container container-content">
        <div class="col-md-12 p-3">
            <?php if ($verify->Verify() == TRUE): ?>
                <h3 class="text-center">La cuenta ha sido activada.</h3>
                <p class="text-center">
                    Para iniciar sesión, haga clic en <a href="login.php"
                                                         class="btn btn-primary btn-sm">Iniciar sesión</a>
                </p>
            <?php elseif ($verify->Verify() == FALSE): ?>
                <h3 class="text-center">Ha habido un error al activar su cuenta.</h3>
                <p class="text-center">
                    Por favor, póngase en contacto con soporte en <a
                        href="mailto:contact@labemotion.net?Subject=Soporte"
                        class="link-blue">contact@labemotion.net</a>.
                </p>    
            <?php endif; ?>  
        </div>
    </div>
</body>
</html>