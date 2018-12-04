<?php

declare(strict_types = 1);

class Account
{
    protected $id;
    protected $name;
    protected $balance;

    /**
     * constructor
     *
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->hydrate($array);
    }

    /**
     * Hydrate for setter
     *
     * @param array $donnees
     * @return self
     */
    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $id = (int) $id;
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the value of balance
     *
     * @return  self
     */
    public function setBalance($balance)
    {
        $balance = (int) $balance;
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of balance
     */
    public function getBalance()
    {
        return $this->balance;
    }

    public function creditBalance($balance)
    {
        $balance = (int) $balance;
        $balance = $this->getBalance() + $balance;
        return $this->setBalance($balance);
    }

    public function debitBalance($balance)
    {
        $balance = (int) $balance;
        $balance = $this->getBalance() - $balance;
        return $this->setBalance($balance);
    }

    public function transfertBalance(Account $user, $balance)
    {
        $balance = (int) $balance;
        $addBalance = $user->getBalance() + $balance;
        $this->removeBalance($balance);
        return $user->setBalance($addBalance);
    }

    public function removeBalance($balance)
    {
        $balance = (int) $balance;
        $removeBalance = $this->getBalance() - $balance;
        return $this->setBalance($removeBalance);
    }
    // if ($this->getName() !== $target->getName()) {

        // }
}
