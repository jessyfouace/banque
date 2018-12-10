<?php
class UserManager
{
    private $_bdd;

    public function __construct(PDO $bdd)
    {
        $this->setBdd($bdd);
    }

    /**
     * verif if user exist
     *
     * @param User $user
     * @return self
     */
    public function verifUser(User $user)
    {
        $query = $this->getBdd()->prepare('SELECT * FROM user WHERE name = :name');
        $query->bindValue(':name', $user->getName(), PDO::PARAM_STR);
        $query->execute();
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $getUser) {
            return new User($getUser);
        }
    }

    /**
     * verif if user as disponibility for sign up
     *
     * @param User $user
     * @return self
     */
    public function verifUserDispo(User $user)
    {
        $query = $this->getBdd()->prepare('SELECT * FROM user WHERE name = :name');
        $query->bindValue(':name', $user->getName(), PDO::PARAM_STR);
        $query->execute();
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $getUser) {
            return new User($getUser);
        }
    }

    /**
     * create user
     *
     * @param User $user
     * @return self
     */
    public function createUser(User $user)
    {
        $query = $this->getBdd()->prepare('INSERT INTO user(name, password, verifConnect) VALUES(:name, :password, :verifConnect)');
        $query->bindValue(':name', $user->getName(), PDO::PARAM_STR);
        $query->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
        $query->bindValue(':verifConnect', $user->getVerifConnect(), PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * update user
     *
     * @param User $user
     * @return self
     */
    public function updateUser(User $user)
    {
        $updateBdd = $this->_bdd->prepare('UPDATE user SET verifConnect = :verifConnect WHERE id = :id');
        $updateBdd->bindValue(':id', $user->getId(), PDO::PARAM_INT);
        $updateBdd->bindValue(':verifConnect', $user->getVerifConnect(), PDO::PARAM_INT);
        $updateBdd->execute();
    }

    /**
     * get user by id
     *
     * @param integer $id
     * @return self
     */
    public function getUserById(int $id)
    {
        $query = $this->getBdd()->prepare('SELECT * FROM user WHERE id = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $getUser) {
            return new User($getUser);
        }
    }

    /** get user by name */
    public function getUserByName(string $name)
    {
        $query = $this->getBdd()->prepare('SELECT * FROM user WHERE name = :name');
        $query->bindValue(':name', $name, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($user as $getUser) {
            return new User($getUser);
        }
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
