<?php
/**
 * @license
 * Copyright(c) 2002-2019 Jose Ricardo Mantilla Mantilla. All Rights Reserved.
 * Author: Jose Ricardo Mantilla Mantilla <contact@pepiuox.net> / <contact@labemotion.net>
 * Website Author: http://pepiuox.net/ / http://labemotion.net/
 * Author's licenses: http://pepiuox.net/license / http://labemotion.net/license
 * Project Name: EasyCRUD
 * 
 */
include_once 'classes/DbConfig.php';

class EasyCRUD extends DbConfig {

    public function __construct() {
        parent::__construct();
    }

// view list 
    public function viewList($tble) {
        $con = $this->connection;

        /* Start pagination */
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }

        /* End pagination */

        echo '<form method="POST">' . "\n";
        echo '<table class="table table-bordered">' . "\n";
        echo '<thead class="bg-success">' . "\n";
        echo '<tr>' . "\n";
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM $tble LIMIT $offset, $limit";
        $result = $conn->query($sql);
        $i = 0;
        while ($i < $result->field_count) {
            $meta = mysqli_fetch_field($result);
            $remp = str_replace("_", " ", $meta->name);
            echo '<th>' . ucfirst($remp) . '</th>' . "\n";
            $i = $i + 1;
        }
        echo '<th><a id="addrow" name="addrow" class="btn btn-primary" href="index.php?view=add&tbl=' . $tble . '">Add new</a></th>' . "\n";
        echo '</tr>' . "\n";
        echo '</thead>' . "\n";
        echo '<tbody>' . "\n";

        while ($row = mysqli_fetch_row($result)) {
            echo '<tr>' . "\n";
            $count = count($row);
            $y = 0;
            while ($y < $count) {
                $c_row = current($row);
                if ($y == 0) {
                    echo '<td id="' . $c_row . '">' . $c_row . '</td>' . "\n";
                } else {
                    echo '<td>' . $c_row . '</td>' . "\n";
                }
                next($row);
                $y = $y + 1;
            }

            $i_row = $row[0];
            echo '<td><!-- Button -->
                <a id="editrow" name="editrow" class="btn btn-success" href="index.php?view=edit&tbl=' . $tble . '&id=' . $i_row . '">Edit</a>
                <a id="deleterow" name="deleterow" class="btn btn-danger" href="index.php?view=delete&tbl=' . $tble . '&id=' . $i_row . '">Delete</a>   
                </td>';

            echo '</tr>' . "\n";
            $i = $i + 1;
        }
        echo '</tbody>' . "\n";
        echo '</table>' . "\n";
        echo '</form>' . "\n";
        ?>
        <div class="row">
            <div class='w-100 nav-scroller py-1 mb-2'>
                <nav aria-label="Page navigation" class="navbar-toggleable-md table-responsive">
                    <?php
                    if (isset($_GET['page']) && $_GET['page'] != "") {
                        $page = $_GET['page'];
                    } else {
                        $page = 1;
                    }
                    $query = "SELECT * FROM $tble";
                    $rs_result = $conn->query($query);
                    $row = $rs_result->fetch_array();
                    $total_records = count($row);

                    $previous_page = $page - 1;
                    $next_page = $page + 1;
                    $adjacents = "2";
                    $range = 10;
                    $total_no_of_pages = ceil($total_records / $limit);
                    $second_last = $total_no_of_pages - 1;
                    ?>        
                    <ul class="pagination justify-content-center">
                        <?php
                        if ($page > 1) {
                            echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=1'>First Page</a></li>";
                        }
                        ?>

                        <li <?php
                        if ($page <= 1) {
                            echo "class='disabled page-item'";
                        } else {
                            echo "class='page-item'";
                        }
                        ?>>
                            <a class='page-link' <?php
                            if ($page > 1) {
                                echo "href='" . DIR_REL . "?page=$previous_page'";
                            }
                            ?>>Previous</a>
                        </li>
                        <?php
                        if ($total_no_of_pages <= $range) {
                            $showp = $total_no_of_pages;
                        } else {
                            $showp = $range;
                        }

                        if ($page <= 5) {
                            for ($counter = 1; $counter <= $showp; $counter++) {
                                if ($counter == $page) {
                                    echo "<li class='active page-item'><a class='page-link'>$counter</a></li>";
                                } else {
                                    echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=$counter'>$counter</a></li>";
                                }
                            }
                            if ($total_no_of_pages >= $range) {
                                echo "<li class='page-item'><a class='page-link'>...</a></li>";
                                echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=$second_last'>$second_last</a></li>";
                                echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=$total_no_of_pages'>$total_no_of_pages</a></li>";
                            }
                        } elseif ($page > 5 && $page < $total_no_of_pages - 5) {
                            if ($total_no_of_pages >= $range) {
                                echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=1'>1</a></li>";
                                echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=2'>2</a></li>";
                                echo "<li class='page-item'><a class='page-link'>...</a></li>";
                                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                                    if ($counter == $page) {
                                        echo "<li class='active page-item'><a class='page-link'>$counter</a></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=$counter'>$counter</a></li>";
                                    }
                                }
                                echo "<li class='page-item'><a class='page-link'>...</a></li>";
                                echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=$second_last'>$second_last</a></li>";
                                echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=$total_no_of_pages'>$total_no_of_pages</a></li>";
                            }
                        } else {
                            if ($total_no_of_pages >= $range) {
                                echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=1'>1</a></li>";
                                echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=2'>2</a></li>";
                                echo "<li class='page-item'><a class='page-link'>...</a></li>";
                                for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                    if ($counter == $page) {
                                        echo "<li class='active page-item'><a class='page-link'>$counter</a></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=$counter'>$counter</a></li>";
                                    }
                                }
                            }
                        }
                        ?>
                        <li <?php
                        if ($page >= $total_no_of_pages) {
                            echo "class='disabled page-item'";
                        } else {
                            echo "class='page-item'";
                        }
                        ?>>
                            <a class='page-link' <?php
                               if ($page < $total_no_of_pages) {
                                   echo "href='" . DIR_REL . "?page=$next_page'";
                               }
                               ?>>Next</a>
                        </li>

                        <?php
                        if ($page < $total_no_of_pages) {
                            echo "<li class='page-item'><a class='page-link' href='" . DIR_REL . "?page=$total_no_of_pages'>Last</a></li>";
                        }
                        ?>
                    </ul>                   
                </nav>
            </div>
        </div>

        <?php
    }

//add item
    public function addItem($tble, $idCol) {
        $con = $this->connection;
        echo '<form method="post" role="form" class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>' . $tble . '</legend>';
        $addQuery = 'SELECT * FROM ' . $tble;
        $addResult = $conn->query($addQuery);

        /* Init loop */

        if (mysqli_num_fields($addResult) > 0) {
            $addmetas = $addResult->fetch_fields();
            foreach ($addmetas as $addmeta) {
                $remp = str_replace("_", " ", $addmeta->name);
                if ($addmeta->name === $idCol) {
                    continue;
                } else {
                    echo '<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">' . ucfirst($remp) . '</label>  
  <div class="col-md-4">
  <input id="' . $addmeta->name . '" name="' . $addmeta->name . '" placeholder="' . ucfirst($remp) . '" class="form-control input-md" type="text">
  <small class="form-text text-muted">' . ucfirst($remp) . '</small>  
  </div>
</div>';
                }
            }
        }
        /* End loop */
        echo '<!-- Button -->
<div class="form-group">  
  <div class="col-md-4">
    <button type="submit" id="addrow" name="addrow" class="btn btn-primary">Save</button>
  </div>
</div>';
        echo '</fieldset>
</form>';
    }

// editItem
    public function editItem($tble, $id, $idCol) {
        $con = $this->connection;
        echo '<form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>' . $tble . '</legend>';

        $editQuery = "SELECT * FROM $tble WHERE ";
        $editQuery .= $idCol . "=" . $id;

        $editResult = $conn->query($editQuery);

        if (mysqli_num_fields($editResult) > 0) {
            $editmetas = $editResult->fetch_fields();
            $rqu = $editResult->fetch_array();
            foreach ($editmetas as $editmeta) {
                $remp = str_replace("_", " ", $editmeta->name);

                if ($editmeta->name === $idCol) {
                    continue;
                } else {
                    echo '<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">' . ucfirst($remp) . '</label>  
  <div class="col-md-4">
  <input id="' . $editmeta->name . '" name="' . $editmeta->name . '" value="' . $rqu[$editmeta->name] . '" class="form-control input-md" type="text">
  <small class="form-text text-muted">' . ucfirst($remp) . '</small>  
  </div>
</div>';
                }
            }
        }
        echo '<!-- Button -->
<div class="form-group">  
  <div class="col-md-4">
    <button type="submit" id="editrow" name="editrow" class="btn btn-primary">Edit</button>
  </div>
</div>';
        echo '</fieldset>
</form>';
    }

//deleteItem
    public function deleteItem($tble, $id, $idCol) {
        $con = $this->connection;
        $deletequery = "SELECT * FROM $tble WHERE $idCol = '$id' ";
        $deleteresult = $conn->query($deletequery);
        echo '<form role="form" id="delete_' . $tble . '" method="POST">
                        <legend>' . $tble . '</legend>' . "\n";
        $deletemetas = $deleteresult->fetch_fields();
        $drow = $deleteresult->fetch_array();

        foreach ($deletemetas as $deletemeta) {
            $cdta = $drow[$deletemeta->name];
            if ($deletemeta->name === $idCol) {
                continue;
            } else {
                $remp = str_replace("_", " ", $deletemeta->name);
                echo '<div class="form-group">
                       <label for="' . $deletemeta->name . '">' . ucfirst($remp) . ':</label>
                       <input type="text" class="form-control" id="' . $deletemeta->name . '" name="' . $deletemeta->name . '" value="' . $cdta . '" readonly>
                  </div>' . "\n";
            }
        }
        echo '<div class="form-group">
             <button type="submit" id="deleterow" name="deleterow" class="btn btn-primary">Delete</button>
         </div>' . "\n";
        echo '</form>' . "\n";
    }

//addpost
    public function addPost($tble, $ncol) {
        $con = $this->connection;
        $query = "SELECT * FROM " . $tble;
        $result = $conn->query($query);
        $r = 0;
        $varnames = array();
        while ($r < mysqli_num_fields($result)) {
            $info = mysqli_fetch_field($result);
            if ($info->name != $ncol) {
                if ($info->type === 10) {
                    $varnames[] = '$' . $info->name . ' = date("Y-m-d", strtotime($_POST["' . $info->name . '"])); ' . "\r\n";
                } else {
                    $varnames[] = '$' . $info->name . ' = remove_junk($db->escape($_POST["' . $info->name . '"])); ' . "\r\n";
                }
            }
            $r = $r + 1;
        }
        return implode("", $varnames);
    }

//addttl
    public function addTtl($tble, $ncol) {
        $con = $this->connection;
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
    public function addTPost($tble, $ncol) {
        $con = $this->connection;
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

//ifempty
    public function ifEmpty($tble, $ncol) {
        $con = $this->connection;
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

//ifmpty
    public function ifMpty($tble, $ncol) {
        $con = $this->connection;
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
    public function updateData($tble, $ncol) {
        $con = $this->connection;
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

    function supdateData($tble) {
        $con = $this->connection;
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

    public function supdateD($tble) {
        $con = $this->connection;
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

    public function addReq($tble, $ncol) {
        $con = $this->connection;
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
        $con = $this->connection;
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
        $con = $this->connection;
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
        $con = $this->connection;
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
