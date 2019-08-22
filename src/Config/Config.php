<?php
namespace Eduardokum\CorreiosPhp\Config;

use Eduardokum\CorreiosPhp\Entities\Sender;
use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;
use Eduardokum\CorreiosPhp\Traits\MagicTrait;

abstract class Config
{
    use MagicTrait;

    protected $environment = null;
    protected $cnpj = null;
    protected $user = null;
    protected $password = null;
    protected $userRastro = null;
    protected $passwordRastro = null;
    protected $administrativeCode = null;
    protected $contract = null;
    protected $postCard = null;
    protected $direction = null;
    protected $sender = null;
    protected $directories = [
        '01' => ['name' => 'CS – Correios Sede', 'initials' => 'CS'],
        '03' => ['name' => 'SE – ACRE', 'initials' => 'ACR'],
        '04' => ['name' => 'SE – ALAGOAS', 'initials' => 'AL'],
        '06' => ['name' => 'SE – AMAZONAS', 'initials' => 'AM'],
        '05' => ['name' => 'SE – AMAPÁ', 'initials' => 'AP'],
        '08' => ['name' => 'SE – BAHIA', 'initials' => 'BA'],
        '10' => ['name' => 'SE – BRASÍLIA', 'initials' => 'BSB'],
        '12' => ['name' => 'SE – CEARÁ', 'initials' => 'CE'],
        '14' => ['name' => 'SE - ESPIRITO SANTO', 'initials' => 'ES'],
        '16' => ['name' => 'SE – GOIÁS', 'initials' => 'GO'],
        '18' => ['name' => 'SE – MARANHÃO', 'initials' => 'MA'],
        '20' => ['name' => 'SE - MINAS GERAIS', 'initials' => 'MG'],
        '22' => ['name' => 'SE - MATO GROSSO DO SUL', 'initials' => 'MS'],
        '24' => ['name' => 'SE - MATO GROSSO', 'initials' => 'MT'],
        '28' => ['name' => 'SE – PARÁ', 'initials' => 'PA'],
        '30' => ['name' => 'SE – PARAÍBA', 'initials' => 'PB'],
        '32' => ['name' => 'SE – PERNAMBUCO', 'initials' => 'PE'],
        '34' => ['name' => 'SE – PIAUÍ', 'initials' => 'PI'],
        '36' => ['name' => 'SE – PARANÁ', 'initials' => 'PR'],
        '50' => ['name' => 'SE - RIO DE JANEIRO', 'initials' => 'RJ'],
        '60' => ['name' => 'SE - RIO GRANDE DO NORTE', 'initials' => 'RN'],
        '26' => ['name' => 'SE – RONDONIA', 'initials' => 'RO'],
        '65' => ['name' => 'SE – RORAIMA', 'initials' => 'RR'],
        '64' => ['name' => 'SE - RIO GRANDE DO SUL', 'initials' => 'RS'],
        '68' => ['name' => 'SE - SANTA CATARINA', 'initials' => 'SC'],
        '70' => ['name' => 'SE – SERGIPE', 'initials' => 'SE'],
        '74' => ['name' => 'SE - SÃO PAULO INTERIOR', 'initials' => 'SPI'],
        '72' => ['name' => 'SE - SÃO PAULO', 'initials' => 'SPM'],
        '75' => ['name' => 'SE- TOCANTINS', 'initials' => 'TO'],
    ];

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
        if (!$this->cnpj) {
            throw new InvalidArgumentException("Cnpj Code Card not set.");
        }
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
     * @param $userRastro
     *
     * @return $this
     */
    public function setUserRastro($userRastro)
    {
        $this->userRastro = $userRastro;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserRastro()
    {
        return $this->userRastro;
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
     * @param $passwordRastro
     *
     * @return $this
     */
    public function setPasswordRastro($passwordRastro)
    {
        $this->passwordRastro = $passwordRastro;
        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordRastro()
    {
        return $this->passwordRastro;
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
        if (!$this->administrativeCode) {
            throw new InvalidArgumentException("Administrative Code Card not set.");
        }
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
        if (!$this->contract) {
            throw new InvalidArgumentException("Contract Card not set.");
        }
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
        if (!$this->postCard) {
            throw new InvalidArgumentException("Post Card not set.");
        }
        return $this->postCard;
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
        if (!$this->direction) {
            throw new InvalidArgumentException("Direction not set.");
        }
        return $this->direction;
    }

    /**
     * @param null $key
     *
     * @return array
     */
    public function getDirectories($key = null)
    {
        if (is_null($key)) {
            return $this->directories;
        }
        if (array_key_exists($key, $this->directories)) {
            return $this->directories[$key];
        }
        throw new InvalidArgumentException("Key $key is not a valid direction.");
    }

    /**
     * @return string
     */
    public function getDirectionName()
    {
        return $this->getDirectories($this->getDirection())['name'];
    }

    /**
     * @return string
     */
    public function getDirectionInitials()
    {
        return $this->getDirectories($this->getDirection())['initials'];
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
        if (!$this->sender) {
            throw new InvalidArgumentException("Sender Code Card not set.");
        }
        return $this->sender;
    }
}
