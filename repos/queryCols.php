<?php
include 'header_top.php';
include_once('../config.php'); //include your db config file
$sql = "SELECT * FROM cols_set";
$result = $conn->query($sql);
$count = $result->field_count;
$i = 0;
$tbl = 'empresa';
$sqlt = "SELECT * FROM $tbl";
$resultt = $conn->query($sqlt);
$countt = $resultt->field_count;
$y = 0;
$g = 1;
while ($meta = $result->fetch_field()) {
    $names[] = $meta->name;
    $vb = $meta->name;
}
foreach ($names as $key => $name) {

    if ($key == 0) {
        continue;
    } else {
        $vars[] = $name;
    }
}
$nvas = implode(', ', $vars);

$px = 1;
while ($tnm = $resultt->fetch_field()) {
    $vls = $px++;

    $mrs[] = "('$tbl', '$" . $tnm->name . "', '\$type_input" . $vls . "', '\$list_page" . $vls . "', '\$add_page" . $vls . "', '\$update_page" . $vls . "', '\$view_page" . $vls . "', '\$delete_page" . $vls . "', '\$search_text" . $vls . "', '\$col_set" . $vls . "')" . "\n\r";
}


$nmrs = implode(', ', $mrs);
$insert = "INSERT INTO cols_set " . "\n";
$insert .= '(' . $nvas . ')' . "\n";
$insert .= " VALUES " . "\n";
$insert .= $nmrs;
?>
</head>
<body>
    <?php
    echo $insert;
    ?>
</body>
</html>

