<?php
require_once('../common/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['language'])) {
    $selected_language = sanitize($_GET['language']);

    if ($selected_language === "en") {
        unset($_SESSION['language']);
    } else {
        $_SESSION['language'] = $selected_language;
    }

    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}
?>
<div class="language-block" style="position: fixed; right: 0; z-index: 10;">
    <a href="?language=es">ES</a>
    <a href="?language=en">EN</a>
    <a href="?language=ro">RO</a>
</div>