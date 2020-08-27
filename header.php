<header>
    <!-- Static navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">CRUD Administración</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <?php if (!empty(@$_SESSION['user_id'])) { ?>
                <ul class="navbar-nav">
                    <li class="nav-item active"><a class="nav-link"
                                                   href="../index.php?w=select">Inicio</a></li>
                        <?php
                        if ($level->levels(@$_SESSION['user_id']) === 'Super Admin' || $level->levels(@$_SESSION['user_id']) === 'Admin') {
                            ?>
                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
                                                         href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                                                         aria-haspopup="true" aria-expanded="false"> Granjas </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="index.php?w=list&tbl=granjas">Granjas</a></li>
                                <li><a class="dropdown-item" href="index.php?w=list&tbl=personal">Personal</a></li>
                                <li><a class="dropdown-item" href="index.php?w=list&tbl=empresa">Empresa</a></li>
                                <li><a class="dropdown-item"
                                       href="../index.php?w=list&tbl=empresa_integrada">Empresa
                                        Integrada</a></li>
                            </ul></li>
                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
                                                         href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                                                         aria-haspopup="true" aria-expanded="false"> Contabilidad </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="index.php?w=list&tbl=ingresos">Ingresos</a></li>
                                <li><a class="dropdown-item" href="index.php?w=list&tbl=salidas">Salidas</a></li>
                                <li><a class="dropdown-item" href="pagos.php">Pagos</a></li>
                                <li><a class="dropdown-item" href="personal_despacho.php">Personal despacho</a></li>
                                <li><a class="dropdown-item" href="despacho.php">Despacho</a></li>						
                                <li><a class="dropdown-item"
                                       href="index.php?w=list&tbl=fecha_laboral">Fecha Laboral</a></li>
                                <li><a class="dropdown-item"
                                       href="index.php?w=list&tbl=gratificaciones">Gratificaciones</a></li>
                                <li><a class="dropdown-item"
                                       href="index.php?w=list&tbl=vacaciones">Vacaciones</a></li>
                            </ul></li>
                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
                                                         href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                                                         aria-haspopup="true" aria-expanded="false">Almacenes</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="index.php?w=list&tbl=almacen">Almacen</a></li>
                                <li><a class="dropdown-item"
                                       href="index.php?w=list&tbl=inventario">Inventario</a></li>
                            </ul></li>
                        <li class="nav-item"><a class="nav-link" href="buscar.php">Buscar
                                contenido</a></li>
                        <?php
                    }
                    if ($level->levels(@$_SESSION['user_id']) === 'Super Admin') {
                        ?>

                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
                                                         href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                                                         aria-haspopup="true" aria-expanded="false"> Configuración </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="table_config.php">Administrar
                                        Tablas</a></li>
                                <li><a class="dropdown-item" href="querybuilder.php">Enlazar
                                        tablas</a></li>
                                <li><a class="dropdown-item" href="dashboard.php">Usuarios</a></li>
                            </ul></li>
                        <?php
                    }

                    if ($level->levels(@$_SESSION['user_id']) === 'Manager') {
                        ?>
                        <li class="nav-item"><a class="nav-link"
                                                href="index.php?w=list&tbl=almacen">Almacen</a></li>
                        <li class="nav-item"><a class="nav-link"
                                                href="index.php?w=list&tbl=inventario">Inventario</a></li>
                        <li class="nav-item"><a class="nav-link" href="pagos.php">Pagos</a></li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <ul class="navbar-nav ml-auto">
                <?php if (!empty(@$_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link"
                                            href="profile.php"><span class="glyphicon glyphicon-user"></span>
                            <?php print_r(@$_SESSION['user_id']) ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><span
                                class="glyphicon glyphicon-log-out"></span> Desconectar</a></li>
                    <?php else: ?>
                    <li class="nav-item"><a class="nav-link"
                                            href="login.php"><span class="glyphicon glyphicon-log-in"></span>
                            Iniciar sesión</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php"><span
                                class="glyphicon glyphicon-user"></span> Regístrate</a></li>
                        <?php endif; ?>
            </ul>

            <!-- /Navigation buttons -->
        </div>
    </nav>
</header>