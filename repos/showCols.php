<?php
include_once('../config.php'); //include your db config file
extract($_POST);

ob_start();

$path = basename($_SERVER['REQUEST_URI']);
$file = basename($path);

$fileName = basename($_SERVER['PHP_SELF']);

if ($file == $fileName) {
    header("Location: showCols.php?view=select");
}

function protect($string) {
    $protection = htmlspecialchars(trim($string), ENT_QUOTES);
    return $protection;
}

if (isset($_GET['view'])) {
    $view = protect($_GET['view']);
} else {
    header("Location: showCols.php?view=select");
}
include 'header_top.php';
?>

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
                                    var url = 'showCols.php?view=cols&tbl=' + selecttb;
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
                            $res = $conn->query("SHOW TABLES");
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

            function getVars($tble) {
                global $conn;
                $query = "SELECT * FROM $tble";
                $result = $conn->query($query);
                $checkd = array();
                $fieldCount = mysqli_num_fields($result);
                $r = 0;
                $x = 1;
                while ($fieldCount > $r) {
                    $n = $x++;
                    $info = mysqli_fetch_field($result);
                    $checkd[] = '' . "\n";
                    //$checkd[] .= 'if(isset($_POST["' . $info->name . '"])){' . "\n";
                    $checkd[] .= '$' . $info->name . $n . ' = $_POST["type_' . $n . '"];' . "\n";
                    $checkd[] .= '$' . $info->name . $n . ' = implode(",",$_POST["' . $info->name . $n . '"]);' . "\n";
                    //$checkd[] .= '}' . "\n";

                    $r = $r + 1;
                }
                return implode(" ", $checkd);
            }

            function getValues($tble) {
                global $conn;
                $sql = "SELECT * FROM $tble";
                $result = $conn->query($sql);

                $i = 0;
                $x = 1;
                echo '';
                while ($result->field_count > $i) {
                    $meta = mysqli_fetch_field($result);
                    $val = $x++;
                    $vnames[] = $meta->name;
                    $ins[] = "('" . $tble . "', '" . $meta->name . "', '\$type_" . $val . "', '\$" . $meta->name . $val . "')";
                    $i = $i + 1;
                }
                $cnames = implode(", ", $vnames);
                $insert = "INSERT INTO cols_set " . "\n";
                $insert .= "($cnames)" . "\n";
                $insert .= " VALUES " . "\n";
                $insert .= implode(", \n", $ins);
                return $insert;
            }

            $tmpfile = $tble . '_show.php';
            $myfile = fopen("$tmpfile", "w") or die("Unable to open file!");
            $content = '<?php' . "\n";
            $content .= '//This is temporal file only for add new row' . "\n";
            $content .= "if (isset(\$_POST['submit'])) {";
            $content .= getVars('cols_set') . "\n";
            $content .= '$ins_qry ="' . getValues('cols_set') . '";' . "\n";
            $content .= 'if ($conn->query($ins_qry) === TRUE) {
                        echo "New record created successfully";
                    } else {
                        echo "Error: " . $ins_qry . "<br>" . $conn->error;
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
                                    <th valign="top">Type data
                                    </th>
                                    <th valign="top">List
                                    </th>
                                    <th valign="top">Add
                                    </th>
                                    <th valign="top">Update
                                    </th>
                                    <th valign="top">View
                                    </th>
                                    <th valign="top">Search
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM $tble";

                                $result = $conn->query($sql);

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
                                    echo '<td><select class="custom-select" id="type_' . $val . '" name="type_' . $val . '">
                                            <option value="1">Text field</option>
                                            <option value="2">Text area</option>
                                            <option value="3">Date</option>
                                            <option value="4">Time</option>
                                            <option value="5">Password</option>
                                            <option value="6">Email</option>
                                            <option value="7">Number</option>
                                            <option value="8">Telephone</option>
                                            <option value="9">File/Image</option>
                                            <option value="10">Select</option>
                                      </select></td>' . "\n";
                                    echo '<td><input type="checkbox" id="' . $meta->name . $val . '" name="' . $meta->name . $val . '[]" value="list"></td>' . "\n";
                                    echo '<td><input type="checkbox" id="' . $meta->name . $val . '" name="' . $meta->name . $val . '[]" value="add" ></td>' . "\n";
                                    echo '<td><input type="checkbox" id="' . $meta->name . $val . '" name="' . $meta->name . $val . '[]" value="update"></td>' . "\n";
                                    echo '<td><input type="checkbox" id="' . $meta->name . $val . '" name="' . $meta->name . $val . '[]" value="view"></td>' . "\n";
                                    echo '<td><input type="checkbox" id="' . $meta->name . $val . '" name="' . $meta->name . $val . '[]" value="search"></td>' . "\n";
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
