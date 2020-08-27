<?php
if (! isset($_SESSION)) {
    session_start();
}
require 'conn.php';
require 'autoload.php';
$level = new AccessLevel();
$login = new UserClass();
include 'top.php';
?>

</head>
<body>
<?php
include 'header.php'?>
 <?php
if ($login->isLoggedIn() === true) {    
    $c = new MyCRUD();
    if ($level->levels(@$_SESSION['user_id']) === 'Super Admin') {
        ?>
<div class="container">
		<div class="col-md-12">
			<h4>Usuarios</h4>
		</div>
<?php
        $c->getList("SELECT username, email, banned,is_activated, level FROM uverify", '');
        ?>
</div>
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<h4>Usuarios Activos</h4>
				<p>Activado = 1 / Inactivo = 0</p>
				<?php
        if (isset($_POST['desactivar'])) {
            $usersCount = count($_POST["username"]);
            for ($i = 0; $i < $usersCount; $i ++) {
                $c->wQueries("UPDATE uverify SET is_activated='" . $_POST['is_activated'][$i] . "' WHERE username='" . $_POST['username'][$i] . "'");
            }
            echo '<meta http-equiv="refresh" content="2">';
        }
        ?>
				<form method="post">
<?php
        $c->getList("SELECT username,is_activated FROM uverify", 'is_activated');
        ?>
<div class="form-group">
						<button type="submit" id="desactivar" name="desactivar"
							class="btn btn-dark">
							<span class="glyphicon glyphicon-plus"></span> Actualizar
						</button>
					</div>
				</form>
			</div>
			<div class="col-md-4">
				<h4>Usuarios bloqueados</h4>
				<p>Bloqueado = 0 / Desbloqueado = 1</p>
				<?php
        if (isset($_POST['bloquear'])) {
            $usersCount = count($_POST["username"]);
            for ($i = 0; $i < $usersCount; $i ++) {
                $c->wQueries("UPDATE uverify SET banned='" . $_POST['banned'][$i] . "' WHERE username='" . $_POST['username'][$i] . "'");
            }
            echo '<meta http-equiv="refresh" content="2">';
        }
        ?>
				<form method="post">				
<?php
        $c->getList("SELECT username, banned FROM uverify", 'banned');
        ?>
<div class="form-group">
						<button type="submit" id="bloquear" name="bloquear"
							class="btn btn-dark">
							<span class="glyphicon glyphicon-plus"></span> Actualizar
						</button>
					</div>
				</form>
			</div>
			<div class="col-md-4">
				<h4>Niveles de Usuario</h4>
				<p>Super Admin, Admin, Manager, Visita</p>
				<?php
        if (isset($_POST['nivel'])) {
            $usersCount = count($_POST["username"]);
            for ($i = 0; $i < $usersCount; $i ++) {
                $c->wQueries("UPDATE uverify SET level='" . $_POST['level'][$i] . "' WHERE username='" . $_POST['username'][$i] . "'");
            }
            echo '<meta http-equiv="refresh" content="2">';
        }
        ?>
				<form method="post">
<?php
        if (isset($_POST['bloquear'])) {}
        $c->getList("SELECT username, level FROM uverify", 'level');
        ?>
<div class="form-group">
						<button type="submit" id="nivel" name="nivel" class="btn btn-dark">
							<span class="glyphicon glyphicon-plus"></span> Actualizar
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
		
<?php
    }
} else {
    header('Location: index.php');
}
?>
</body>
</html>