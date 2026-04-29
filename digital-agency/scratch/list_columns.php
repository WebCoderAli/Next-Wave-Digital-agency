<?php
require_once "includes/db.php";
$result = mysqli_query($conn, "SHOW COLUMNS FROM orders");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . "\n";
}
?>
