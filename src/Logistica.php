<?php
namespace Eduardokum\CorreiosPhp;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;

class Logistica extends Correios
{
    public function __construct(ConfigContract $config = null, $type = 'curl')
    {
        parent::__construct($config, $type);
        $this->setWs($this->getWs('logistica'));

        if ($this->getConfig()->getEnvironment() == 'homologacao') {
            $this->getConfig()->setUser('60618043');
            $this->getConfig()->setPassword('8o8otn');
        }
    }
}
