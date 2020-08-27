<?php
//This is temporal file only for add new row
if (isset($_POST['submit'])) {
 $id1 = $_POST["type_1"];
 $id1 = implode(",",$_POST["id1"]);
 
 $table_name2 = $_POST["type_2"];
 $table_name2 = implode(",",$_POST["table_name2"]);
 
 $col_name3 = $_POST["type_3"];
 $col_name3 = implode(",",$_POST["col_name3"]);
 
 $type_input4 = $_POST["type_4"];
 $type_input4 = implode(",",$_POST["type_input4"]);
 
 $list_page5 = $_POST["type_5"];
 $list_page5 = implode(",",$_POST["list_page5"]);
 
 $add_page6 = $_POST["type_6"];
 $add_page6 = implode(",",$_POST["add_page6"]);
 
 $update_page7 = $_POST["type_7"];
 $update_page7 = implode(",",$_POST["update_page7"]);
 
 $view_page8 = $_POST["type_8"];
 $view_page8 = implode(",",$_POST["view_page8"]);
 
 $delete_page9 = $_POST["type_9"];
 $delete_page9 = implode(",",$_POST["delete_page9"]);
 
 $search_text10 = $_POST["type_10"];
 $search_text10 = implode(",",$_POST["search_text10"]);
 
 $col_set11 = $_POST["type_11"];
 $col_set11 = implode(",",$_POST["col_set11"]);

$ins_qry ="INSERT INTO cols_set 
(id, table_name, col_name, type_input, list_page, add_page, update_page, view_page, delete_page, search_text, col_set)
 VALUES 
('cols_set', 'id', '$type_1', '$id1'), 
('cols_set', 'table_name', '$type_2', '$table_name2'), 
('cols_set', 'col_name', '$type_3', '$col_name3'), 
('cols_set', 'type_input', '$type_4', '$type_input4'), 
('cols_set', 'list_page', '$type_5', '$list_page5'), 
('cols_set', 'add_page', '$type_6', '$add_page6'), 
('cols_set', 'update_page', '$type_7', '$update_page7'), 
('cols_set', 'view_page', '$type_8', '$view_page8'), 
('cols_set', 'delete_page', '$type_9', '$delete_page9'), 
('cols_set', 'search_text', '$type_10', '$search_text10'), 
('cols_set', 'col_set', '$type_11', '$col_set11')";
if ($conn->query($ins_qry) === TRUE) {
                        echo "New record created successfully";
                    } else {
                        echo "Error: " . $ins_qry . "<br>" . $conn->error;
                    }}?>