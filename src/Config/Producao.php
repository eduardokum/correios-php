<?php
namespace Eduardokum\CorreiosPhp\Config;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;

class Producao implements ConfigContract
{
    private $environment = 'producao';
    private $user = null;
    private $password = null;
    private $administrativeCode = null;
    private $contract = null;
    private $postCard = null;
    private $serviceCode = null;

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

    /**
     * @param $postCard
     *
     * @return $this
     */
    public function setPostCard($postCard)
    {
        $this->postCard = $postCard;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostCard()
    {
        return $this->postCard;
    }

    /**
     * @param $serviceCode
     *
     * @return $this
     */
    public function setServiceCode($serviceCode)
    {
        $this->serviceCode = $serviceCode;
        return $this;
    }
    /**
     * @return string
     */
    public function getServiceCode()
    {
        return $this->serviceCode;
    }
}