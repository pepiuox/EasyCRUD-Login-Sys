<?php

include_once 'conn.php';

if (!empty($_POST['nome'])) {
    $tnome = $_POST['nome'];
    $stb = $_POST['stb'];

    function selrQuery($tble) {
        global $link;
        if (!$link) {
            die('Error: Could not connect: ' . mysqli_error());
        }

        $sql = "SELECT * FROM " . $tble;
        $qresult = $link->query($sql);

        echo '<div class="form-group">
              <label class="col-md-12 control-label" for="column_id">Select a value to relate</label>
              <div class="col-md-12"> 
                 <select id="column_id" name="column_id" class="form-control">' . "\n";
        while ($rinfo = $qresult->fetch_field()) {
            $rempp = str_replace("_", " ", $rinfo->name);
            echo '<option value="' . $rinfo->name . '">' . $rempp . '</option>' . "\n";
        }
        echo '   </select>
              </div>
              </div>' . "\n";
    }

    function selvQuery($tble) {
        global $link;
        if (!$link) {
            die('Error: Could not connect: ' . mysqli_error());
        }

        $sql = "SELECT * FROM " . $tble;
        $qresult = $link->query($sql);

        echo '<div class="form-group">
  <label class="col-md-12 control-label" for="column_value">Select a value for show</label>
  <div class="col-md-12">
    <select id="column_value" name="column_value" class="form-control">' . "\n";
        while ($vinfo = $qresult->fetch_field()) {
            $vempp = str_replace("_", " ", $vinfo->name);
            echo '<option value="' . $vinfo->name . '">' . $vempp . '</option>' . "\n";
        }
        echo '</select>
  </div>
</div>' . "\n";
    }

    selrQuery($tnome);
    selvQuery($tnome);
    echo '<script type="text/javascript">
                $(document).ready(function () {
                    $("#column_value").change(function () {
                        var snname = this.value;
                        var stble ="' . $stb . '";
                        $.ajax({
                            url: "tbn.php",
                            data: "snname=" + snname + "&stble=" + stble,
                            type: "POST",
                            dataType: "html",
                            async: true,
                            success: function (response) {
                                $("#asnames").html(response);
                            }
                        });
                    });
                });
            </script>';
}


