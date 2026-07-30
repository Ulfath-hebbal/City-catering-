<?php
session_start();

/* CLEAR SESSION */
$_SESSION = array();

/* DESTROY SESSION */
session_destroy();

/* OPTIONAL: DELETE SESSION COOKIE */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

/* REDIRECT TO LOGIN */
header("Location: home.php");
exit();
?>