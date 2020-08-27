<?php

class MyCrypter extends DbConn {

    public function infcheck($myusername, $mypin) {
        $stmt = $this->conn->prepare("SELECT id, username, email, password, mktoken, mkkey, mkhash, mkpin FROM info WHERE username = :myusername AND mkpin = :mypin");
        $stmt->bindParam(':myusername', $myusername);
        $stmt->bindParam(':mypin', $mypin);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        define("SECRET_KEY", $result['mktoken']);
        define("SECRET_IV", $result['mkkey']);
        define('ENCRYPTION_KEY', $result['mkhash']);
        return;
    }

    public function crypt($action, $string) {

        define("ENCRYPT_METHOD", "AES-256-CBC");

        $output = false;
        $encrypt_method = ENCRYPT_METHOD;
        $secret_key = SECRET_KEY;
        $secret_iv = SECRET_IV;
        // $encrypt_key = ENCRYPTION_KEY;

        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

}
