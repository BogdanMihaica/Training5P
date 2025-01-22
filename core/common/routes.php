<?php
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$queryString = $_SERVER['QUERY_STRING'] ?? '';
parse_str($queryString, $queryParams);

$routes = [
    '/' => '../controllers/index.php',
    '/cart' => '../controllers/cart.php',
    '/login' => '../controllers/login.php',
    '/orders' => '../controllers/orders.php',
    '/order' => '../controllers/order.php',
    '/products' => '../controllers/products.php',
    '/product' => '../controllers/product.php'
];

function abort($code = 404)
{
    http_response_code($code);

    require basePath('views/404.php');

    die();
}
function routeToController($uri, $routes)
{
    if (array_key_exists($uri, $routes)) {
        require $routes[$uri];
    } else {
        abort();
    }
}

routeToController($uri, $routes);
