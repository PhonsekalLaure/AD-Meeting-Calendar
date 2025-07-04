<?php

declare(strict_types=1);

// 1) Composer autoload
require 'vendor/autoload.php';

// 2) Composer bootstrap
require 'bootstrap.php';

// 3) envSetter
require_once UTILS_PATH . '/envSetter.util.php';

// 4) dummy data
$users = require_once DUMMIES_PATH . '/users.staticData.php';
$meetings = require_once DUMMIES_PATH . '/meetings.staticData.php';
$tasks = require_once DUMMIES_PATH . '/tasks.staticData.php';
$meeting_users = require_once DUMMIES_PATH . '/meeting_users.staticData.php';

echo "✅ Connected to PostgreSQL.\n";

// ——— Connect to PostgreSQL ———
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";
$pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// ——— Apply schemas before truncating ———
echo "📦 Applying schema files...\n";
$schemaFiles = [
    'database/user.model.sql',
    'database/meeting.model.sql',
    'database/meeting_user.model.sql',
    'database/task.model.sql'
];

foreach ($schemaFiles as $file) {
    echo "📄 Applying $file...\n";
    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new RuntimeException("❌ Could not read $file");
    }
    $pdo->exec($sql);
}

$stmtUsers = $pdo->prepare("
    INSERT INTO users (username, role, firstname, lastname, password)
    VALUES (:username, :role, :fn, :ln, :pw)
");
$stmtMeetings = $pdo->prepare("
    INSERT INTO meetings (title, description, schedule, location, created_by)
    VALUES (:title, :desc, :sched, :loc, :cr_by)
");
$stmtTasks = $pdo->prepare("
    INSERT INTO tasks (meeting_id, assigned_to, title, description, status, due_date, created_at)
    VALUES (:mID, :ass_to, :title, :desc, :stat, :due, :cr_at)
");
$stmtMeet_Ur = $pdo->prepare("
    INSERT INTO meeting_users (meeting_id, user_id, role)
    VALUES (:mID, :uID, :role)
");

// Track seeding success
$allSeeded = true;

echo "🔁 Seeding Users\n";
try {
    foreach ($users as $u) {
        $stmtUsers->execute([
            ':username' => $u['username'],
            ':role' => $u['role'],
            ':fn' => $u['firstname'],
            ':ln' => $u['lastname'],
            ':pw' => password_hash($u['password'], PASSWORD_DEFAULT),
        ]);
    }
} catch (PDOException $e) {
    echo "❌ Error seeding users: " . $e->getMessage() . "\n";
    $allSeeded = false;
}

echo "🔁 Seeding Meetings\n";
try {
    foreach ($meetings as $m) {
        $stmtMeetings->execute([
            ':title' => $m['title'],
            ':desc' => $m['description'],
            ':sched' => $m['schedule'],
            ':loc' => $m['location'],
            ':cr_by' => $m['created_by'],
        ]);
    }
} catch (PDOException $e) {
    echo "❌ Error seeding meetings: " . $e->getMessage() . "\n";
    $allSeeded = false;
}

echo "🔁 Seeding Tasks\n";
try {
    foreach ($tasks as $t) {
        $stmtTasks->execute([
            ':mID' => $t['meeting_id'],
            ':ass_to' => $t['assigned_to'],
            ':title' => $t['title'],
            ':desc' => $t['description'],
            ':stat' => $t['status'],
            ':due' => $t['due_date'],
            ':cr_at' => $t['created_at'],
        ]);
    }
} catch (PDOException $e) {
    echo "❌ Error seeding tasks: " . $e->getMessage() . "\n";
    $allSeeded = false;
}

echo "🔁 Seeding Meeting_Users\n";
try {
    foreach ($meeting_users as $mu) {
        $stmtMeet_Ur->execute([
            ':mID' => $mu['mID'],
            ':uID' => $mu['uID'],
            ':role' => $mu['role'],
        ]);
    }
} catch (PDOException $e) {
    echo "❌ Error seeding meeting_users: " . $e->getMessage() . "\n";
    $allSeeded = false;
}

// Final confirmation
if ($allSeeded) {
    echo "✅ All seeding processes completed successfully!\n";
}
