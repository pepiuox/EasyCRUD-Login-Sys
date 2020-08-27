<!-- Static navbar -->
<nav class="navbar navbar-expand-md navbar-light bg-light">
    <a class="navbar-brand" href="index.php">CRUD Administracion</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <?php if (!empty(@$_SESSION['user_id'])) { ?>

                <li class="nav-item active"><a class="nav-link"
                                               href="index.php?w=select">Inicio</a></li>
                <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
                                                 href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                                                 aria-haspopup="true" aria-expanded="false"> Granjas </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="index.php?w=list&tbl=granjas">Granjas</a></li>
                        <li><a class="dropdown-item" href="index.php?w=list&tbl=personal">Personal</a></li>
                        <li><a class="dropdown-item" href="index.php?w=list&tbl=empresa">Empresa</a></li>
                        <li><a class="dropdown-item"
                               href="index.php?w=list&tbl=empresa_integrada">Empresa Integrada</a></li>
                    </ul></li>
                <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
                                                 href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                                                 aria-haspopup="true" aria-expanded="false"> Contabilidad </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="index.php?w=list&tbl=ingresos">Ingresos</a></li>
                        <li><a class="dropdown-item" href="index.php?w=list&tbl=salidas">Salidas</a></li>
                        <li><a class="dropdown-item"
                               href="index.php?w=list&tbl=fecha_laboral">Fecha Laboral</a></li>
                        <li><a class="dropdown-item"
                               href="index.php?w=list&tbl=gratificaciones">Gratificaciones</a></li>
                        <li><a class="dropdown-item" href="index.php?w=list&tbl=vacaciones">Vacaciones</a></li>
                    </ul></li>
                <li class="nav-item"><a class="nav-link" href="pagos.php">Pagos</a></li>
                <li class="nav-item"><a class="nav-link" href="buscar.php">Buscar
                        contenido</a></li>
                <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
                                                 href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                                                 aria-haspopup="true" aria-expanded="false"> Ver tablas </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <?php
                        $tq = "SELECT * FROM table_config WHERE tcon_Id='1'";
                        $rTQ = $link->query($tq);
                        $rwtq = mysqli_fetch_array($rTQ);
                        $mtq = explode(",", $rwtq['table_name']);
                        foreach ($mtq as $v) {
                            $rv = str_replace("_", " ", $v);
                            echo '<li><a class="dropdown-item" href="index.php?w=list&tbl=' . $v . '">' . ucfirst($rv) . '</a></li>';
                        }
                        ?>                                                
                    </ul></li>
                <li class="nav-item"><a class="nav-link" href="table_config.php">Administrar
                        Tablas</a></li>
                <li class="nav-item"><a class="nav-link" href="querybuilder.php">Enlazar
                        tablas</a></li>
            <?php } ?>
        </ul>

        <ul class="navbar-nav ml-auto">
            <?php if (!empty(@$_SESSION['user_id'])): ?>
                <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
                                                 href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                                                 aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span>
                        Cuenta</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item"
                               href="profile.php">Perfil</a></li>
                        <li><a class="dropdown-item"
                               href="profile.php">Cambiar Contraseña</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="logout.php"><span
                            class="glyphicon glyphicon-log-out"></span> Desconectar</a></li>
                <?php else: ?>
                <li class="nav-item"><a class="nav-link"
                                        href="dashboard/login.php"><span class="glyphicon glyphicon-log-in"></span>
                        Iniciar sesión</a></li>
                <li class="nav-item"><a class="nav-link" href="registration.php"><span
                            class="glyphicon glyphicon-user"></span> Regístrate</a></li>
                    <?php endif; ?>
        </ul>
    </div>
</nav>
