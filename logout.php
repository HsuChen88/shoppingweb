<?php
    session_start();

    // remove all session variables
    session_unset();
    
    // destroy the session
    session_destroy();

    // set the expiration date to one hour ago
    setcookie("user_id_cookie", "", time() - 3600);
    
    header("Location:index.php");
?>