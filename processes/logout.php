<?php
require_once '../includes/functions.php';

session_destroy();
redirect('../views/login.php');
?>