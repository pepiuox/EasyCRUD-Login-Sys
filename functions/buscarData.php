<?php

function buscarData($tble, $col, $str)
{
    global $conn, $c;
    $total_pages = $conn->query("SELECT * FROM $tble")->num_rows;

    $colmns = $c->viewColumns($tble);

    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

    $num_results_on_page = 10;

    if ($stmt = $conn->prepare("SELECT * FROM $tble WHERE (`$col` LIKE '%" . $str . "%') LIMIT ?,?")) {
        
        $calc_page = ($page - 1) * $num_results_on_page;
        $stmt->bind_param('ii', $calc_page, $num_results_on_page);
        $stmt->execute();

        $result = $stmt->get_result();

        echo '
	<table class="table">
			<thead>
				<tr><th></th>';
        foreach ($colmns as $colmn) {
            $tremp = ucfirst(str_replace("_", " ", $colmn->name));
            $remp = str_replace(" id", " ", $tremp);
            echo '<th>' . $remp . '</th>';
        }
        echo '				
			</tr>
			</thead>
			<tbody>' . "\n";
        while ($row = $result->fetch_array()) {

            echo '<tr>' . "\n";
            echo '<td><!--Button -->
                <a id="editrow" name="editrow" class="btn btn-success" href="index.php?w=edit&tbl=' . $tble . '&id=' . $row[0] . '">Editar</a>
                <a id="deleterow" name="deleterow" class="btn btn-danger" href="index.php?w=delete&tbl=' . $tble . '&id=' . $row[0] . '">Borrar</a>
                </td>'. "\n";
            foreach ($colmns as $colmn) {
                $fd = $row[$colmn->name];
                $resultq = $conn->query("SELECT * FROM table_queries WHERE name_table='$tble' AND col_name='$colmn->name' AND input_type IS NOT NULL");
                $resv = $resultq->num_rows;
                $r = 0;
                if ($resv > $r) {
                    $trow = $resultq->fetch_assoc();
                    $tb = $trow['j_table'];
                    $id = $trow['j_id'];
                    $val = $trow['j_value'];
                    $tow = $conn->query("SELECT * FROM $tb WHERE $id='$fd'")->fetch_assoc();

                    echo '<td><a class="goto" href="buscar.php?w=find&tbl=' . $tb . '&id=' . $fd . '">' . $tow[$val] . '</a></td>';
                } else {
                    echo '<td>' . $row[$colmn->name] . '</td>';
                }
            }
            

            echo '</tr>' . "\n";
        }
        echo '</tbody>
		</table>' . "\n";

        if (ceil($total_pages / $num_results_on_page) > 0) {
            ?>
<nav aria-label="Page navigation">
	<ul class="pagination justify-content-center mx-auto">
		<?php if ($page > 1){ ?>
		<li class="prev"><a href="buscar.php?page=<?php echo $page-1 ?>">Anterior</a></li>
		<?php } ?>

		<?php if ($page > 3){ ?>
		<li class="start"><a href="buscar.php?page=1">1</a></li>
		<li class="dots">...</li>
		<?php } ?>

		<?php if ($page-2 > 0){ ?>
		<li class="page"><a href="buscar.php?page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a></li>
		<?php } ?>
		<?php if ($page-1 > 0){ ?>
		<li class="page"><a href="buscar.php?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a></li>
		<?php } ?>

		<li class="currentpage"><a href="buscar.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>

		<?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1){ ?>
		<li class="page"><a href="buscar.php?page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></li>
		<?php } ?>
		<?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1){ ?>
		<li class="page"><a href="buscar.php?page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></li>
		<?php } ?>

		<?php if ($page < ceil($total_pages / $num_results_on_page)-2){ ?>
		<li class="dots">...</li>
		<li class="end"><a
			href="buscar.php?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
		<?php } ?>

		<?php if ($page < ceil($total_pages / $num_results_on_page)){ ?>
		<li class="next"><a href="buscar.php?page=<?php echo $page+1 ?>">Siguiente</a></li>
		<?php } ?>
	</ul>
</nav>
<?php
        }
        $stmt->close();
    }
}

?>