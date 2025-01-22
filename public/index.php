<?php
const BASE_PATH = __DIR__ . '/../';

require_once(BASE_PATH . 'core/common/functions.php');

require_once basePath('core/database.php');

require_once basePath('core/common/routes.php');

unset($_SESSION['_flash']['errors']);
