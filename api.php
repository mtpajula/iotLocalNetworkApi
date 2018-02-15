<?php
include_once 'LocalApi.php';

header('Content-Type: application/json');
$l = new LocalApi();
$l->run();

?>
