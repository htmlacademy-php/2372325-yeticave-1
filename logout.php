<?php
session_start();
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}
session_destroy();

if (ob_get_level()) ob_end_clean(); 

header('Location: /index.php');
exit;