<?php
namespace Eduardokum\CorreiosPhp\Config;

use Eduardokum\CorreiosPhp\Entities\Sender;
use Eduardokum\CorreiosPhp\Traits\MagicTrait;

abstract class Config
{
    use MagicTrait;

    protected $environment = null;
    protected $cnpj = null;
    protected $user = null;
    protected $password = null;
    protected $administrativeCode = null;
    protected $contract = null;
    protected $postCard = null;
    protected $serviceCode = null;
    protected $direction = null;
    protected $sender = null;

    public function __construct()
    {
        $this->setSender(new Sender);
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param $cnpj
     *
     * @return $this
     */
    public function setCNPJ($cnpj)
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    /**
     * @return string
     */
    public function getCNPJ()
    {
        return $this->cnpj;
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

    /**
     * @param $direction
     *
     * @return $this
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param $sender
     *
     * @return $this
     */
    public function setSender(Sender $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return Sender
     */
    public function getSender()
    {
        return $this->sender;
    }
}
