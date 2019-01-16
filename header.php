<?php
include_once 'database.php';
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)) {
    header('Location:/sign.php');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/bootstrap-reboot.css">
    <link rel="stylesheet" href="/css/bootstrap-grid.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" href="/images/logo.png" sizes="any" type="image/png">
    <title>Physmath School of Yerevan</title>
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/bootstrap.bundle.js"></script>
    <script src="/js/bootstrap.js"></script>
</head>
