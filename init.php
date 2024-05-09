<?php

require 'vendor/autoload.php';  

session_start();
function nactiTridu($nazevTridy) {
    if (preg_match("/Kontroler$/", $nazevTridy)) 
        require "kontrolery/$nazevTridy.php";
    else
        require "modely/$nazevTridy.php";
}

if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // Uživatel je přihlášen, můžeš provádět další operace nebo zobrazit obsah stránky pro přihlášeného uživatele.
    //echo "Přihlášený uživatel: " . $_SESSION['email'];
} else {
    // Uživatel není přihlášen, můžeš přesměrovat na přihlašovací stránku nebo zobrazit přihlašovací formulář.
   // echo "Uživatel není přihlášen.";
}

spl_autoload_register("nactiTridu");

Db::pripoj("localhost", "sport", "SportKratoskola24", "sport");