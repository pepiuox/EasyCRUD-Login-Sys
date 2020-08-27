<?php
//This is temporal file only for add new row
if (isset($_POST['submit'])) {
 $type_1 = $_POST["type_1"];
 $idCnt1 = implode(",",$_POST["idCnt1"]);
 
 $type_2 = $_POST["type_2"];
 $cuentas2 = implode(",",$_POST["cuentas2"]);

$ins_qry ="INSERT INTO cols_set 
(table_name,col_name,col_type,col_set)
 VALUES 
('cuentas', 'idCnt', '$type_1', '$idCnt1'), 
('cuentas', 'cuentas', '$type_2', '$cuentas2')";
if ($conn->query($ins_qry) === TRUE) {
                        echo "New record created successfully";
                    } else {
                        echo "Error: " . $ins_qry . "<br>" . $conn->error;
                    }}?>