<?php

function chargerClasse($classname)
{
    if (file_exists('../models/'. ucfirst($classname).'.php')) {
        require '../models/'. ucfirst($classname).'.php';
    } elseif (file_exists('../entities/'. ucfirst($classname).'.php')) {
        require '../entities/'. ucfirst($classname).'.php';
    } elseif (file_exists('../traits/'. ucfirst($classname).'.php')) {
        require '../traits/'. ucfirst($classname).'.php';
    } else {
        require '../interface/'. ucfirst($classname).'.php';
    }
}
spl_autoload_register('chargerClasse');

session_start();

if (!empty($_SESSION['id'])) {
    $bdd = Database::BDD();

    $userManager = new UserManager($bdd);

    $objectUser = new User([
        'id' => $_SESSION['id'],
        'name' => $_SESSION['name'],
        'password' => $_SESSION['password'],
        'verifConnect' => 0
    ]);

    $updateUser = $userManager->updateUser($objectUser);

    session_destroy();
    
    header('location: login.php');
} else {
    header('location: login.php');
}
