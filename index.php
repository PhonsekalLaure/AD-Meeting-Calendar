<?php
require_once LAYOUTS_PATH . "/main.layout.php";
$mongoCheckerResult = require_once HANDLERS_PATH . "/mongodbChecker.handler.php";
$postgresqlCheckerResult = require_once HANDLERS_PATH . "/postgreChecker.handler.php";

$title = "Meeting Callendar";
$error = trim((string) ($_GET['error'] ?? ''));
$error = str_replace("%", " ", $error);
$css = ['css' => ['/assets/css/style.css']];

renderMainLayout(
    function () use ($mongoCheckerResult, $postgresqlCheckerResult, $error){
        ?>
        <div class="status">
            <?php
                echo $mongoCheckerResult;
                echo $postgresqlCheckerResult;
            ?>
        </div>
        <form action="/handlers/auth.handler.php" method="POST">
        <label for="username" class="label">Username</label>
        <input id="username" name="username" type="text" required class="input">

        <label for="password" class="label">Password</label>
        <input id="password" name="password" type="password" required class="input">

        <input type="hidden" name="action" value="login">
        <button type="submit" class="button">Log In</button>

        <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </form>
    <?php
    },
    $title,
    $css
);
?>
