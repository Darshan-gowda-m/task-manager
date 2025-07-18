<?php
// includes/auth.php

function isLoggedIn()
{
  return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn()
{
  if (!isLoggedIn()) {
    header("Location: ../pages/login.php");
    exit();
  }
}

function redirectIfLoggedIn()
{
  if (isLoggedIn()) {
    header("Location: ../pages/dashboard.php");
    exit();
  }
}
