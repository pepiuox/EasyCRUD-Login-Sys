<?php
if (isset($_POST['updatePassword'])) {
    // User input from Forgot password form(passwordResetForm.php).
    $password3 = trim($_POST['password3']);
    $password2 = trim($_POST['password2']);
    $email = $_POST['email'];
    $forgotkey = $_POST['key'];

    // Check that both entered passwords match.
    if ($password3 === $password2) {
        if (! empty($password3) && ! empty($email)) {
            $very = $conn->query("SELECT email, password_key FROM uverify WHERE email='$email' AND password_key='$forgotkey'");

            if ($very->num_rows === 1) {
                $dt = $very->fetch_assoc();
                $duv = $dt['iduv'];
                $pin = $dt['mkpin'];

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

                $securing = ende_crypter('encrypt', $password2);
                $cml = ende_crypter('encrypt', $email);
                $clenkey = '';
                $upd = $conn->query("UPDATE uverify password='$securing', mktoken='$ekey', mkkey='$eiv', mkhash='$enck', password_key='$clenkey' WHERE email='$email' ANDpassword_key=''");

                $stmt = $conn->prepare("UPDATE users SET email = ?, password = ?  WHERE idUser=? AND mkpin=?");
                $stmt->bind_param("sssi", $cml, $securing, $duv, $pin);
                $stmt->execute();
                $stmt->close();
                if ($upd === TRUE) {
                    header('Location: index.php');
                }
            } else {
                $_SESSION['message'] = 'No coinciden los datos para actualizar su contraseña.';
            }
        } else {
            $_SESSION['message'] = 'Por favor llene todos los campos requeridos.';
        }
    } else {
        $_SESSION['message'] = '¡Las contraseñas no coinciden!';
    }
}
?>

<div class="container">
<?php if(!empty($_SESSION['message'])): ?>
<div class="alert alert-danger alert-container" id="alert">
		<strong><?php echo htmlentities($_SESSION['message']) ?></strong>
        <?php unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>
	<!-- Forgot password form -->
	<div class="forgotpassword-Form">
		<form action="passwrd_reset.php" name="forgotpassword-Form"
			class="form-forgot" method="post">
			<h3 class="cnt">Inserte nueva clave.</h3>
			<hr class="colorgraph">
			<label for="password3">Nueva clave<span class="red">*</span>:
			</label> <input type="password" name="password3" id="password3"
				placeholder="Re-enter password" class="input form-control"
				autocomplete="off" required> <label for="password2">confirma la
				clave<span class="red">*</span>:
			</label> <input type="password" name="password2" id="password2"
				placeholder="Re-enter password" class="input form-control"
				autocomplete="off" required><br> <input type="text" name="email"
				value="<?php  echo htmlentities($_GET['email']); ?>" hidden> <input
				type="text" name="key"
				value="<?php echo htmlentities($_GET['key']); ?>" hidden> <input
				type="submit" name="updatePassword" value="Update password"
				class="btn btn-lg btn-block submit" />

		</form>

	</div>
	<!-- End Forgot password form-->
</div>
