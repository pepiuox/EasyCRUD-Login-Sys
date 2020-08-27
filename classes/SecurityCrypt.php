<?php

class SecurityCrypt extends DbConn {

    public function randKey($len = 32) {
        return substr(sha1(openssl_random_pseudo_bytes(13)), -$len);
    }

    public function randHash($len = 32) {
        return substr(sha1(openssl_random_pseudo_bytes(21)), -$len);
    }

    public function encKey($len = 32) {
        return substr(sha1(openssl_random_pseudo_bytes(17)), -$len);
    }

    public function crypt($action, $string) {

        $ekey = $this->randHash();
        $eiv = $this->randkey();
        $enck = $this->enckey();

        define("ENCRYPT_METHOD", "AES-256-CBC");
        define("SECRET_KEY", $ekey);
        define("SECRET_IV", $eiv);
        define('ENCRYPTION_KEY', $enck);

        $output = false;
        $encrypt_method = ENCRYPT_METHOD;
        $secret_key = SECRET_KEY;
        $secret_iv = SECRET_IV;

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

    public function secure($newid, $newuser, $newemail, $crypt, $pin) {
        $ekey = SECRET_KEY;
        $eiv = SECRET_IV;
        $enck = ENCRYPTION_KEY;        

        $stmt = $this->conn->prepare("INSERT INTO info (id, username, password, email, mktoken, mkkey, mkhash, mkpin) VALUES (:id, :username, :password, :email, :mktoken, :mkkey, :mkhash, :mkpin)");
        $stmt->bindParam(':id', $newid);
        $stmt->bindParam(':username', $newuser);
        $stmt->bindParam(':email', $newemail);
        $stmt->bindParam(':password', $crypt);
        $stmt->bindParam(':mktoken', $ekey);
        $stmt->bindParam(':mkkey', $eiv);
        $stmt->bindParam(':mkhash', $enck);
        $stmt->bindParam(':mkpin', $pin);
        $stmt->execute();
        unset($stmt);
    }

}
