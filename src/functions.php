<?php

function sanitize($data)
{
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($data))));
}

function redirect($url)
{
    header("Location: $url");
    exit();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function requireLogin()
{
    if (!isLoggedIn()) {
        redirect('index.php?action=login');
    }
}

function requireRole($allowedids)
{
    if (!isLoggedIn() || !in_array($_SESSION['role'], $allowedids)) {
        die("Access Denied: You do not have permission to view this page.");
    }
}

function currentUser()
{
    return $_SESSION['user_id'] ?? null;
}

function currentRole()
{
    return $_SESSION['role'] ?? null;
}

function view($path, $data = [])
{
    global $conn;
    extract($data);
    require_once __DIR__ . "/Views/$path.php";
}
