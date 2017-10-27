<?php
namespace Eduardokum\CorreiosPhp\Config;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;

class Homologacao implements ConfigContract
{
    private $environment = 'homologacao';
    private $user = 'sigep';
    private $password = 'n5f9t8';
    private $administrativeCode = '08082650';
    private $contract = '9912208555';
    private $postCad = '0057018901';

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getAdministrativeCode()
    {
        return $this->administrativeCode;
    }

    /**
     * @return string
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @return string
     */
    public function getPostCard()
    {
        return $this->postCad;
    }

}