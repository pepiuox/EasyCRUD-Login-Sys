<?php
/*
define("ENCRYPT_METHOD", "AES-256-CBC");
                            define("SECRET_KEY", $ekey);
                            define("SECRET_IV", $eiv);
                            define('ENCRYPTION_KEY', $enck);

                            function encrypter($action, $string) {
                                $output = false;
                                $key = hash("sha256", SECRET_KEY);
                                $iv = substr(hash("sha256", SECRET_IV), 0, 16);

                                if ($action == "encrypt") {
                                    $output = openssl_encrypt($string, ENCRYPT_METHOD, $key, 0, $iv);
                                    $output = base64_encode($output);
                                } else if ($action == "decrypt") {
                                    $output = base64_decode($string);
                                    $output = openssl_decrypt($output, ENCRYPT_METHOD, $key, 0, $iv);
                                }
                                return $output;
                            }

                            $pass = encrypter('encrypt', $password);
                            */