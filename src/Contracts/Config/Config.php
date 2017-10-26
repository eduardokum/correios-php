<?php
namespace Eduardokum\CorreiosPhp\Contracts\Config;

interface Config
{
    public function getEnvironment();
    public function getUser();
    public function getPassword();
    public function getAdministrativeCode();
    public function getContract();
}