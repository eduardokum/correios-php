<?php
namespace Eduardokum\CorreiosPhp\Contracts\Config;

use Eduardokum\CorreiosPhp\Entities\Sender;

interface Config
{
    public function getEnvironment();

    public function getCNPJ();
    public function setCNPJ($cnpj);

    public function getUser();
    public function setUser($user);

    public function getPassword();
    public function setPassword($password);

    public function getUserRastro();
    public function setUserRastro($user);

    public function getPasswordRastro();
    public function setPasswordRastro($password);

    public function getAdministrativeCode();
    public function setAdministrativeCode($administrativecode);

    public function getContract();
    public function setContract($contract);

    public function getPostCard();
    public function setPostCard($postcard);

    public function getDirection();
    public function setDirection($direction);

    /**
     * @return Sender
     */
    public function getSender();
    public function setSender(Sender $sender);
}
