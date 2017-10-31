<?php
namespace Eduardokum\CorreiosPhp\Contracts\Config;

use Eduardokum\CorreiosPhp\Entity\Sender;

interface Config
{
    public function getEnvironment();
    public function getCNPJ();
    public function getUser();
    public function getPassword();
    public function getAdministrativeCode();
    public function getContract();
    public function getPostCard();
    public function getServiceCode();
    public function getDirection();

    /**
     * @return Sender
     */
    public function getSender();
}