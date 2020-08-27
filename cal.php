<?php
include 'conn.php';
include 'top.php';
?>

</head>
<body>
    <div class="container">
        <div class="col-md-12">
            <h3>Reservación de citas</h3>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Categoria de servicio: </label> <select
                    id="categoria_servicio" name="categoria_servicio"
                    onchange="showService(this);">
                        <?php
                        $querycs = $conn->query("SELECT * FROM categoria_servicio");
                        ?>                                        
                    <option>Seleccione una categoria</option>
                    <?php
                    while ($rspo = $querycs->fetch_array()) {
                        ?>
                        <option
                            value="<?php echo $rspo['idCatser']; ?>"><?php echo $rspo['categoria']; ?></option>
                        <?php } ?>
                </select>

                <script type="text/javascript">
                    function showService(sel) {
                        var idCat = sel.options[sel.selectedIndex].value;
                        $("#servicio").html("");
                        if (idCat.length > 0) {
                            $.ajax({
                                type: "POST",
                                url: "servicio.php",
                                data: "idCat=" + idCat,
                                cache: false,
                            }).
                                    done(function (data) {
                                        $('#servicio').html(data);
                                    });
                        }
                    }
                </script>

            </div>
            <div class="col-md-8">
                <label>Nombre de servicio: </label> 
                <select id="servicio" name="servicio">
                    <option>Seleccione un servicio</option>
                </select>
            </div>
        </div>
        <div class="frmSearch">
            <label>Nombre del cliente: </label>
            <input type="text" id="search-box" placeholder="Nombre cliente" />
            <div id="suggesstion-box"></div>
        </div>

        <script>
            $(document).ready(function () {
                $("#search-box").keyup(function () {
                    $.ajax({
                        type: "POST",
                        url: "client.php",
                        data: 'keyword=' + $(this).val()
                    }).
                            done(function (data) {
                                $("#suggesstion-box").show();
                                $("#suggesstion-box").html(data);
                                $("#search-box").css("background", "#FFF");
                            });
                });
            });

            function selectCountry(val) {
                $("#search-box").val(val);
                $("#suggesstion-box").hide();
            }
        </script>


        <?php
        setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");

        $dayNames = array(
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miercoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sabádo'
        );
// $day = $dayNames[1];

        if (isset($_POST['todo'])) {

            $day = $_POST['day'];
            $month = $_POST['month'];
            $year = $_POST['year'];
            $hr = $_POST['hr'];
            $mn = $_POST['mn'];

            $date_value = $day . '/' . $month . '/' . $year;

            $date_value1 = $year . '-' . $month . '-' . $day . ' ' . $hr . ':' . $mn . ':00';

            $string = $date_value;
            $date = DateTime::createFromFormat("d/m/Y", $string);
            $dayl = ucfirst(strftime("%A", $date->getTimestamp())) . "\n";
            $date = new DateTime($date_value1);
            $dateObj = DateTime::createFromFormat('!m', $month);
            $monthName = $dateObj->format('F');
            // Convert it into the 12 hour time using the format method.
            $message = '<h4>Se creo la cita para el día ' . $dayl . ', ' . $day . ' ' . $monthName . ' a la hora ' . $date->format('g:iA') . '</h4>';
        }

        if (isset($message)) {
            echo '<div class="col-md-12 bg-info py-1 text-center">' . $message . '</div>';
        }
        ?>

        <form method="post" name="f1" action="">
            <table class="table" border="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Mes</th>
                        <th>Año</th>
                        <th>Hora</th>
                        <th>Minuto</th>
                        <th></th>


                    <tr>

                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php
                            echo '<select name="day">' . "\n";

                            for ($i = 1; $i <= 31; $i++) {
                                echo '<option value="';
                                if (strlen($i) === 1) {
                                    echo '0' . $i . '"';
                                } else {
                                    echo $i . '"';
                                }
                                if (date('j') === $i) {
                                    echo ' selected';
                                }
                                echo '>' . $i . '</option>' . "\n";
                            }
                            echo "</select>" . "\n";
                            ?>
                        </td>
                        <td>
                            <?php
                            echo '<select name="month">' . "\n";
                            for ($i = 0; $i <= 11; $i++) {
                                $month = date('F', strtotime("first day of $i month"));
                                $nmonth = date('n', strtotime($month));

                                echo '<option value="' . $nmonth . '"';
                                if (date('n') === $nmonth) {
                                    echo ' selected';
                                }
                                echo '>' . $month . '</option>' . "\n";
                            }
                            echo "</select>" . "\n";
                            ?>
                        </td>

                        <td><?php
                            echo '<select name="year">';
                            for ($i = 0; $i <= 5; $i++) {
                                $year = date('Y', strtotime("last day of +$i year"));
                                echo '<option name="' . $year . '">' . $year . '</option>' . "\n";
                            }
                            echo "</select>" . "\n";
                            ?>
                        </td>
                        <td><?php
                            echo '<select name="hr">' . "\n";
                            for ($i = 0; $i <= 23; $i++) {
                                if (strlen($i) === 1) {
                                    echo '<option value="0' . $i . '">0' . $i . '</option>' . "\n";
                                } else {
                                    echo '<option value="' . $i . '">' . $i . '</option>' . "\n";
                                }
                            }
                            echo "</select>" . "\n";
                            ?></td>
                        <td><?php
                            echo '<select name="mn">' . "\n";
                            for ($i = 0; $i <= 59; $i++) {
                                if (strlen($i) === 1) {
                                    echo '<option value="0' . $i . '">0' . $i . '</option>' . "\n";
                                } else {
                                    echo '<option value="' . $i . '">' . $i . '</option>' . "\n";
                                }
                            }
                            echo "</select>" . "\n";
                            ?>
                        </td>
                        <td><input type="hidden" name="todo" value="submit"> <input
                                class="btn" type="submit" value="Crear reserva"></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>