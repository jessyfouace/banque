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

// check if user is connected
if (!empty($_SESSION['id'])) {
    // connect to db
    $bdd = Database::BDD();

    // instance a new manager
    $userManager = new UserManager($bdd);

    // instance a new user for reset verifconnect of db
    $objectUser = new User([
        'id' => $_SESSION['id'],
        'name' => $_SESSION['name'],
        'password' => $_SESSION['password'],
        'verifConnect' => 0
    ]);

    // update user
    $updateUser = $userManager->updateUser($objectUser);

    // destroy session
    session_destroy();
    
    // return user to login page
    header('location: login.php');
} else {
    header('location: login.php');
}
