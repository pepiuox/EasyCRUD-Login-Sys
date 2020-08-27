<?php
include_once 'header.php';
/* Include header */
?>
<div class="container">
<?php

if (! empty(@$_SESSION['user_id'])) {

    $user = $_SESSION['user_id'];
    echo $user;

    $rquery = $conn->query("SELECT * FROM info WHERE username='$user'");
    $row = $rquery->fetch_assoc();
    if ($rquery->num_rows === 1) {
        ?>
  <div class="col-md-12">
		<form method="post" role="form" id="add_info">
			<div class="form-group">
				<label for="username">Username:</label> <input type="text"
					class="form-control" id="username" name="username"
					value="<?php $row['username']?>">
			</div>
			<div class="form-group">
				<label for="email">Email:</label> <input type="text"
					class="form-control" id="email" name="email">
			</div>
			<div class="form-group">
				<label for="nombre">Nombre:</label> <input type="text"
					class="form-control" id="nombre" name="nombre">
			</div>
			<div class="form-group">
				<label for="apellido">Apellido:</label> <input type="text"
					class="form-control" id="apellido" name="apellido">
			</div>
			<div class="form-group">
				<label for="telefono">Telefono:</label> <input type="text"
					class="form-control" id="telefono" name="telefono">
			</div>
			<div class="form-group">
				<label for="direccion">Direccion:</label> <input type="text"
					class="form-control" id="direccion" name="direccion">
			</div>
			<div class="form-group">
				<label for="genero">Genero:</label> <select type="text"
					class="form-control" id="genero" name="genero">
					<option value="Mujer">Mujer</option>

					<option value="Varon">Varon</option>

					<option value="No lo sabe">No lo sabe</option>

				</select>
			</div>
			<div class="form-group">
				<label for="edad">Edad:</label> <input type="text"
					class="form-control" id="edad" name="edad">
			</div>
			<div class="form-group">
				<label for="cumpleanos">Cumpleanos:</label> <input type="text"
					data-date-format="dd/mm/yyyy" class="form-control" id="cumpleanos"
					name="cumpleanos">
			</div>
			<script type="text/javascript">
                                        $(document).ready(function ()
                                        {
                                            $("#cumpleanos").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#cumpleanos").datepicker("setDate", new Date());
                                        });
                                    </script>
			<div class="form-group">
				<label for="active">Active:</label> <input type="text"
					class="form-control" id="active" name="active">
			</div>
			<div class="form-group">
				<label for="banned">Banned:</label> <input type="text"
					class="form-control" id="banned" name="banned">
			</div>
			<div class="form-group">
				<label for="date">Date:</label> <input type="text"
					data-date-format="dd/mm/yyyy" class="form-control" id="date"
					name="date">
			</div>
			<script type="text/javascript">
                                        $(document).ready(function ()
                                        {
                                            $("#date").datepicker({
                                                weekStart: 1,
                                                daysOfWeekHighlighted: "6,0",
                                                autoclose: true,
                                                todayHighlight: true
                                            });
                                            $("#date").datepicker("setDate", new Date());
                                        });
                                    </script>
			<div class="form-group">
				<button type="submit" id="addrow" name="addrow"
					class="btn btn-primary">
					<span class="glyphicon glyphicon-plus" onclick="dVals();"></span>
					Add
				</button>
			</div>
		</form>
	</div>	
    <?php
    }else{
        
    }
}
?>
</div>

