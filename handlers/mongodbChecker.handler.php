<?php
try {
    $configs = require UTILS_PATH . 'envSetter.util.php';
    $mongoConfig = $configs['mongoConfig'];

    $mongo = new MongoDB\Driver\Manager($mongoConfig['uri']);

    $command = new MongoDB\Driver\Command(["ping" => 1]);
    $mongo->executeCommand("admin", $command);

    echo "✅ Connected to MongoDB successfully.  <br>";
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "❌ MongoDB connection failed: " . $e->getMessage() . "  <br>";
}
