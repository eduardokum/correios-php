<?php
namespace Eduardokum\CorreiosPhp\Config;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;

class Producao implements ConfigContract
{
    private $environment = 'producao';
    private $user = 'sigep';
    private $password = 'n5f9t8';
    private $administrativeCode = '08082650';
    private $contract = '9912208555';

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $administrativeCode
     *
     * @return $this
     */
    public function setAdministrativeCode($administrativeCode)
    {
        $this->administrativeCode = $administrativeCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdministrativeCode()
    {
        return $this->administrativeCode;
    }

    /**
     * @param $contract
     *
     * @return $this
     */
    public function setContract($contract)
    {
        $this->contract = $contract;
        return $this;
    }

    /**
     * @return string
     */
    public function getContract()
    {
        return $this->contract;
    }
}