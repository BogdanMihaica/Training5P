<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['language'])) {


    $selectedLanguage = sanitize($_GET['language']);

    if ($selectedLanguage === "en") {
        unset($_SESSION['language']);
    } else {
        $_SESSION['language'] = $selectedLanguage;
    }

    $afterLanguageQuery = strstr($_SERVER['QUERY_STRING'], '&', false);

    if ($afterLanguageQuery) {
        $afterLanguageQuery = substr($afterLanguageQuery, 1);
    }

    $newQueryString = $afterLanguageQuery ? ('?' . $afterLanguageQuery) : '';

    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . $newQueryString);
    exit;
}
?>
<div class="language-block" style="position: fixed; right: 0; z-index: 10;">
    <?php
    $currentQueryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    $baseUrl = strtok($_SERVER['REQUEST_URI'], '?');

    $queryParams = $currentQueryString ? '&' . $currentQueryString : '';
    ?>

    <a href="<?= $baseUrl . '?language=en' . $queryParams ?>">EN</a>
    <a href="<?= $baseUrl . '?language=ro' . $queryParams ?>">RO</a>
</div>