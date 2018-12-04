<?php

declare(strict_types = 1);

class AccountManager
{
    private $_bdd;

    public function __construct(PDO $bdd)
    {
        $this->setBdd($bdd);
    }

    public function verifAccount(Account $user)
    {
        $nameDisponibility = "";
        $query = $this->getBdd()->prepare('SELECT * FROM accounts WHERE name = :name');
        $query->bindValue(':name', $user->getName(), PDO::PARAM_STR);
        $query->execute();
        $userInfo = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($userInfo as $checkDisponibility) {
            $nameDisponibility = new Account($checkDisponibility);
        }

        return $nameDisponibility;
    }

    public function takeAccountById(int $id)
    {
        $idExist = "";
        $query = $this->getBdd()->prepare('SELECT * FROM accounts WHERE id = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $checkId = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($checkId as $idDisponibility) {
            $idExist = new Account($idDisponibility);
        }

        return $idExist;
    }


    /**
     * TAKE ALL ACCOUNTS
     *
     * @return self
     */
    public function takeAccounts()
    {
        $arrayAccounts = [];
        $query = $this->getBdd()->prepare('SELECT * FROM accounts');
        $query->execute();
        $allAccounts = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($allAccounts as $account) {
            $arrayAccounts[] = new Account($account);
        }

        return $arrayAccounts;
    }

    /**
     * add new user
     *
     * @param integer $id
     * @return self
     */
    public function addAccount(Account $user)
    {
        $query = $this->getBdd()->prepare('INSERT INTO accounts(name, balance) VALUES(:name, :balance)');
        $query->bindValue(':name', $user->getName(), PDO::PARAM_STR);
        $query->bindValue(':balance', $user->getBalance(), PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * remove account by id
     *
     * @param Account $user
     * @return self
     */
    public function removeAccount(Account $user)
    {
        $query = $this->getBdd()->prepare('DELETE FROM accounts WHERE id = :id');
        $query->bindValue(':id', $user->getId(), PDO::PARAM_STR);
        $query->execute();
    }

    public function updateAccount(Account $user)
    {
        $updateBdd = $this->getBdd()->prepare('UPDATE accounts SET balance = :balance WHERE id = :id');
        $updateBdd->bindValue(':id', $user->getId(), PDO::PARAM_STR);
        $updateBdd->bindValue(':balance', $user->getBalance(), PDO::PARAM_STR);
        $updateBdd->execute();
    }

    /**
     * Get the value of _bdd
     */
    public function getBdd()
    {
        return $this->_bdd;
    }

    /**
     * Set the value of _bdd
     *
     * @return  self
     */
    public function setBdd(PDO $bdd)
    {
        $this->_bdd = $bdd;

        return $this;
    }
}
