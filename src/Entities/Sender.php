<?php
namespace Eduardokum\CorreiosPhp\Entities;

use Eduardokum\CorreiosPhp\Traits\MagicTrait;

class Sender
{
    use MagicTrait;

    private $logo;
    private $name;
    private $street;
    private $number;
    private $complement;
    private $district;
    private $cep;
    private $city;
    private $state;
    private $phone;
    private $cellphone;
    private $mail;

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     *
     * @return Sender
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
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
     * @return Sender
     */
    public function setName($name)
    {
        $this->name = substr($name, 0, 50);

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
     * @return Sender
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
     * @return Sender
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
     * @return Sender
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
     * @return Sender
     */
    public function setDistrict($district)
    {
        $this->district = substr($district, 0, 30);

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
     * @return Sender
     */
    public function setCep($cep)
    {
        $this->cep = $cep;

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
     * @return Sender
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
     * @return Sender
     */
    public function setState($state)
    {
        $this->state = substr($state, 0, 2);

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
     * @return Sender
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
     * @return Sender
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
     * @return Sender
     */
    public function setMail($mail)
    {
        $this->mail = substr($mail, 0, 50);

        return $this;
    }
}
