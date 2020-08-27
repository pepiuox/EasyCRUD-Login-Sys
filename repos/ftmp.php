<?php
//This is temporal file only for add new row
if(isset($_POST['addtable'])){$query="INSERT INTO table_queries (name_table, col_name) VALUES('almacen', 'granja_id'), ('almacen', 'producto'), ('almacen', 'cantidad'), ('almacen', 'stock'), ('almacen', 'precio_unitario'), ('almacen', 'destino_ubicacion'), ('almacen', 'observaciones'), ('almacen', 'imagen')";
if($conn->query($query) === TRUE){
echo "Record added successfully";
} else{
                                         echo "Error added record: " . $conn->error;
                                         }
                                         }
?> 
