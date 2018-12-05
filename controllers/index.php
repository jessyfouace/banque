<?php

// On enregistre notre autoload.
function chargerClasse($classname)
{
    if (file_exists('../models/'. $classname.'.php')) {
        require '../models/'. $classname.'.php';
    } else {
        require '../entities/' . $classname . '.php';
    }
}
spl_autoload_register('chargerClasse');

session_start();

if (!empty($_SESSION['id'])) {
    $bdd = Database::BDD();

    $accountManager = new AccountManager($bdd);

    // Create table and stock all the account possibility
    $typeAccount = ['PEL', 'Compte Courant', 'Livret A', 'Compte Joint'];

    // create error message
    $message = "";

    // checking for create account
    if (isset($_POST['name'])) {
        if ($_POST['name'] == 'PEL' || $_POST['name'] == 'Compte Courant' || $_POST['name'] == 'Livret A' || $_POST['name'] == 'Compte Joint') {
            // instance new account
            if ($_POST['name'] == "Compte Courant") {
                $createAccount = new Account([
                    'name' => $_POST['name'],
                    'balance' => 80,
                    'idAccount' => $_SESSION['id']
                ]);
            } else {
                $createAccount = new Account([
                    'name' => $_POST['name'],
                    'balance' => 0,
                    'idAccount' => $_SESSION['id']
                ]);
            }
            // check if account is no't create
            $verifAccount = $accountManager->verifAccount($createAccount);
            // if he's no't create
            if (!$verifAccount) {
                // Create an account
                $valideAccount = $accountManager->addAccount($createAccount);
            } else {
                // else message error
                $message = "Ce compte est déjà créer.";
            }
        }
    }

    // check for delete
    if (isset($_POST['delete'])) {
        if (isset($_POST['id'])) {
            $delete = htmlspecialchars($_POST['delete']);
            $getId = htmlspecialchars($_POST['id']);
            // take the account with the id
            $takeAccount = $accountManager->takeAccountById($getId, $_SESSION['id']);
            // remove him
            if ($takeAccount) {
                $accountManager->removeAccount($takeAccount);
            } else {
                $message = "Le compte que vous essayez de supprimer ne vous appartient pas";
            }
        }
    }

    // add money to you'r account
    if (isset($_POST['payment'])) {
        if (isset($_POST['id'])) {
            if (isset($_POST['balance'])) {
                $payment = htmlspecialchars($_POST['payment']);
                $getId = htmlspecialchars($_POST['id']);
                $balance = htmlspecialchars($_POST['balance']);
                // take the account with the id
                $takeAccount = $accountManager->takeAccountById($getId, $_SESSION['id']);
                if ($takeAccount) {
                    // add the money too the account
                    $creditAccount = $takeAccount->creditBalance($balance);
                    // update the account
                    $accountManager->updateAccount($takeAccount);
                } else {
                    $message = "Vous ne pouvez pas créditer un compte qui ne vous appartient pas";
                }
            }
        }
    }

    // for remove money to you'r acc
    if (isset($_POST['debit'])) {
        if (isset($_POST['id'])) {
            if (isset($_POST['balance'])) {
                $debit = htmlspecialchars($_POST['debit']);
                $getId = htmlspecialchars($_POST['id']);
                $balance = htmlspecialchars($_POST['balance']);
                // take account with the id
                $takeAccount = $accountManager->takeAccountById($getId, $_SESSION['id']);
                if ($takeAccount) {
                    // remove money to the account
                    $creditAccount = $takeAccount->debitBalance($balance);
                    // update account
                    $accountManager->updateAccount($takeAccount);
                } else {
                    $message = "Vous ne pouvez pas débiter un compte qui ne vous appartient pas";
                }
            }
        }
    }

    // Transfert money to another account
    if (isset($_POST['transfer'])) {
        if (isset($_POST['balance'])) {
            if (isset($_POST['idDebit'])) {
                if (isset($_POST['idPayment'])) {
                    $transfert = htmlspecialchars($_POST['transfer']);
                    $balance = htmlspecialchars($_POST['balance']);
                    $idDebit = htmlspecialchars($_POST['idDebit']);
                    $idPayment = htmlspecialchars($_POST['idPayment']);
                    // if the id of account is different of the id of second account
                    if ($idPayment !== $idDebit) {
                        // take the account of the 2
                        $takeGiver = $accountManager->takeAccountById($idDebit, $_SESSION['id']);
                        $takeTaker = $accountManager->takeAccountById($idPayment, $_SESSION['id']);
                        if ($takeGiver) {
                            if ($takeTaker) {
                                // make the transfert
                                $takeGiver->transfertBalance($takeTaker, $balance);
                                // update the 2 accounts
                                $accountManager->updateAccount($takeGiver);
                                $accountManager->updateAccount($takeTaker);
                            } else {
                                $message = "Le compte auquel vous voulez transferer ne vous appartient pas.";
                            }
                        } else {
                            $message = "Le compte qui transfert n'est pas le votre.";
                        }
                    } else {
                        // else error message
                        $message = "Vous ne pouvez pas vous donner de l'argent.";
                    }
                }
            }
        }
    }


    // take all accounts for views
    $accounts = $accountManager->takeAccounts($_SESSION['id']);


    include "../views/indexView.php";
} else {
    header('location: login.php');
}
