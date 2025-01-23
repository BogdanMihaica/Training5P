<?php
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

function addToCart($id, $quantity)
{
    if (!in_array($id, array_keys($_SESSION['cart']))) {
        $_SESSION['cart'][$id] = $quantity;
    }
}

function removeFromCart($index)
{
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
    }
}

function sanitize($string)
{
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML401, 'UTF-8');
}

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

function dd($object)
{
    die(var_dump($object));
}

function basePath($path)
{
    return BASE_PATH . $path;
}

function redirect($url)
{
    header('Location: ' . $url);
}
