<?php

declare(strict_types = 1);

class AccountManager
{
    private $_bdd;

    public function __construct(PDO $bdd)
    {
        $this->setBdd($bdd);
    }

    /**
     * verif if account exist
     *
     * @param Account $user
     * @return self
     */
    public function verifAccount(Account $user)
    {
        $nameDisponibility = "";
        $query = $this->getBdd()->prepare('SELECT * FROM accounts WHERE name = :name and idAccount = :idAccount');
        $query->bindValue(':name', $user->getName(), PDO::PARAM_STR);
        $query->bindValue(':idAccount', $user->getIdAccount(), PDO::PARAM_INT);
        $query->execute();
        $userInfo = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($userInfo as $checkDisponibility) {
            $nameDisponibility = new Account($checkDisponibility);
        }

        return $nameDisponibility;
    }

    /**
     * take account by user id
     *
     * @param integer $id
     * @param integer $idAccount
     * @return self
     */
    public function takeAccountById(int $id, int $idAccount)
    {
        $idExist = "";
        $query = $this->getBdd()->prepare('SELECT * FROM accounts WHERE id = :id and idAccount = :idAccount');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':idAccount', $idAccount, PDO::PARAM_INT);
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
    public function takeAccounts(int $idAccount)
    {
        $arrayAccounts = [];
        $query = $this->getBdd()->prepare('SELECT * FROM accounts WHERE idAccount = :idAccount');
        $query->bindValue(':idAccount', $idAccount, PDO::PARAM_INT);
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
        $query = $this->getBdd()->prepare('INSERT INTO accounts(name, balance, idAccount) VALUES(:name, :balance, :idAccount)');
        $query->bindValue(':name', $user->getName(), PDO::PARAM_STR);
        $query->bindValue(':balance', $user->getBalance(), PDO::PARAM_INT);
        $query->bindValue(':idAccount', $user->getIdAccount(), PDO::PARAM_INT);
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
        $query = $this->getBdd()->prepare('DELETE FROM accounts WHERE id = :id and idAccount = :idAccount');
        $query->bindValue(':id', $user->getId(), PDO::PARAM_INT);
        $query->bindValue(':idAccount', $user->getIdAccount(), PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * update user account
     *
     * @param Account $user
     * @return void
     */
    public function updateAccount(Account $user)
    {
        $updateBdd = $this->getBdd()->prepare('UPDATE accounts SET balance = :balance WHERE id = :id and idAccount = :idAccount');
        $updateBdd->bindValue(':id', $user->getId(), PDO::PARAM_INT);
        $updateBdd->bindValue(':idAccount', $user->getIdAccount(), PDO::PARAM_INT);
        $updateBdd->bindValue(':balance', $user->getBalance(), PDO::PARAM_INT);
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
