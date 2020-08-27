<?php
include_once('db.php'); //include your db config file
extract($_POST);

ob_start();

$path = basename($_SERVER['REQUEST_URI']);
$file = basename($path);

$fileName = basename($_SERVER['PHP_SELF']);

if ($file == $fileName) {
    header("Location: viewCols.php?view=select");
}

function protect($string) {
    $protection = htmlspecialchars(trim($string), ENT_QUOTES);
    return $protection;
}

if (isset($_GET['view'])) {
    $view = protect($_GET['view']);
} else {
    header("Location: viewCols.php?view=select");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" name="viewport" content="width-device=width, initial-scale=1" />
        <link rel="stylesheet" type="text/css" href="../css/styles.css" />
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <title>PHP CRUD</title>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    </head>
    <body>
        <?php
        include 'navbar.php';
        ?>
        <?php
        if ($view === "select") {
            ?>
            <div class="container">
                <div class = "row">	
                    <div class="col-md-6">
                        <h3 id="fttl">Form </h3>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <script>
                                $(function () {
                                    $("#selecttb").change(function () {
                                        var selecttb = $(this).val();
                                        //var path = $(location).attr('href');                        
                                        var url = 'viewCols.php?view=cols&tbl=' + selecttb;
                                        $('#fttl').text('Form ' + selecttb);
                                        window.location.replace(url);
                                    });
                                });
                            </script>
                            <label class="control-label" for="selecttb">Select Table</label>
                            <select id="selecttb" name="selecttb" class="form-control">
                                <option value="">Select Table</option>
                                <?php
                                /* Get table names */
                                $tableList = array();
                                $res = $con->query("SHOW TABLES");
                                while ($row = $res->fetch_array()) {
                                    $tableList[] = $row[0];
                                }
                                foreach ($tableList as $tname) {
                                    $remp = str_replace("_", " ", $tname);
                                    echo '<option value="' . $tname . '">' . ucfirst($remp) . '</option>' . "\n";
                                }
                                ?>
                            </select>                               
                        </div>
                    </div>
                </div>
            </div>
            <?php
            /* View data in the selected table */
        } elseif ($view == "cols") {

            if (!empty($_GET['tbl'])) {
                $tble = protect($_GET['tbl']);

                function chckBox($val) {
                    $chboxs = array('list' . $val, 'add' . $val, 'update' . $val, 'view' . $val, 'delete' . $val, 'search' . $val);
                    foreach ($chboxs as $chbox) {

                        $remp[] = 'if(isset($_POST["' . $chbox . '"])){' . "\n";
                        $remp[] .= '$' . $chbox . ' = $_POST["' . $chbox . '"];' . "\n";
                        $remp[] .= '}else{' . "\n";
                        $remp[] .= '$' . $chbox . ' = " ";' . "\n";
                        $remp[] .= '}' . "\n";
                    }
                    return implode(" ", $remp);
                }

                function gpost($tble) {
                    global $con;
                    $sqls = "SELECT * FROM $tble";
                    $results = $con->query($sqls);
                    $n = 0;
                    $i = 1;

                    //while ($n < mysqli_num_fields($results)) {
                    while ($results->field_count > $n) {
                        $val = $i++;
                        //$meta = mysqli_fetch_field($results);
                        //$remp[] = '$' . $meta->name . ' = $_POST["' . $meta->name . '"];' . "\n";
                        $remp[] = '$type' . $val . ' = $_POST["type' . $val . '"];' . "\n";
                        $remp[] .= chckBox($val) . "\n";
                        $n = $n + 1;
                    }
                    return implode(" ", $remp);
                }

                function getValues($tble) {
                    global $con;
                    $sql = "SELECT * FROM $tble";
                    $result = $con->query($sql);

                    $i = 0;
                    $x = 1;
                    echo '';
                    while ($result->field_count > $i) {
                        $meta = mysqli_fetch_field($result);
                        $val = $x++;
                        $ins[] = "('" . $tble . "', '" .$meta->name. "', '\$type" . $val . "', '\$list" . $val . "', '\$add" . $val . "', '\$update" . $val . "', '\$view" . $val . "', '\$delete" . $val . "', '\$search" . $val . "')";
                        $i = $i + 1;
                    }
                    $insert = "INSERT INTO cols_set " . "\n";
                    $insert .= "(table_name,col_name,type_input,list_page,add_page,update_page,view_page,delete_page,search_text)" . "\n";
                    $insert .= " VALUES " . "\n";
                    $insert .= implode(", \n", $ins);
                    return $insert;
                }

                $tmpfile = $tble . '-view.php';
                $myfile = fopen("$tmpfile", "w") or die("Unable to open file!");
                $content = '<?php' . "\n";
                $content .= '//This is temporal file only for add new row' . "\n";
                $content .= "if (isset(\$_POST['submit'])) {" . "\n";
                $content .= gpost($tble) . "\n";
                $content .= "\n";
                
                $content .= '$ins_qry ="' . getValues($tble) . '";' . "\n";
                $content .= 'if ($con->query($ins_qry) === TRUE) {
                        echo "New record created successfully";
                    } else {
                        echo "Error: " . $ins_qry . "<br>" . $con->error;
                    }';
                $content .= '}';
                $content .= '?>';

                fwrite($myfile, $content);
                fclose($myfile);

                include ($tmpfile);
                ?>
                <div class="container">

                    <div id="test">

                    </div>
                    <form class="form-horizontal" method="post" action="">
                        <div class="form-group">
                            <h3 class="col-md-4 control-label" for="checkboxes">Configure Columns:</h3>
                            <table class="table">
                                <thead>
                                    <tr>                                       
                                        <th valign="top">Name
                                        </th>
                                        <th valign="top">Type
                                        </th>
                                        <th valign="top">List
                                        </th>
                                        <th valign="top">Add
                                        </th>
                                        <th valign="top">Update
                                        </th>
                                        <th valign="top">View
                                        </th>
                                        <th valign="top">Delete
                                        </th>
                                        <th valign="top">Search
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
        <?php
        $sql = "SELECT * FROM $tble";

        $result = $con->query($sql);

        $i = 0;
        $x = 1;
        /*
          while ($row = mysqli_fetch_row($result)) {
          $tableNames[$arrayCount] = $row[0];
          $arrayCount++; //only do this to make sure it starts at index 0
          }
         */
        while ($result->field_count > $i) {
            $meta = mysqli_fetch_field($result);
            $remp = str_replace("_", " ", $meta->name);
            $val = $x++;
            echo '<tr id="row-' . $val . '">' . "\n";
            echo '<td><b>' . ucfirst($remp) . '</b></td>' . "\n";
            echo '<td><select class="custom-select" id="type' . $val . '" name="type' . $val . '">' . "\n";
            echo '<option value="1">Text field</option>
                                            <option value="2">Text area</option>
                                            <option value="3">Date</option>
                                            <option value="4">Time</option>
                                            <option value="5">Password</option>
                                            <option value="6">Email</option>
                                            <option value="7">Number</option>
                                            <option value="8">Telephone</option>
                                            <option value="9">File/Image</option>
                                            <option value="10">Select</option>' . "\n";
            echo '</select></td>' . "\n";
            echo '<td><input type="checkbox" id="list' . $val . '" name="list' . $val . '" value="list"></td>' . "\n";
            echo '<td><input type="checkbox" id="add' . $val . '" name="add' . $val . '" value="add" ></td>' . "\n";
            echo '<td><input type="checkbox" id="update' . $val . '" name="update' . $val . '" value="update"></td>' . "\n";
            echo '<td><input type="checkbox" id="view' . $val . '" name="view' . $val . '" value="view"></td>' . "\n";
            echo '<td><input type="checkbox" id="delete' . $val . '" name="delete' . $val . '" value="delete"></td>' . "\n";
            echo '<td><input type="checkbox" id="search' . $val . '" name="search' . $val . '" value="search"></td>' . "\n";
            echo '</tr>' . "\n";
            $i = $i + 1;
        }
        ?>
                                </tbody>
                            </table>                                                          
                            <div class="form-group">        
                                <button type="submit" id="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Save Cols settings</button>
                            </div>

                        </div>
                    </form>

                </div>
        <?php
    }
}
?>
    </body>
</html>
