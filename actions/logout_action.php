<?php
// actions/logout_action.php
require_once '../includes/config.php';
require_once '../includes/auth.php';

session_unset();
session_destroy();

header("Location: ../pages/login.php");
exit();
