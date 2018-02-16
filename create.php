<?php
include_once 'LocalApi.php';

echo '<p><b>Creating iot tables</b></p>';
$l = new LocalApi();

$sqls = array();
$sqls[] = "DROP TABLE command";
$sqls[] = "DROP TABLE schedule";
$sqls[] = "DROP TABLE message";
$sqls[] = "DROP TABLE status";
$sqls[] = "DROP TABLE device";

$sqls[] = "CREATE TABLE command (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                device TEXT,
                payload TEXT,
                time DATETIME DEFAULT CURRENT_TIMESTAMP)";

$sqls[] = "CREATE TABLE schedule (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                device TEXT,
                start TEXT,
                payload TEXT,
                time DATETIME DEFAULT CURRENT_TIMESTAMP)";

$sqls[] = "CREATE TABLE message (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                device TEXT,
                payload TEXT,
                time DATETIME DEFAULT CURRENT_TIMESTAMP)";

$sqls[] = "CREATE TABLE status (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                device TEXT,
                payload TEXT,
                time DATETIME DEFAULT CURRENT_TIMESTAMP)";

$sqls[] = "CREATE TABLE device (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                address TEXT,
                name TEXT,
                task TEXT,
                time DATETIME DEFAULT CURRENT_TIMESTAMP)";

foreach ($sqls as &$sql) {
    echo "<p>$sql";
    $l->setSql($sql);
    $status = $l->executeRequest();
    if ($status) {
        echo " - Ok";
    } else {
        echo " - Failed";
        echo "<p><b>".$l->getRespond()."</b></p>";
    }
    echo "</p>";
}
$l->close();

?>
