<?php
session_start();

function isAuthenticated() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: InicioDeSesion');
        exit;
    }
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUserEmail() {
    return $_SESSION['user_email'] ?? null;
}

