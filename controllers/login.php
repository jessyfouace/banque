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

// $loginManager = new loginManager($bdd);
$nicknameConnection = "";
$passwordConnection = "";
$messageConnection = "";

if (isset($_GET['connection'])) {
    if ($_GET['connection'] == "true") {
        if (isset($_POST['nickname'])) {
            if ($_POST['nickname'] !== "") {
                if (isset($_POST['password'])) {
                    if ($_POST['password'] !== "") {
                        $userManager = new UserManager($bdd);
                        $nickname = htmlspecialchars($_POST['nickname']);
                        $password = htmlspecialchars($_POST['password']);
                        $objectUser = new User([
                            'name' => $nickname,
                            'password' => $password
                        ]);
                        $getUser = $userManager->verifUser($objectUser);
                        if ($getUser) {
                            if ($getUser->getPassword() == password_verify($password, $getUser->getPassword())) {
                                $userManager = new UserManager($bdd);
                                $objectUser = new User([
                                    'name' => $getUser->getName(),
                                    'password' => $getUser->getPassword(),
                                    'verifConnect' => 1,
                                    'id' => $getUser->getId()
                                ]);
                                $userManager->updateUser($objectUser);
                                $takeUser = $userManager->getUserById($objectUser->getId());
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

if (isset($_GET['inscription'])) {
    if ($_GET['inscription'] == "true") {
        if (isset($_POST['nickname'])) {
            if ($_POST['nickname'] !== "") {
                if (isset($_POST['password'])) {
                    if ($_POST['password'] !== "") {
                        if (isset($_POST['confirmpassword'])) {
                            if ($_POST['confirmpassword'] !== "") {
                                if ($_POST['password'] == $_POST['confirmpassword']) {
                                    $userManager = new UserManager($bdd);
                                    $nickname = htmlspecialchars($_POST['nickname']);
                                    $password = htmlspecialchars($_POST['password']);
                                    $password = password_hash($password, PASSWORD_DEFAULT);
                                    $objectUser = new User([
                                        'name' => $nickname,
                                        'password' => $password,
                                        'verifConnect' => 0
                                    ]);
                                    $getUser = $userManager->verifUserDispo($objectUser);
                                    if (!$getUser) {
                                        $userManager->createUser($objectUser);
                                        $messageConnection = "Inscription terminées, merci de vous connecter.";
                                        $colorerror = "colorgreen";
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
