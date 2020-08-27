<?php
/*
class CrypterKey
{

    function sign($message, $key)
    {
        return hash_hmac('sha256', $message, $key) . $message;
    }

    function verify($bundle, $key)
    {
        return hash_equals(hash_hmac('sha256', mb_substr($bundle, 64, null, '8bit'), $key), mb_substr($bundle, 0, 64, '8bit'));
    }

    function getKey($password, $keysize = 16)
    {
        return hash_pbkdf2('sha256', $password, 'some_token', 100000, $keysize, true);
    }

    function encrypt($message, $password)
    {
        $iv = random_bytes(16);
        $key = getKey($password);
        $result = sign(openssl_encrypt($message, 'aes-256-ctr', $key, OPENSSL_RAW_DATA, $iv), $key);
        return bin2hex($iv) . bin2hex($result);
    }

    function decrypt($hash, $password)
    {
        $iv = hex2bin(substr($hash, 0, 32));
        $data = hex2bin(substr($hash, 32));
        $key = getKey($password);
        if (! verify($data, $key)) {
            return null;
        }
        return openssl_decrypt(mb_substr($data, 64, null, '8bit'), 'aes-256-ctr', $key, OPENSSL_RAW_DATA, $iv);
    }
}

define ("SECRETKEY", "mysecretkey1234");

// ENCRYPT THE PASSWORD USING THE OPENSSL_ENCRYPT FUNCTION & YOUR SECRET KEY
function addUser($name, $email, $password){
    $sql = "INSERT INTO `users` (`name`, `email`, `password`) VALUES (?,?,?)";
    $this->stmt = $this->pdo->prepare($sql);
    $hash = openssl_encrypt($password, "AES-128-ECB", SECRETKEY);
    return $this->stmt->execute([$name, $email, $hash]);
}
function login($email, $password){
    $sql = "SELECT * FROM `users` WHERE `email`=?";
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute([$email]);
    $user = $this->stmt->fetchAll();
    $plain = openssl_decrypt($user['password'], "AES-128-ECB", SECRETKEY);
    return $password==$plain;
}

// TURN ADDRESS INTO JSON STRING & ENCRYPT
$addresses = [
    "Test Street 1234 Somewhere 2345",
    "Doge Street 1234 Cate Country 2345"
];
$cipher = openssl_encrypt(json_encode($addresses), "AES-128-ECB", SECRETKEY);
$sql = "INSERT INTO `addresses` (`user_id`, `data`) VALUES (?,?)";
$this->stmt = $this->pdo->prepare($sql);
$this->stmt->execute([$id, $cipher]);

// DECRYPT & JSON DECODE
$sql = "SELECT * FROM `address` WHERE `id`=?";
$this->stmt = $this->pdo->prepare($sql);
$this->stmt->execute([$id]);
$address = $this->stmt->fetchAll();
$address = json_decode(openssl_decrypt($address, "AES-128-ECB", SECRETKEY));


$valid_user = login($_POST['email'], $_POST['password']);

$pass = addUser("Jane Doe", "jane@doe.com", "password456");
$string_to_encrypt = 'John Smith';
$password = 'password';
$encrypted_string = encrypt($string_to_encrypt, $password);
$decrypted_string = decrypt($encrypted_string, $password);
*/