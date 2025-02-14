<?php
// Path: admin/logout.php Logout
require('inc/essentials.php');

    session_start(); // Start Sesiune
    session_destroy(); // Distrugere sesiune
    redirect('index.php'); // Redirect la index.php
?>