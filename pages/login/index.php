<?php
require_once LAYOUTS_PATH . "/main.layout.php";

$title = "Meeting Callendar";
$css = ['css' => ['/assets/css/style.css']];

renderMainLayout(
    function (){
        ?>
        <div class="success-container">
            <h1>Login Success</h1>
        </div>
    <?php
    },
    $title,
    $css
);
?>