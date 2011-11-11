<?php
$props = parse_ini_file("properties.ini");

$consumer_key = props['consumer_key'];
$consumer_secret = props['consumer_secret'];

$dbServer = props['server'];
$dbName = props['dbname'];
$dbTable = props['tableName'];
$user = props['username'];
$password = props['password'];

$homeUrl = props['url'];
$adminEmail = props['adminEmail'];
?>
