<!-- signin-page -->
<div class="container">
	<!-- Registration form -->


	<div class="loginForm">
		
					
                    <?php
                    if (isset($_POST['login'])) {

                        $username = protect($_POST['username']);
                        $email = protect($_POST['email']);
                        $password = protect($_POST['password']);
                        $repassword = protect($_POST['repassword']);

                        $dt = new DateTime();
                        $time = $dt->format('Y-m-d H:i:s');
                        $ip = $_SERVER['REMOTE_ADDR'];

                        function randHash($len = 32)
                        {
                            return substr(sha1(openssl_random_pseudo_bytes(21)), - $len);
                        }

                        function randKey($len = 32)
                        {
                            return substr(sha1(openssl_random_pseudo_bytes(13)), - $len);
                        }

                        function encKey($len = 32)
                        {
                            return substr(sha1(openssl_random_pseudo_bytes(17)), - $len);
                        }

                        $ekey = randHash();
                        $eiv = randkey();
                        $enck = enckey();

                        define("ENCRYPT_METHOD", "AES-256-CBC");
                        define("SECRET_KEY", $ekey);
                        define("SECRET_IV", $eiv);

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

                        $check_username = $conn->query("SELECT username FROM users WHERE username='$username'");
                        $check_email = $conn->query("SELECT email FROM users WHERE email='$email'");

                        if (empty($username) || empty($email) || empty($password) || empty($repassword)) {
                            echo "¡Complete los campos o casillas!";
                        } elseif (! isValidUsername($username)) {
                            echo "¡Ingrese un usuario valido!";
                        } elseif ($check_username->num_rows > 0) {
                            echo "¡El usuario ya existe!";
                        } elseif (! isValidEmail($email)) {
                            echo "¡Ingrese un correo electronico valido!";
                        } elseif ($check_email->num_rows > 0) {
                            echo "¡El correo electronico ya existe!";
                        } elseif ($password !== $repassword) {
                            echo "¡La contreseña no coincide!";
                        } else {
                            if ($password === $repassword) {

                                $newid = uniqid(rand(), false);
                                $pass = ende_crypter('encrypt', $password);
                                $cml = ende_crypter('encrypt', $email);
                                $pin = rand(000000, 999999);

                                $insert = $conn->query("INSERT INTO users (idUser,username,email,password,status,ip,signup_time,email_verified,document_verified,mobile_verified) VALUES ('$newid','$username','$cml','$pass','0','$ip','$time','0','0','0')");
                                $info = $conn->query("INSERT INTO info(id) VALUES ('$newid')");
                                if ($insert === true && $info === true) {
                                    $code = substr(md5(mt_rand()), 0, 32);
                                    $insert2 = $conn->query("INSERT INTO uverify (iduv,username,email,password,mktoken,mkkey,mkhash,mkpin,banned,is_activated,activation_code) VALUES ('$newid','$username','$email','$pass','$ekey','$eiv','$enck','$pin','1','0','$code')");
                                    if ($insert2 === true) {
                                        $to = $email;
                                        $subject = "Su código de activación para Membresía.";
                                        $from = 'contact@labemotion.net'; // This should be changed to an email that you would like to send activation e-mail from.
                                        $body = 'Tu código de activación es: ' . $code . '<br> Para activar su cuenta, haga clic en el siguiente enlace' . ' <a href="http://sys.chicsalonyspa.com/verify.php?id=' . $email . '&code=' . $code . '">verify.php?id=' . $email . '&code=' . $code . '</a>.'; // Input the URL of your website.
                                        $headers = "From: " . $from . "\r\n";
                                        $headers .= "Reply-To: " . $from . "\r\n";
                                        $headers .= "MIME-Version: 1.0\r\n";
                                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                                        mail($to, $subject, $body, $headers);
                                    }

                                    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<strong>¡Recuerde! Guarde esto, su código PIN es: ' . $pin . '</strong><br>Gracias por registrarse</div>';

                                    $query = $conn->query("SELECT * FROM uverify WHERE username='$username' and email='$email' and password='$pass'");
                                    if ($query->num_rows > 0) {

                                        $row = $query->fetch_assoc();
                                        $upid = $row['iduv'];
                                        $uekey = $row['mktoken'];
                                        $ueiv = $row['mkkey'];
                                        $uenck = $row['mkhash'];
                                        $upin = $row['mkpin'];
                                        $_SESSION['uid'] = $row['iduv'];

                                        $update = $conn->query("UPDATE users SET mkpin='$upin' WHERE idUser='$upid '");
                                        if ($update === true) {
                                            $_SESSION['SuccessMessage'] = '¡El usuario ha sido creado!';
                                            echo '<div class="alert alert-success"><p>¡El usuario se a registrado con exito!</p></div>';
                                            echo '<meta http-equiv="refresh" content="30;URL=index.php" />';
                                        }
                                    } else {
                                        echo '<div class="alert alert-success"><p>El registro de seguridad no se pudo completar, consulte con el soporte técnico.</p></div>';
                                    }
                                } else {
                                    echo '<div class="alert alert-success"><p>Error en la creación del usuario, consulte con el soporte para continuar con su registro.</p></div>';
                                }
                            }
                        }
                    }
                    ?>
                    <!-- form -->

		<form action="register.php" name="registerform" method="POST"
			class="form-registration">
			<h3>¡Regístrate!</h3>
			<hr>


			<div class="input-group mb-1">
				<div class="input-group-append">
					<span class="input-group-text"> <i class="fa fa-user"
						aria-hidden="true"></i></span>
				</div>
				<input type="text" class="form-control" name="username"
					placeholder="Usuario">
			</div>
			<div class="input-group mb-1">
				<div class="input-group-append">
					<span class="input-group-text"> <i class="fa fa-envelope"
						aria-hidden="true"></i></span>
				</div>
				<input type="text" class="form-control" name="email"
					placeholder="Correo electronico">
			</div>
			<div class="input-group mb-1">
				<div class="input-group-append">
					<span class="input-group-text"> <i class="fa fa-key"
						aria-hidden="true"></i></span>
				</div>
				<input type="password" class="form-control" name="password"
					placeholder="Contraseña">
			</div>
			<div class="input-group mb-1">
				<div class="input-group-append">
					<span class="input-group-text"> <i class="fa fa-key"
						aria-hidden="true"></i></span>
				</div>
				<input type="password" class="form-control" name="repassword"
					placeholder="Repite la contraseña">
			</div>
			<button type="submit" name="login" class="btn">Registrarse</button>

			<!-- forgot-password -->
			<div class="form-group">				
				<div class="forgot-password">
					<a href="forgot.php">¿Olvidaste tu contraseña?</a>
				</div>
			</div>
			<!-- forgot-password -->
						<?php if(!empty($_SESSION['message'])): ?>
                <div class="alert alert-danger alert-container"
				id="alert">
				<strong><center><?php echo htmlentities($_SESSION['message']) ?></center></strong>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
					</form>
		<!-- form -->
		<div class="cnt">
			<a href="login.php">Iniciar sesión con cuenta.</a>
		</div>
		<!-- Back to main page -->
		<div class="cnt gray">
			<a href="index.php">Retornar al inicio</a>

			<!-- user-login -->
		</div>
		<!-- row -->
	</div>
	<!-- container -->
</div>
<!-- signin-page -->