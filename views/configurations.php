<div id="main" class="container">                
    <div class='col-md-12'>       
        <?php
        if ($session->isAdmin()) {

            if (isset($_POST["submitted"]) && $_POST["submitted"] != "") {

                $valueCount = count($_POST["type_name"]);
                for ($i = 0; $i < $valueCount; $i++) {

                    $database->query("UPDATE `config` SET  `value` =  '{$_POST['value'][$i]}'   WHERE `type_name` = '{$_POST['type_name'][$i]}' ");
                }
            }
            ?>
            <h3>Administrar configuracion</h3> 
            <form action='' method='POST'> 
                <?php
                echo "<table border=1 cellpadding=0 cellspacing=0 >";
                echo "<thead>";
                echo "<tr class=title>";
                echo "<th><b>Config / Nombre</b></th>";
                echo "<th><b>Config / Valor</b></th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                $result = $database->query("SELECT * FROM `config`") or trigger_error($database->error);
                while ($row = $result->fetch_array()) {
                    foreach ($row AS $key => $value) {
                        $row[$key] = stripslashes($value);
                    }
                    echo "<tr>";
                    echo "<td valign='top'><input type='text' name='type_name[]' id='type_name' value='" . $row['type_name'] . "' readonly /></td>";
                    echo "<td valign='top'><input type='text' name='value[]' id='value' value='" . $row['value'] . "' /></td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "<tfoot>";
                echo "<tr class=title>";
                echo "<th><b>Config / Nombre</b></th>";
                echo "<th><b>Config / Valor</b></th>";
                echo "</tr>";
                echo "</tfoot>";
                echo "</table>";
                ?>
                <div class='col-md-12'><input class="button" type='submit' value='Editar configuracion' /><input type='hidden' value='1' name='submitted' /></div> 
            </form> 
        <?php
        } else {
            header('Location: index.php');
        }
        ?>
    </div>
</div>