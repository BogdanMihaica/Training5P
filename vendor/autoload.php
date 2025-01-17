<?php
require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';

function my_autoloader($class)
{
    $path = str_replace("\\", "/", $class); 
    require_once $path . '.php';
}

spl_autoload_register('my_autoloader');

?>