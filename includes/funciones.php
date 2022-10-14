<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Identifica el ultimo elemento de la interaccion de los precios para el total 
function esUltimo(string $actual, string $ultimo):bool {
    if($actual !== $ultimo){
        return true;
    }
    return false;
}

// Funcion que revisa que el usuario este autenticado
function isAuth() : void{
    if(!isset($_SESSION['login'])){
        header('Location:/');
    }
}

// Identifica si el usuario es un administrador
function isAdmin() : void{
    if(!isset($_SESSION['admin'])){
        header('Location:/');
    }
}