<?php
if (isset($_POST['forgotPassword'])) {
    $email = trim($_POST['email']);

    // Require credentials for DB connection.

    // Check if username or email is already taken.
    $stmt = $conn->prepare("SELECT email FROM uverify WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // Always give this message to prevent data colleting even if the e-mail doesn't exist(The password reset e-mail is only sent if the e-mail exists in database).
    $_SESSION['SuccessMessage'] = 'E-mail ha sido enviado.';

    // If e-mail exists a key for password reset is created into database, after this an e-mail will be sent to user with link and the token key.
    if ($result->num_rows === 1) {

        $forgot_password_key = enfKey();
        $stmt = $conn->prepare("UPDATE uverify SET password_key = ? WHERE email = ?");
        $stmt->bind_param("ss", $forgot_password_key, $email);
        $stmt->execute();
        $stmt->close();

        $_SESSION['SuccessMessage'] = '¡El correo electrónico contiene los pasos a seguir para el reinicio de su contraseña!';

        $message = "Su clave de reinicio es: " . $forgot_password_key . "";
        $to = $email;
        $subject = "Restablecer la contraseña";
        $from = 'contact@labemotion.net'; // Insert the e-mail from where you want to send the emails.
        $body = '<a href="http://farms.labemotion.net/password_reset.php?email=' . $email . '&key=' . $forgot_password_key . '">password_reset.php?email=' . $email . '&key=' . $forgot_password_key . '</a>'; // Replace YOURWEBSITEURL with your own URL for the link to work.
        $headers = "From: " . $from . "\r\n";
        $headers .= "Reply-To: " . $from . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail($to, $subject, $body, $headers);
    }
}

?>

<div class="container">
	<!-- Forgot password form -->
	<div class="forgotpassword-Form">
		<form action="forgot.php" name="forgotpassword-Form"
			class="form-forgot" method="post">
			<h3 class="cnt">¿Olvidaste tu contraseña?</h3>
			<hr class="colorgraph">

			<p class="">Introduce tu correo electrónico. Le enviaremos
				instrucciones por correo electrónico sobre cómo restablecer su
				contraseña.</p>

			<label for="email">Correo electronico<span class="red">*</span>:
			</label> <input type="email" name="email" id="email"
				placeholder="E-mail" class="input form-control" autocomplete="off"
				required autofocus><br>

			<!-- If there is an error it will be shown. --> 
            <?php if(!empty($_SESSION['message'])): ?>
                <div class="alert alert-danger alert-container"
				id="alert">
				<strong><?php echo htmlentities($_SESSION['message']) ?></strong>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            <!-- If e-mail has been sent. -->
            <?php if(!empty($_SESSION['SuccessMessage'])): ?>
                <div class="alert alert-success alert-container"
				id="alert">
				<strong><?php echo htmlentities($_SESSION['SuccessMessage']) ?></strong>
                    <?php unset($_SESSION['SuccessMessage']); ?>
                </div>
            <?php endif; ?>
            
            <input type="submit" name="forgotPassword"
				value="Enviar e-mail" class="btn btn-lg btn-block submit" />

		</form>

	</div>
	<!-- End Forgot password Form-->

</div>
