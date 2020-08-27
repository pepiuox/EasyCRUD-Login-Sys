<?php

// Check that data has been submited.
if (isset($_POST['login'])) {

    // User input from Login Form(loginForm.php).
    $user = trim($_POST['username']);
    $userpsw = trim($_POST['password']);
    $userpin = trim($_POST['userpin']);

    // Check that both username and password fields are filled with values.
    if (! empty($user) && ! empty($userpsw) && ! empty($userpin)) {

        $rquery = $conn->query(" SELECT * FROM uverify WHERE username='$user' AND mkpin='$userpin' AND activation_code='NULL'");
        $num = $rquery->num_rows;
        $urw = $rquery->fetch_assoc();

        if ($num === 1) {

            $cml = $urw['email'];
            $passw = $urw['password'];
            $ban = $urw['banned'];
            $actv = $urw['is_activated'];
            $level = $urw['level'];

            define("ENCRYPT_METHOD", "AES-256-CBC");
            define("SECRET_KEY", $urw['mktoken']);
            define("SECRET_IV", $urw['mkkey']);
            define('ENCRYPTION_KEY', $urw['mkhash']);

            function ende_crypter($action, $string)
            {
                $output = false;
                $encrypt_method = ENCRYPT_METHOD;
                $secret_key = SECRET_KEY;
                $secret_iv = SECRET_IV;
                // hash
                $key = hash('sha256', $secret_key);
                // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                $iv = substr(hash('sha256', $secret_iv), 0, 16);
                if ($action == 'encrypt') {
                    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                    $output = base64_encode($output);
                } else if ($action == 'decrypt') {
                    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
                }
                return $output;
            }
            $pass = ende_crypter('decrypt', $passw);

            $mail = ende_crypter('encrypt', $cml);

            if ($userpsw === $pass) {
                if ($actv > 0 && $ban > 0) {
                    $sqr = $conn->query("SELECT username, email, password, mkpin FROM users WHERE username = '$user' AND email='$mail' AND password = '$passw' AND mkpin = '$userpin'");
                    if ($sqr->num_rows === 1) {
                        $row = $sqr->fetch_assoc();
                        $_SESSION['user_id'] = $row['username'];
                        $_SESSION['levels'] = $level;
                        $_SESSION['message'] = 'Felicitaciones usted ahora tiene acceso.';
                        header("Location: index.php");
                        echo '<meta http-equiv="refresh" content="3;URL=index.php" />';
                    }
                } else {
                    $_SESSION['message'] = 'No se pudo completar el acceso, puede que no este activo o bloqueado, comuniquese con el soporte.';
                }
            } else {
                $_SESSION['message'] = 'Usuario o contraseña invalido.';
            }
        } else {
            $_SESSION['message'] = 'Usuario no esta activado.';
        }
    } else {
        $_SESSION['message'] = 'Por favor llene todos los campos requeridos.';
    }
}
/* End Login() */

?>

<div class="container">
	<!-- Login form -->
	<div class="loginForm">
		<!-- If there is an error it will be shown. --> 
            <?php if(!empty($_SESSION['message'])): ?>
                <div class="alert alert-danger alert-container"
			id="alert">
			<strong><center><?php echo htmlentities($_SESSION['message']) ?></center></strong>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
		<form name="loginform" class="form-login" method="post">
			<h3>!Bienvenido nuevamente!</h3>
			<hr>
			<div class="input-group mb-1">
				<div class="input-group-append">
					<span class="input-group-text"> <i class="fa fa-user"
						aria-hidden="true"></i></span>
				</div>
				<input type="text" name="username" id="username"
					placeholder="Usuario" class="form-control" autocomplete="off"
					required autofocus>
			</div>
			<div class="input-group mb-1">
				<div class="input-group-append">
					<span class="input-group-text"> <i class="fa fa-key"
						aria-hidden="true"></i></span>
				</div>
				<input type="password" name="password" id="password"
					placeholder="Clave" class="form-control" autocomplete="off"
					required>
			</div>
			<div class="input-group mb-1">
				<div class="input-group-append">
					<span class="input-group-text"> <i class="fa fa-cog"
						aria-hidden="true"></i></span>
				</div>
				<input type="password" name="userpin" id="userpin" placeholder="PIN"
					class=" form-control" autocomplete="off" required>
			</div>
			<!-- forgot-password -->
			<div class="form-group">				
				<div class="forgot-password">
					<a href="forgot.php">¿Olvidaste tu contraseña?</a>
				</div>
			</div>
			<div class="form-group">
				<input type="submit" name="login" value="Ingresar"
					class="btn btn-lg btn-block submit" />
			</div>
		</form>
	</div>
	<!-- End Login Form-->

	<!-- URL to registration form -->
	<div class="cnt">
		<a href="register.php">¿No tengo una cuenta? Crear una</a>
	</div>

	<!-- Back to main page -->
	<div class="cnt gray">
		<a href="index.php">Retornar al inicio</a>
	</div>

</div>
<!-- End div -->