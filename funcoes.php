<?php
session_start();

function estaLogado() {
    return isset($_SESSION['usuario_id']);
}

function ehAdmin() {
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin';
}

function redireciona($url) {
    header("Location: $url");
    exit;
}

function protegePagina() {
    if (!estaLogado()) {
        redireciona('login.php');
    }
}

function protegeAdmin() {
    protegePagina();
    if (!ehAdmin()) {
        redireciona('index.php?msg=Acesso negado');
    }
}

function logout() {
    session_destroy();
    redireciona('index.php');
}

function resumir($texto, $limite = 150) {
    return strlen($texto) > $limite ? substr($texto, 0, $limite) . '...' : $texto;
}
?>