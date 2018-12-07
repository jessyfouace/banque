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

$bdd = Database::BDD();

$nicknameConnection = "";
$passwordConnection = "";
$messageConnection = "";

// check get connection
if (isset($_GET['connection'])) {
    // if true
    if ($_GET['connection'] == "true") {
        // check if post nickname exist
        if (isset($_POST['nickname'])) {
            // check if no't empty
            if ($_POST['nickname'] !== "") {
                // check post password exist
                if (isset($_POST['password'])) {
                    // check if no't empty
                    if ($_POST['password'] !== "") {
                        // instanc new user manager
                        $userManager = new UserManager($bdd);
                        // protection
                        $nickname = htmlspecialchars($_POST['nickname']);
                        $password = htmlspecialchars($_POST['password']);
                        // create new user
                        $objectUser = new User([
                            'name' => $nickname,
                            'password' => $password
                        ]);
                        // verif if user exist
                        $getUser = $userManager->verifUser($objectUser);
                        // if exist
                        if ($getUser) {
                            // verif password
                            if ($getUser->getPassword() == password_verify($password, $getUser->getPassword())) {
                                // create new user
                                $userManager = new UserManager($bdd);
                                $objectUser = new User([
                                    'name' => $getUser->getName(),
                                    'password' => $getUser->getPassword(),
                                    'verifConnect' => 1,
                                    'id' => $getUser->getId()
                                ]);
                                // update for put verifconnect = 1
                                $userManager->updateUser($objectUser);
                                // get user by id
                                $takeUser = $userManager->getUserById($objectUser->getId());
                                // add session
                                $_SESSION['id'] = $takeUser->getId();
                                $_SESSION['name'] = $takeUser->getName();
                                $_SESSION['password'] = $takeUser->getPassword();
                                $_SESSION['verifConnect'] = $takeUser->getVerifConnect();
                            } else {
                                $messageConnection = "Mot de passe ou Pseudonyme incorrect.";
                                $colorerror = "colorred";
                            }
                        } else {
                            $messageConnection = "Mot de passe ou Pseudonyme incorrect.";
                            $colorerror = "colorred";
                        }
                    } else {
                        $passwordConnection = "Erreur champ Mot de passe.";
                        $colorerror = "colorred";
                    }
                } else {
                    $passwordConnection = "Erreur champ Mot de passe.";
                    $colorerror = "colorred";
                }
            } else {
                $nicknameConnection = "Erreur champ Pseudonyme.";
                $colorerror = "colorred";
            }
        } else {
            $nicknameConnection = "Erreur champ Pseudonyme.";
            $colorerror = "colorred";
        }
    }
}

$nicknameInscription = "";
$passwordInscription = "";
$passwordVerifInscription = "";

// check if inscription exist
if (isset($_GET['inscription'])) {
    if ($_GET['inscription'] == "true") {
        // check if nickname exist
        if (isset($_POST['nickname'])) {
            if ($_POST['nickname'] !== "") {
                // check if password exist
                if (isset($_POST['password'])) {
                    if ($_POST['password'] !== "") {
                        // check if confirmpassword exist
                        if (isset($_POST['confirmpassword'])) {
                            if ($_POST['confirmpassword'] !== "") {
                                // check if password and confirm are same
                                if ($_POST['password'] == $_POST['confirmpassword']) {
                                    // instanc new usermanager
                                    $userManager = new UserManager($bdd);
                                    // Security
                                    $nickname = htmlspecialchars($_POST['nickname']);
                                    $password = htmlspecialchars($_POST['password']);
                                    $password = password_hash($password, PASSWORD_DEFAULT);
                                    // instanc new user
                                    $objectUser = new User([
                                        'name' => $nickname,
                                        'password' => $password,
                                        'verifConnect' => 0
                                    ]);
                                    // verif exist
                                    $getUser = $userManager->verifUserDispo($objectUser);
                                    // if user don't exist
                                    if (!$getUser) {
                                        // create user
                                        $userManager->createUser($objectUser);
                                        $messageConnection = "Inscription terminées, merci de vous connecter.";
                                        $colorerror = "colorgreen";
                                    // else don't create
                                    } else {
                                        $messageConnection = "Pseudo déjà utilisé.";
                                        $colorerror = "colorred";
                                    }
                                } else {
                                    $messageConnection = "Les 2 mot de passes ne sont pas identique.";
                                    $colorerror = "colorred";
                                }
                            } else {
                                $passwordVerifInscription = "Erreur champ Mot de passe.";
                                $colorerror = "colorred";
                            }
                        } else {
                            $passwordInscription = "Erreur champ Mot de passe.";
                            $colorerror = "colorred";
                        }
                    } else {
                        $passwordInscription = "Erreur champ Mot de passe.";
                        $colorerror = "colorred";
                    }
                } else {
                    $nicknameInscription = "Erreur champ Pseudonyme.";
                    $colorerror = "colorred";
                }
            } else {
                $nicknameInscription = "Erreur champ Pseudonyme.";
                $colorerror = "colorred";
            }
        }
    }
}

require('../views/loginVue.php');
