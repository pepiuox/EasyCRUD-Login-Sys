<?php

//$selecttb = $_POST['tb_links'];

class MyCRUD {

    public function getID($tble) {
        global $conn;
        $result = $conn->query("SELECT * from $tble");
        $result->field_seek(0);
        $finfo = $result->fetch_field();
        $ncol = $finfo->name;
        return $ncol;
    }

    public function listData($tble) {

        global $conn;
        if (!$conn) {
            die('Error: Could not connect: ' . mysqli_error());
        }
        $sqlq = "SELECT * FROM table_queries WHERE name_table='$tble' AND type IS NOT NULL";
        $resultq = $conn->query($sqlq);
        $resv = mysqli_num_rows($resultq);

        $r = 0;
        // start vars
        if ($r < $resv) {

            $qers = array();
            $ttl = array();
            $ctl = array();
            $fcols = array();

            while ($row = $resultq->fetch_array()) {
                $c_nm = $row['col_name'];
                $c_jo = $row['joins'];
                $c_tb = $row['j_table'];
                $c_id = $row['j_id'];
                $c_vl = $row['j_value'];
                $ttl[] = '$meta->name != "' . $c_id . '" && $meta->name != "' . $c_vl . '"';
                $ctl[] = '$name != "' . $c_id . '" && $name != "' . $c_vl . '"';
                $fcols[] = "if(\$name == '{$c_nm}'){echo '<td>'.\$rw['{$c_vl}'].'</td>';}" . "\n";
                $qers[] = $c_jo . " (SELECT " . $c_id . ', ' . $c_vl . ' FROM ' . $c_tb . ') ' . $c_tb . ' ON ' . $tble . '.' . $c_nm . '=' . $c_tb . '.' . $c_id;
            }
            $vtl = implode(" && ", $ttl);
            $valr = implode(" ", $qers);
            $fcol = implode(" else", $fcols);
            $ctls = implode(" && ", $ctl);
        }

        // end vars

        $start = 1;
        $range = 10;
        $startpage = 1;

        if (isset($_GET['page']) && !empty($_GET['page'])) {
            $pg = protect($_GET['page']);
            $pg = filter_var($pg, FILTER_SANITIZE_NUMBER_INT);
            $page = $pg - $start;
            $pages = "OFFSET " . ($range * $page);
            if ($r < $resv) {
                $sel = "SELECT * FROM {$tble} {$valr} LIMIT {$range}";
                $select = "SELECT * FROM {$tble} {$valr} LIMIT {$range} {$pages}";
            } else {
                $sel = "SELECT * FROM {$tble} LIMIT {$range}";
                $select = "SELECT * FROM {$tble} LIMIT {$range} {$pages}";
            }
        } else {
            $pg = 1;
            $page = 0;
            if ($r < $resv) {
                $sel = "SELECT * FROM {$tble} {$valr} LIMIT {$range}";
                $select = "SELECT * FROM {$tble} {$valr} LIMIT {$range}";
            } else {
                $sel = "SELECT * FROM {$tble} LIMIT {$range}";
                $select = "SELECT * FROM {$tble} LIMIT {$range}";
            }
        }
        $numq = "SELECT * FROM " . $tble;
        $endpage = '';
        if ($nres = $conn->query($numq)) {
            $rowcq = $nres->num_rows();
            $endpage = ceil($rowcq / $range);
        }


        $res = $conn->query($sel);
        $result = $conn->query($select);

        if (!$result) {
            $message = 'ERROR:' . mysqli_error();
            return $message;
        } else {

            $i = 0;
            if ($i < $resv) {
                $rvfile = 'ftmp.php';
                $mfile = fopen("$rvfile", "w") or die("Unable to open file!");
                $content = '<?php' . "\n";
                $content .= "if ({$vtl}) {" . "\n";
                $content .= "echo '<th>' . ucfirst(\$remp) . '</th>';" . "\n";
                $content .= "}" . "\n";
                $content .= "?> \n";

                fwrite($mfile, $content);
                fclose($mfile);
            }

            // start form
            // start table head
            $names = array();
            echo '<form method="POST">' . "\n";
            echo '<table class="table table-bordered">' . "\n";
            echo '<thead class="bg-info">' . "\n";
            echo '<tr>' . "\n";

            if ($i < mysqli_num_fields($result)) {
                while ($meta = mysqli_fetch_field($result)) {
                    $names[] = $meta->name;
                    $remp = str_replace("_", " ", $meta->name);
                    if ($i < $resv) {
                        include 'ftmp.php';
                    } else {
                        echo '<th>' . ucfirst(str_replace(' ', '_', substr($remp, 0, 3))). '</th>';
                    }
                }
            }

            echo '<th><a id="addrow" name="addrow" class="btn btn-primary" href="forms.php?w=add&tbl=' . $tble . '">Add new</a></th>' . "\n";
            echo '</tr>' . "\n";
            echo '</thead>' . "\n";
            echo '<tbody>' . "\n";
            // end table head
            //start body table
            while ($row = mysqli_fetch_row($res)) {
                echo '<tr>' . "\n";
                $rw = mysqli_fetch_array($result);
                $count = count($row);

                $y = 0;
                if ($y < $count) {
                    $c_row = current($row);
                    foreach ($names as $key => $name) {
                        if ($y < $resv) {

                            $vrfile = 'vtmp.php';
                            $vfile = fopen("$vrfile", "w") or die("Unable to open file!");
                            $varcont = '<?php' . "\n";
                            $varcont .= "if (\$key == 0) {";
                            $varcont .= "echo '<td id=\"'.\$rw[0].'\">'.\$rw[0].'</td>';" . "\n";
                            $varcont .= "}else";
                            $varcont .= $fcol;
                            $varcont .= "elseif({$ctls}){ echo '<td>' . \$rw[\$name] . '</td>';}" . "\n";
                            $varcont .= "?> \n";

                            fwrite($vfile, $varcont);
                            fclose($vfile);

                            include 'vtmp.php';
                        } else {
                            if ($key == 0) {
                                echo '<td id="' . $rw[$key] . '">' . $rw[$key] . '</td>' . "\n";
                            } else {
                                echo '<td>' . $rw[$name] . '</td>' . "\n";
                            }
                        }
                    }
                    next($row);
                    $y++;
                }

                $i_row = $row[0];
                echo '<td><!--Button -->
                <a id="editrow" name="editrow" class="btn btn-success" href="forms.php?w=edit&tbl=' . $tble . '&id=' . $i_row . '">Edit</a>
                <a id="deleterow" name="deleterow" class="btn btn-danger" href="forms.php?w=delete&tbl=' . $tble . '&id=' . $i_row . '">Delete</a>
                </td>';

                echo '</tr>' . "\n";
                $i++;
            }
            echo '</tbody>' . "\n";
            echo '</table>' . "\n";
            //end body table
            // end         
            $url = 'forms.php?w=list&tbl=' . $tble;

            if ($i < $rowcq) {
                echo '<nav aria-label="navigation">';
                echo '<ul class="pagination justify-content-center">' . "\n";

                echo '<li class="page-item';
                if ($page < $startpage) {
                    echo ' disabled';
                }
                echo '"><a class="page-link" href="' . $url . '&page=' . $startpage . '">First</a></li>' . "\n";

                echo '<li class="page-item ';
                if ($page < 1) {
                    echo 'disabled';
                }
                echo '"><a class="page-link" href="';
                if ($page <= 1) {
                    echo '#';
                } else {
                    echo $url . "&page=" . $page;
                }
                echo '">Prev</a></li>' . "\n";
                //
                for ($x = 1; $x <= $range; $x++) {
                    if ($pg < $endpage) {
                        echo '<li class="page-item ';
                        if ($pg == ($page + 1)) {
                            echo 'disabled';
                        }
                        echo '"><a class="page-link" href="';
                        if ($endpage < $page) {
                            echo '#';
                        } else {
                            echo $url . "&page=" . $pg;
                        }
                        echo '">' . $pg++ . '</a></li>' . "\n";
                    } elseif ($pg > $endpage) {
                        continue;
                    } else {
                        echo '<li class="page-item ';
                        if ($pg == ($page + 1)) {
                            echo 'disabled';
                        }
                        echo '"><a class="page-link" href="';
                        if ($endpage < $page) {
                            echo '#';
                        } else {
                            echo $url . "&page=" . $pg;
                        }
                        echo '">' . $pg++ . '</a></li>' . "\n";
                    }
                }
                //
                echo '<li class="page-item ';
                if ($endpage == $_GET['page']) {
                    echo 'disabled';
                }
                echo '"><a class="page-link" href="';
                if ($pg < $endpage) {
                    echo '#';
                } else {
                    echo $url . "&page=" . ($pg + 1);
                }
                echo '">Next</a></li>' . "\n";

                echo '<li class="page-item';
                if ($endpage == $_GET['page']) {
                    echo ' disabled';
                }
                echo '"><a class="page-link" href="' . $url . '&page=' . $endpage . '">Last</a></li>' . "\n";

                echo '</ul>' . "\n";
                echo '</nav>' . "\n";
            }
        }

        mysqli_close($con);
    }

//addrow
    function addData($tble, $ncol) {
        global $conn;
        if (!$conn) {
            die('Error: Could not connect: ' . mysqli_error());
        }
//
        $sqlq = "SELECT * FROM table_queries WHERE name_table='$tble' AND type IS NOT NULL";
        $resultq = $conn->query($sqlq);
        $rowcq = mysqli_num_rows($resultq);
        $r = 0;

        if ($r < $rowcq) {
            $nif = array();
            $qers = array();
            $ctl = array();
            while ($rqu = $resultq->fetch_array()) {

                $c_nm = $rqu['col_name'];
                $c_jo = $rqu['joins'];
                $c_tb = $rqu['j_table'];
                $c_id = $rqu['j_id'];
                $c_vl = $rqu['j_value'];
                $ctl[] = '$finfo->name != "' . $c_id . '" && $finfo->name != "' . $c_vl . '"';
                $qers[] = $c_jo . " (SELECT " . $c_id . ', ' . $c_vl . ' FROM ' . $c_tb . ') ' . $c_tb . ' ON ' . $tble . '.' . $c_nm . '=' . $c_tb . '.' . $c_id;
                $nif[] = "if (\$finfo->name == '{$c_nm}') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <select type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\" >';

                    \$qres = \$conn->query(\"SELECT * FROM  {$c_tb}\");
                    while (\$rqj = \$qres->fetch_array()) {
                        echo '<option value=\"' . \$rqj['{$c_id}'] . '\">' . \$rqj['{$c_vl}'] . '</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                }";
            }

            $valr = implode(" ", $qers);
            $nifs = implode("else", $nif);
            $ctls = implode(" && ", $ctl);

            $sql = "SELECT * FROM $tble $valr";
        } else {
            $sql = "SELECT * FROM $tble";
        }
//
        $qresult = $conn->query($sql);
        echo '<form method="post" role="form" id="add_' . $tble . '">' . "\n";
        while ($finfo = $qresult->fetch_field()) {
            $remp = str_replace("_", " ", $finfo->name);

            $typs = "if (\$finfo->type == '1' || \$finfo->type == '2' || \$finfo->type == '3' || \$finfo->type == '4' || \$finfo->type == '8' || \$finfo->type == '16') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <input type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\">
    </div>' . \"\n\";
                } elseif (\$finfo->type == '5' || \$finfo->type == '246') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <textarea type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\"></textarea>
    </div>' . \"\n\";
                } elseif (\$finfo->type == '7' || \$finfo->type == '10' || \$finfo->type == '11' || \$finfo->type == '12' || \$finfo->type == '13') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <input type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\">
    </div>' . \"\n\";
                } elseif (\$finfo->type == '252') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <textarea type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\"></textarea>
    </div>' . \"\n\";
                } elseif (\$finfo->type == '253') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <input type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\">
    </div>' . \"\n\";
                } elseif (\$finfo->type == '254') {
                    //----------------------
                    \$isql = \"SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '\" . \$tble . \"' AND COLUMN_NAME = '\" . \$finfo->name . \"'\";

                    \$iresult = \$conn->query(\$isql);
                    \$row = mysqli_fetch_array(\$iresult);
                    \$enum_list = explode(\",\", str_replace(\"'\", \"\", substr(\$row['COLUMN_TYPE'], 5, (strlen(\$row['COLUMN_TYPE']) - 6))));
                    \$default_value = '';
                    //
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <select type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\" >' . \"\n\";

                    \$options = \$enum_list;
                    foreach (\$options as \$option) {
                        \$soption = '<option value=\"' . \$option . '\"';
                        \$soption .= (\$default_value == \$option) ? ' SELECTED' : '';
                        \$soption .= '>' . \$option . '</option>' . \"\n\";
                        echo \$soption;
                    }
                    echo '</select>' . \"\n\";
                    echo '</div>' . \"\n\";

                    //----------------------
                }";

            if ($finfo->name != $ncol) {
                if ($r < $rowcq) {
                    $rvfile = 'ftmp.php';
                    $mfile = fopen("$rvfile", "w") or die("Unable to open file!");
                    $content = '<?php' . "\n";
                    $content .= "if({$ctls}){" . "\n";
                    $content .= $nifs;
                    $content .= "else" . $typs;
                    $content .= "}";
                    $content .= "?> \n";

                    fwrite($mfile, $content);
                    fclose($mfile);
                    include 'ftmp.php';
                } else {
                    $rvfile = 'ftmp.php';
                    $mfile = fopen("$rvfile", "w") or die("Unable to open file!");
                    $content = '<?php' . "\n";
                    $content .= $typs;
                    $content .= "?> \n";

                    fwrite($mfile, $content);
                    fclose($mfile);
                    include 'ftmp.php';
                }
            }
        }
        echo '<div class="form-group">        
        <button type="submit" id="addrow" name="addrow" class="btn btn-primary"><span class="glyphicon glyphicon-plus" onclick="dVals();"></span> Add</button>
    </div>' . "\n";
        echo '</form>' . "\n";
    }

//
//editrow
    function editData($tble, $ncol, $id) {
        global $conn;
        if (!$conn) {
            die('Error: Could not connect: ' . mysqli_error());
        }
//
        $sqlq = "SELECT * FROM table_queries WHERE name_table='$tble' AND type IS NOT NULL";
        $resultq = $conn->query($sqlq);
        $rowcq = mysqli_num_rows($resultq);
        $r = 0;

        if ($r < $rowcq) {
            $nif = array();
            $qers = array();
            $ctl = array();
            while ($rqu = $resultq->fetch_array()) {

                $c_nm = $rqu['col_name'];
                $c_jo = $rqu['joins'];
                $c_tb = $rqu['j_table'];
                $c_id = $rqu['j_id'];
                $c_vl = $rqu['j_value'];
                $ctl[] = '$finfo->name != "' . $c_id . '" && $finfo->name != "' . $c_vl . '"';
                $qers[] = $c_jo . " (SELECT " . $c_id . ', ' . $c_vl . ' FROM ' . $c_tb . ') ' . $c_tb . ' ON ' . $tble . '.' . $c_nm . '=' . $c_tb . '.' . $c_id;
                $nif[] = "if(\$finfo->name == '{$c_nm}'){
                echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">'.ucfirst(\$remp) . ':</label>
        <select type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\" >';

                        \$qres = \$conn->query(\"SELECT * FROM {$c_tb}\");
                            
                        while (\$rqj = \$qres->fetch_array()) {
                            if (\$cdta == \$rqj['{$c_id}']) {
                                echo '<option value=\"' . \$rqj['{$c_id}'] . '\" selected=\"selected\">' . \$rqj['{$c_vl}'] . '</option>';
                            } else {
                                echo '<option value=\"' . \$rqj['{$c_id}'] . '\">' . \$rqj['{$c_vl}'] . '</option>';
                            }
                        }
                        
                        echo '</select>';
                        echo '</div>';
                        }";
            }
            $valr = implode(" ", $qers);
            $nifs = implode("else", $nif);
            $ctls = implode(" && ", $ctl);

            $query = "select * from $tble $valr where $ncol = '$id' ";
        } else {
            $query = "select * from $tble where $ncol = '$id' ";
        }
//

        $qresult = $conn->query($query);
        echo '<form role="form" id="add_' . $tble . '" method="POST">' . "\n";
        $row = $qresult->fetch_array();
        while ($finfo = $qresult->fetch_field()) {
            $remp = str_replace("_", " ", $finfo->name);
            $cdta = $row[$finfo->name];
            $typs = "if (\$finfo->type == '1' || \$finfo->type == '2' || \$finfo->type == '3' || \$finfo->type == '4' || \$finfo->type == '8' || \$finfo->type == '16') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <input type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\" value=\"' . \$cdta . '\">
    </div>' . \"\n\";
                } elseif (\$finfo->type == '5' || \$finfo->type == '246') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <textarea type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\">' . \$cdta . '</textarea>
    </div>' . \"\n\";
                } elseif (\$finfo->type == '7' || \$finfo->type == '10' || \$finfo->type == '11' || \$finfo->type == '12' || \$finfo->type == '13') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <input type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\" value=\"' . \$cdta . '\">
    </div>' . \"\n\";
                } elseif (\$finfo->type == '252') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <textarea type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\">' . \$cdta . '</textarea>
    </div>' . \"\n\";
                } elseif (\$finfo->type == '253') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <input type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\" value=\"' . \$cdta . '\">
    </div>' . \"\n\";
                } elseif (\$finfo->type == '254') {
                    echo '<div class=\"form-group\">
        <label for=\"' . \$finfo->name . '\">' . ucfirst(\$remp) . ':</label>
        <select type=\"text\" class=\"form-control\" id=\"' . \$finfo->name . '\" name=\"' . \$finfo->name . '\" value=\"' . \$cdta . '\">
            <option value=\"0\">0</option>
        </select>
    </div>' . \"\n\";
}";

            if ($finfo->name != $ncol) {
                if ($r < $rowcq) {
                    $rvfile = 'ftmp.php';
                    $mfile = fopen("$rvfile", "w") or die("Unable to open file!");
                    $content = '<?php' . "\n";
                    $content .= "if({$ctls}){" . "\n";
                    $content .= $nifs;
                    $content .= "else" . $typs;
                    $content .= "}" . "\n";
                    $content .= "?> \n";

                    fwrite($mfile, $content);
                    fclose($mfile);
                    include 'ftmp.php';
                } else {
                    $rvfile = 'ftmp.php';
                    $mfile = fopen("$rvfile", "w") or die("Unable to open file!");
                    $content = '<?php' . "\n";
                    $content .= $typs;
                    $content .= "?> \n";

                    fwrite($mfile, $content);
                    fclose($mfile);
                    include 'ftmp.php';
                }
            }
        }
        echo '<div class="form-group">
        <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class = "glyphicon glyphicon-plus"></span> Edit</button>
    </div>' . "\n";
        echo '</form>' . "\n";
    }

//deleterow
    function deleteData($tble, $ncol, $id) {
        global $conn;
        if (!$conn) {
            die('Error: Could not connect: ' . mysqli_error());
        }
        $query = "select * from $tble where $ncol = '$id' ";
        $qresult = $conn->query($query);
        echo '<form role="form" id="delete_' . $tble . '" method="POST">' . "\n";
        $row = mysqli_fetch_array($qresult, MYSQLI_ASSOC);
        while ($finfo = $qresult->fetch_field()) {
            $cdta = $row[$finfo->name];
            if ($finfo->name == $ncol) {
                continue;
            } else {
                $remp = str_replace("_", " ", $finfo->name);
                echo '<div class="form-group">
        <label for="' . $finfo->name . '">' . ucfirst($remp) . ':</label>
        <input type="text" class="form-control" id="' . $finfo->name . '" name="' . $finfo->name . '" value="' . $cdta . '" readonly>
    </div>' . "\n";
            }
        }
        echo '<div class="form-group">
        <button type = "submit" id="deleterow" name="deleterow" class="btn btn-primary"><span class = "glyphicon glyphicon-plus"></span> Delete</button>
    </div>' . "\n";
        echo '</form>' . "\n";
    }

//adduery
    function addQuery($tble, $ncol) {
        global $conn;
        if (!$conn) {
            die('Error: Could not connect: ' . mysqli_error());
        }

        $sql = "SELECT * FROM " . $tble;
        $qresult = $conn->query($sql);
        echo '<form method="post" role="form" id="query_' . $tble . '">' . "\n";
        while ($finfo = $qresult->fetch_field()) {
            if ($finfo->name == $ncol) {
                continue;
            } else {
                $remp = str_replace("_", " ", $finfo->name);

                echo '<div class="form-group">
        <label for="' . $finfo->name . '">' . ucfirst($remp) . ':</label>
        <textarea type="text" class="form-control" id="' . $finfo->name . '" name="' . $finfo->name . '"></textarea>
    </div>' . "\n";
            }
        }
        echo '<div class="form-group">
        <button type = "submit" id="addqueries" name="addqueries" class="btn btn-primary"><span class = "glyphicon glyphicon-plus"></span> Add queries</button>
    </div>' . "\n";
        echo '</form>' . "\n";
    }

//addpost
    function addPost($tble, $ncol) {
        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);
        $r = 0;
        $varnames = array();
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $varnames[] = '$' . $info->name . ' = $_POST["' . $info->name . '"]; ' . "\r\n";
            }
            $r = $r + 1;
//return $varnames;
        }
        return implode("", $varnames);
    }

//addttl
    function addTtl($tble, $ncol) {
        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);
        $checkd = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd[] = '`' . $info->name . '`';
            }

            $r = $r + 1;
        }
        return implode(" , ", $checkd);
    }

//addtpost
    function addTPost($tble, $ncol) {
        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);
        $checkd = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd[] = "'$" . $info->name . "'";
            }
            $r = $r + 1;
        }
        return implode(" , ", $checkd);
    }

//ifmpty
    function ifMpty($tble, $ncol) {
        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);
        $checkd = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd[] = '!empty($' . $info->name . ')';
            }

            $r = $r + 1;
        }
        return implode(" && ", $checkd);
    }

//updatedata
    function updateData($tble, $ncol) {
        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);
        $varnames = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $name = mysqli_fetch_field($result);

            if ($name->name != $ncol) {
                $varnames[] = $name->name . " = '$" . $name->name . "'";
            }
            $r = $r + 1;
        }
        return implode(", ", $varnames);
    }

//ifempty
    function ifEmpty($tble, $ncol) {
        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);
        $checkd = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd[] = '!empty($_POST["' . $info->name . '"])';
            }

            $r = $r + 1;
        }
        return implode(" && ", $checkd);
    }

// ------------------------------->
// edit row
    function editColm($tble, $ncol, $id) {

        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        if (!$conn) {
            die('Error: Could not connect: ' . mysqli_error());
        }

        $query = "select * from $tble where $ncol = '$id' ";

        $result = $conn->query($query);

        if (!$result) {
            $message = 'ERROR:' . mysqli_error();
            return $message;
        } else {
            $i = 0;
            $ttle = str_replace("_", " ", $tble);
            echo '<form class="form-horizontal" method="POST">
    <fieldset>

        <!-- Form Name -->

        <legend>' . ucfirst($ttle) . '</legend>';

            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            while ($i < mysqli_num_fields($result)) {
                $meta = mysqli_fetch_field($result);
                if ($meta->name == $ncol) {
                    continue;
                } else {
                    $remp = str_replace("_", " ", $meta->name);
                    $mdat = $row[$meta->name];

                    echo '<!-- Text input-->
        <div class="form-group">
            <label for="' . $meta->name . '">' . ucfirst($remp) . ':</label>  
            <input id="' . $meta->name . '" name="' . $meta->name . '" value="' . $mdat . '" class="form-control input-md" type="text">
            <span class="help-block">' . $meta->name . '</span>  

        </div>';
                }
                $i = $i + 1;
            }

            echo '<!-- Button -->
        <div class="form-group">  
            <div class="col-md-4">
                <button type="button" id="editrow" name="editrow" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Edit</button>
            </div>
        </div>';
            echo '</fieldset>
</form>';
            mysqli_free_result($result);
        }
        mysqli_close($con);
    }

//add colm
    function addColm($tble) {

        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        if (!$conn) {
            die('Error: Could not connect: ' . mysqli_error());
        }

        $query = 'SELECT * FROM' . $tble;

        $result = $conn->query($query);

        if (!$result) {
            $message = 'ERROR:' . mysqli_error();
            return $message;
        } else {
            $i = 0;
            echo '<form class="form-horizontal">
    <fieldset>

        <!-- Form Name -->
        <legend>' . $tble . '</legend>';
            while ($i < mysqli_num_fields($result)) {
                $meta = mysqli_fetch_field($result);
                $remp = str_replace("_", " ", $meta->name);
                echo '<!-- Text input-->
        <div class="form-group">
            <label class="col-md-4 control-label" for="textinput">' . ucfirst($remp) . '</label>  
            <div class="col-md-4">
                <input id="' . $meta->name . '" name="' . $meta->name . '" placeholder="' . ucfirst($remp) . '" class="form-control input-md" type="text">
                <span class="help-block">' . $meta->name . '</span>  
            </div>
        </div>';
                $i = $i + 1;
            }
            echo '<!-- Button -->
        <div class="form-group">  
            <div class="col-md-4">
                <button id="submit" name="submit" class="btn btn-primary">Save</button>
            </div>
        </div>';
            echo '</fieldset>
</form>';
            mysqli_free_result($result);
        }
        mysqli_close($con);
    }

    function supdateData($tble) {
        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);
        $varnames = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $name = mysqli_fetch_field($result);
            $varnames[] = $name->name . ': $' . $name->name;
            $r = $r + 1;
        }
        echo implode(", ", $varnames);
    }

    function supdateD($tble) {
        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);
        $varnames = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $name = mysqli_fetch_field($result);
            $varnames[] = $name->name . ':' . $name->name;
            $r = $r + 1;
        }
        echo implode(", ", $varnames);
    }

    function addReq($tble, $ncol) {
        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);
        $r = 0;
        $varnames = '';
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $varnames = '$' . $info->name . ' = mysqli_real_escape_string($con,$_REQUEST["' . $info->name . '"]); ' . "\n\r";
            }
            $r = $r + 1;
            return $varnames;
        }
    }

    function addReqch($tble, $ncol) {
        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);
        $checkd = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd[] = "' " . $info->name . " : $" . $info->name . " '";
            }

            $r = $r + 1;
        }
        return implode(" , ", $checkd);
    }

    function addvTtl($tble, $ncol) {
        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);
        $checkd = array();
        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd[] = "'$" . $info->name . "'";
            }

            $r = $r + 1;
        }
        return implode(" , ", $checkd);
    }

    function sValues($tble, $ncol) {
        $con = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);

        $r = 0;
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                $checkd = 'var ' . $info->name . ' = $("#' . $info->name . '").val();' . "\n";
                echo $checkd;
            }
            $r = $r + 1;
        }
    }

}

?>
