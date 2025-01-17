<?php

require_once('../common/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_language = sanitize($_POST['language']);

    if ($selected_language === "en") {
        unset($_SESSION['language']);
    } else {
        $_SESSION['language'] = $selected_language;
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
}
?>
<div class="language-block" style="position: fixed; right: 0; z-index: 10;">
    <form method="post">
        <button type="submit" name="language" value="es">ES</button>
    </form>
    <form method="post">
        <button type="submit" name="language" value="en">EN</button>
    </form>
    <form method="post">
        <button type="submit" name="language" value="ro">RO</button>
    </form>
</div>