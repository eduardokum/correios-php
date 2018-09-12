<?php
namespace Eduardokum\CorreiosPhp\Entities;

use Eduardokum\CorreiosPhp\Traits\MagicTrait;

class Recipient
{
    use MagicTrait;

    private $national = 1;
    private $name;
    private $phone;
    private $cellphone;
    private $mail;
    private $street;
    private $number;
    private $complement;
    private $district;
    private $city;
    private $state;
    private $cep;

    /**
     * @return $this
     */
    public function national()
    {
        $this->national = 1;
        return $this;
    }

    /**
     * @return $this
     */
    public function international()
    {
        $this->national = 0;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isNational()
    {
        return $this->national;
    }

    /**
     * @return boolean
     */
    public function isInternational()
    {
        return !$this->national;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return Recipient
     */
    public function setName($name)
    {
        $this->name = substr($name, 0, 50);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     *
     * @return Recipient
     */
    public function setPhone($phone)
    {
        $this->phone = substr($phone, 0, 12);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCellphone()
    {
        return $this->cellphone;
    }

    /**
     * @param mixed $cellphone
     *
     * @return Recipient
     */
    public function setCellphone($cellphone)
    {
        $this->cellphone = substr($cellphone, 0, 12);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     *
     * @return Recipient
     */
    public function setMail($mail)
    {
        $this->mail = substr($mail, 0, 50);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     *
     * @return Recipient
     */
    public function setStreet($street)
    {
        $this->street = substr($street, 0, 50);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     *
     * @return Recipient
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * @param mixed $complement
     *
     * @return Recipient
     */
    public function setComplement($complement)
    {
        $this->complement = substr($complement, 0, 30);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param mixed $district
     *
     * @return Recipient
     */
    public function setDistrict($district)
    {
        $this->district = substr($district, 0, 30);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     *
     * @return Recipient
     */
    public function setCity($city)
    {
        $this->city = substr($city, 0, 30);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     *
     * @return Recipient
     */
    public function setState($state)
    {
        $this->state = substr($state, 0, 2);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * @param mixed $cep
     *
     * @return Recipient
     */
    public function setCep($cep)
    {
        $this->cep = $cep;

        return $this;
    }
}
