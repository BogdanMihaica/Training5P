<?php
require_once('../common/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['language'])) {
    $selectedLanguage = sanitize($_GET['language']);

    if ($selectedLanguage === "en") {
        unset($_SESSION['language']);
    } else {
        $_SESSION['language'] = $selectedLanguage;
    }

    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}
?>
<div class="language-block" style="position: fixed; right: 0; z-index: 10;">
    <a href="?language=en">EN</a>
    <a href="?language=ro">RO</a>
</div>