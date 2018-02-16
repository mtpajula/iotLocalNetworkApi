<?php
include_once 'LocalApi.php';

echo '<p><b>Creating iot tables</b></p>';
$l = new LocalApi();

$sqls = array();

$sqls[] = "CREATE TABLE command (
                id INTEGER PRIMARY KEY NOT NULL,
                device TEXT,
                payload TEXT,
                time DATETIME DEFAULT CURRENT_TIMESTAMP)";

$sqls[] = "CREATE TABLE schedule (
                id INTEGER PRIMARY KEY NOT NULL,
                device TEXT,
                start TEXT,
                payload TEXT,
                time DATETIME DEFAULT CURRENT_TIMESTAMP)";

$sqls[] = "CREATE TABLE message (
                id INTEGER PRIMARY KEY NOT NULL,
                device TEXT,
                payload TEXT,
                time DATETIME DEFAULT CURRENT_TIMESTAMP)";

$sqls[] = "CREATE TABLE status (
                id INTEGER PRIMARY KEY NOT NULL,
                device TEXT,
                payload TEXT,
                time DATETIME DEFAULT CURRENT_TIMESTAMP)";

$sqls[] = "CREATE TABLE device (
                id INTEGER PRIMARY KEY NOT NULL,
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
