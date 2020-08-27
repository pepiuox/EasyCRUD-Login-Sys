<?php
include_once('../config.php'); //include your db config file
extract($_POST);

ob_start();

$path = basename($_SERVER['REQUEST_URI']);
$file = basename($path);

$fileName = basename($_SERVER['PHP_SELF']);

if ($file == $fileName) {
    header("Location: pagos.php?view=select");
}

function protect($string) {
    $protection = htmlspecialchars(trim($string), ENT_QUOTES);
    return $protection;
}

if (isset($_GET['view'])) {
    $view = protect($_GET['view']);
} else {
    header("Location: pagos.php?view=select");
}

include 'header_top.php';
?>

<style>            
    table{
        font-size: 10px;
    }
</style>
<script>
    $(document).ready(function () {

        function calVars(vl) {

            var myTD = '#row_' + vl;

            $(myTD).click(function (e) {

                var count = $(this).find('input:checkbox:checked').length;

                $('#total_' + vl).val(count);
                var tota = $('#total_' + vl).val();
                var salr = $('#salario_' + vl).val();
                var mt = (salr / 14) * tota;
                $('#monto_neto_' + vl).val(mt);
                $('#pago_total_' + vl).val(mt);
                ;
            });

            $('#comedor_' + vl).keyup(function () {
                var tota = $('#total_' + vl).val();
                var salr = $('#salario_' + vl).val();
                var comd = $(this).val();
                var mt = ((salr / 14) * tota) - comd;
                $('#monto_neto_' + vl).val(mt);
                $('#pago_total_' + vl).val(mt);
            });

            $('#horas_extra_' + vl).keyup(function () {
                var tota = $('#total_' + vl).val();
                var salr = $('#salario_' + vl).val();
                var comd = $('#comedor_' + vl).val();
                var hoex = $(this).val();
                var rel = ((salr / 14) * tota) - comd;
                var mt = Number(rel) + Number(hoex);
                $('#monto_neto_' + vl).val(mt);
                $('#pago_total_' + vl).val(mt);
            });

            $('#despacho_' + vl).keyup(function () {
                var mt = $('#monto_neto_' + vl).val();
                var desp = $(this).val();
                var pt = Number(mt) + Number(desp);
                $('#pago_total_' + vl).val(pt);
            });
            $(':input[type="number"]').change(function () {
                this.value = parseFloat(this.value).toFixed(2);
            });
        }
        $('tr').click(function (evt) {
            var cID = this.id;
            cID = cID.replace("row_", "");
            calVars(cID);
        });

    });
</script>
</head>
<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="contentHeader">
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
                                        var url = 'pagos.php?view=pago&col=' + selecttb;
                                        $('#fttl').text('Form ' + selecttb);
                                        window.location.replace(url);
                                    });
                                }
                                );
                            </script>
                            <label class="control-label" for="selecttb">Seleccionar granja</label>
                            <select id="selecttb" name="selecttb" class="form-control">
                                <option value="">Selecciona granja</option>
                                <?php
                                /* Get farm names */

                                $res = $conn->query("SELECT * FROM granjas");
                                while ($row = $res->fetch_array()) {
                                    $remp = str_replace("_", " ", $row['nombre_granja']);
                                    echo '<option value="' . $row['idGrnj'] . '">' . ucfirst($remp) . '</option>' . "\n";
                                }
                                ?>
                            </select>                               
                        </div>
                    </div>
                </div>
            </div>
            <?php
            /* View data in the selected table */
        } elseif ($view == "pago") {

            if (!empty($_GET['col'])) {
                $cole = protect($_GET['col']);

                function chckBox($val) {
                    $chboxs = array('d1_' . $val, 'd2_' . $val, 'd3_' . $val, 'd4_' . $val, 'd5_' . $val, 'd6_' . $val, 'd7_' . $val, 'd8_' . $val, 'd9_' . $val, 'd10_' . $val, 'd11_' . $val, 'd12_' . $val, 'd13_' . $val, 'd14_' . $val);
                    foreach ($chboxs as $chbox) {

                        $remp[] = 'if(isset($_POST["' . $chbox . '"])){' . "\n";
                        $remp[] .= '$' . $chbox . ' = $_POST["' . $chbox . '"];' . "\n";
                        $remp[] .= '}else{' . "\n";
                        $remp[] .= '$' . $chbox . ' = " ";' . "\n";
                        $remp[] .= '}' . "\n";
                    }
                    return implode(" ", $remp);
                }

                function gpost($cole) {
                    global $conn;
                    $sqls = "SELECT * FROM personal WHERE granja_id=$cole";
                    $results = $conn->query($sqls);
                    $n = 0;
                    $i = 1;
                    $cnt = $results->num_rows;

                    while ($cnt > $n) {
                        $val = $i++;

                        $remp[] = chckBox($val) . "\n";
                        $remp[] .= '$total_' . $val . ' = $_POST["total_' . $val . '"];' . "\n";
                        $remp[] .= '$comedor_' . $val . ' = $_POST["comedor_' . $val . '"];' . "\n";
                        $remp[] .= '$horas_extra_' . $val . ' = $_POST["horas_extra_' . $val . '"];' . "\n";
                        $remp[] .= '$monto_neto_' . $val . ' = $_POST["monto_neto_' . $val . '"];' . "\n";
                        $remp[] .= '$despacho_' . $val . ' = $_POST["despacho_' . $val . '"];' . "\n";
                        $remp[] .= '$pago_total_' . $val . ' = $_POST["pago_total_' . $val . '"];' . "\n\n";
                        $n = $n + 1;
                    }
                    return implode(" ", $remp);
                }

                function getValues($cole) {
                    global $conn;
                    $sql = "SELECT * FROM personal WHERE granja_id='$cole'";
                    $result = $conn->query($sql);

                    $x = 1;

                    while ($row = $result->fetch_array()) {
                        $val = $x++;
                        $adding[] = "('" . $cole . "', '" . $row['idPrsn'] . "', '\$desde', '\$al', '\$d1_" . $val . "', '\$d2_" . $val . "', '\$d3_" . $val . "', "
                                . "'\$d4_" . $val . "', '\$d5_" . $val . "', '\$d6_" . $val . "', '\$d7_" . $val . "', '\$d8_" . $val . "', "
                                . "'\$d9_" . $val . "', '\$d10_" . $val . "', '\$d11_" . $val . "', '\$d12_" . $val . "', '\$d13_" . $val . "', '\$d14_" . $val . "', "
                                . "'\$total_" . $val . "', '" . $row['salario'] . "', '\$comedor_" . $val . "', '\$horas_extra_" . $val . "', '\$monto_neto_" . $val . "', "
                                . "'\$despacho_" . $val . "', '\$pago_total_" . $val . "')";
                    }


                    $insert = "INSERT INTO fecha_laboral " . "\n";
                    $insert .= "(granja_id, personal_id, desde, al, d1, d2, d3, d4, d5, d6, d7, d8, d9, d10, d11, d12, d13, d14, total, salario, comedor, horas_extra, monto_neto, despacho, pago_total)" . "\n";
                    $insert .= " VALUES " . "\n";
                    $insert .= implode(", \n", $adding);
                    return $insert;
                }

                $tmpfile = 'granja' . $cole . '-view.php';
                $myfile = fopen("$tmpfile", "w") or die("Unable to open file!");
                $content = '<?php' . "\n";
                $content .= '//This is temporal file only for add new row' . "\n";
                $content .= "if (isset(\$_POST['submit'])) {" . "\n";
                $content .= "\$desde = \$_POST['desde'];" . "\n";
                $content .= "\$al = \$_POST['al'];" . "\n";
                $content .= gpost($cole) . "\n";
                $content .= "\n";

                $content .= '$ins_qry ="' . getValues($cole) . '";' . "\n";
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
                <div class="container-fluid pt-2">

                    <div id="test">

                    </div>
                    <form class="form-horizontal" method="post" action="">
                        <div class="form-group">
                            <?php
                            $res = $conn->query("SELECT * FROM granjas WHERE idGrnj='$cole'");
                            $row = $res->fetch_assoc();
                            $remp = str_replace("_", " ", $row['nombre_granja']);
                            ?>
                            <script>
                                $(function () {
                                    var $startDate = $('#desde');
                                    var $endDate = $('#al');

                                    $startDate.datepicker({
                                        format: 'dd-mm-yyyy',
                                        autoHide: true
                                    });
                                    $endDate.datepicker({
                                        format: 'dd-mm-yyyy',
                                        autoHide: true,
                                        startDate: $startDate.datepicker('getDate')
                                    });

                                    $startDate.on('change', function () {
                                        $endDate.datepicker('setStartDate', $startDate.datepicker('getDate'));
                                    });
                                });
                            </script>
                            <div class="col-md-12"><b>Granja <?php echo ucfirst($remp); ?></b> / Planilla Desde: <input class="docs-date" data-toggle="datepicker" id="desde" name="desde"> - Al: <input class="docs-date" data-toggle="datepicker" id="al" name="al"></div>

                            <table class="table">
                                <thead>
                                    <tr>                                       
                                        <?php
                                        $sqlh = "SELECT * FROM fecha_laboral";
                                        $resulth = $conn->query($sqlh);
                                        $t = 0;

                                        if ($resulth->field_count > $t) {
                                            while ($meta = $resulth->fetch_field()) {

                                                $remp = str_replace("_", " ", $meta->name);

                                                $id = 'idLbr';
                                                $farm = 'granja_id';
                                                $from = 'desde';
                                                $to = 'al';
                                                $update = 'actualizado';

                                                if ($meta->name != $id && $meta->name != $farm && $meta->name != $from && $meta->name != $to && $meta->name != $update) {
                                                    echo '<th>' . ucfirst($meta->name) . '</th>';
                                                }
                                            }
                                            $t = $t + 1;
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sqli = "SELECT * FROM personal p LEFT JOIN granjas g ON p.granja_id=g.idGrnj WHERE granja_id='$cole'";

                                    $resulti = $conn->query($sqli);

                                    $x = 1;
                                    while ($row = $resulti->fetch_array()) {
                                        $val = $x++;
                                        echo '<tr id="row_' . $val . '">' . "\n";
                                        echo '<td><input type="text" id="' . $row['idPrsn'] . '_' . $val . '" name="' . $row['idPrsn'] . '_' . $val . '" value="' . $row['nombre'] . ' ' . $row['apellido'] . '" readonly></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d1_' . $val . '" name="d1_' . $val . '" value="1"></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d2_' . $val . '" name="d2_' . $val . '" value="1" ></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d3_' . $val . '" name="d3_' . $val . '" value="1"></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d4_' . $val . '" name="d4_' . $val . '" value="1"></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d5_' . $val . '" name="d5_' . $val . '" value="1"></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d6_' . $val . '" name="d6_' . $val . '" value="1"></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d7_' . $val . '" name="d7_' . $val . '" value="1"></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d8_' . $val . '" name="d8_' . $val . '" value="1" ></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d9_' . $val . '" name="d9_' . $val . '" value="1"></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d10_' . $val . '" name="d10_' . $val . '" value="1"></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d11_' . $val . '" name="d11_' . $val . '" value="1"></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d12_' . $val . '" name="d12_' . $val . '" value="1"></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d13_' . $val . '" name="d13_' . $val . '" value="1"></td>' . "\n";
                                        echo '<td><input type="checkbox" id="d14_' . $val . '" name="d14_' . $val . '" value="1" ></td>' . "\n";
                                        echo '<td><input type="number" id="total_' . $val . '" name="total_' . $val . '" maxlength="2"></td>' . "\n";
                                        echo '<td><input type="number" id="salario_' . $val . '" name="salario_' . $val . '" value="' . $row['salario'] . '" maxlength="6" readonly></td>' . "\n";
                                        echo '<td><input type="number" id="comedor_' . $val . '" name="comedor_' . $val . '" maxlength="4" ></td>' . "\n";
                                        echo '<td><input type="number" id="horas_extra_' . $val . '" name="horas_extra_' . $val . '" maxlength="4" ></td>' . "\n";
                                        echo '<td><input type="number" id="monto_neto_' . $val . '" name="monto_neto_' . $val . '" maxlength="4" ></td>' . "\n";
                                        echo '<td><input type="number" id="despacho_' . $val . '" name="despacho_' . $val . '" maxlength="4" ></td>' . "\n";
                                        echo '<td><input type="number" id="pago_total_' . $val . '" name="pago_total_' . $val . '" maxlength="6" ></td>' . "\n";
                                        echo '</tr>' . "\n";
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
    </div>
</body>
</html>
