<?php

/**
 * Gets the path of an image from the images directory by a specified id
 * 
 * @param integer $id
 * 
 * @return string
 */
function getImageForId($id)
{
    $dir = basePath('public/src/images/');
    $files = scandir($dir);

    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_FILENAME) == $id) {
            return trim($file);
        }
    }

    return '';
}

/**
 * Handles the uploading process of an image. It also checks if the file provided is an actual image and returns a response as following:
 * 0 : Unknown error
 * 1 : File upload success
 * 2 : Not supported type
 * 
 * @param array $image
 * @param integer $id
 * 
 * @return integer
 */
function handleImageUpload($image, $id)
{
    $targetDir = "../public/src/images/";
    $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
    $newFileName = $targetDir . $id . '.' . $fileExtension;
    $acceptedFileTypes = ['png', 'jpg', 'jpeg', 'gif'];

    # Verify if the file is an image

    $check = getimagesize($image["tmp_name"]);

    if (!$check) {
        return 3;
    }

    # Verify if the file is an image of valid type

    if (!in_array($fileExtension, $acceptedFileTypes)) {
        return 2;
    }

    # Verify if there is a file with the same name (not extension)

    $filesInTargetDir = scandir($targetDir);

    foreach ($filesInTargetDir as $file) {

        $fileName = pathinfo($file, PATHINFO_FILENAME);

        if ($fileName == $id) {
            unlink($targetDir . $file);
            break;
        }
    }

    if (move_uploaded_file($image["tmp_name"], $newFileName)) {
        return 1;
    }

    return 0;
}

/**
 * Adds a product to the cart with id and quantity
 * 
 * @param integer $id
 * @param integer $quantity
 * 
 */
function addToCart($id, $quantity)
{
    if (!in_array($id, array_keys($_SESSION['cart']))) {
        $_SESSION['cart'][$id] = $quantity;
    }
}

/**
 * Removes an item from the cart if it exists
 * 
 * @param integer $index
 */
function removeFromCart($index)
{
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
    }
}

/**
 * Sanitizes a string using the htmlspecialchars function
 * 
 * @param string $string
 * 
 * @return string
 */
function sanitize($string)
{
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML401, 'UTF-8');
}

/**
 * Translates a string using a global variable 'translations'
 * 
 * @param string $string
 * 
 * @return string
 */
function translate($string)
{
    $translations = $GLOBALS['translations'];

    if (isset($_SESSION['language']) && $_SESSION['language'] !== '') {
        $lang = $_SESSION['language'];
        return $translations[$string][$lang];
    } else {
        return $string;
    }
}

/**
 * var_dumps an obnject and dies it (debug purposes)
 * 
 * @param mixed $object
 * 
 * @return never
 */
function dd($subject)
{
    die(var_dump($subject));
}

/**
 * Returns the path of a file using the BASE_PATH constant defined in public/index.html
 * 
 * @param string $path
 * 
 * @return string
 */
function basePath($path)
{
    return BASE_PATH . $path;
}

/**
 * Redirects the user to a specified url
 * 
 * @param string $url
 * 
 * @return void
 */
function redirect($url)
{
    header('Location: ' . $url);
}

/**
 * Verifies if a variable is empty or not equals to 0
 * @param mixed $var
 * @return bool
 */
function isEmpty($var)
{
    if (is_array($var)) {
        return count($var) === 0;
    }
    return strlen($var) === 0;
}

/**
 * Returns the value from the subject array if the key exists
 * 
 * @param array $subject
 * @param string $key
 * 
 * @return string
 */
function getIfExists($subject, $key)
{
    return array_key_exists($key, $subject) ? $subject[$key] : '';
}
