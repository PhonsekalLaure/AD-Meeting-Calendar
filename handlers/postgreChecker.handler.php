<?php
$configs = require UTILS_PATH . 'envSetter.util.php';
$pgConfig = $configs['pgConfig'];

$host = $pgConfig['host'];
$port = $pgConfig['port'];
$username = $pgConfig['user'];
$password = $pgConfig['pass'];
$dbname = $pgConfig['db'];

$conn_string = "host=$host port=$port dbname=$dbname user=$username password=$password";

$dbconn = pg_connect($conn_string);

if (!$dbconn) {
    return "❌ Connection Failed: " . pg_last_error() . "  <br>";
} else {
    pg_close($dbconn);
    return "✔️ PostgreSQL Connection  <br>";
}