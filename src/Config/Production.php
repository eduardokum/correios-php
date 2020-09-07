<?php
namespace Eduardokum\CorreiosPhp\Config;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;

class Production extends Config implements ConfigContract
{
    public function __construct()
    {
        parent::__construct();
        $this->environment = 'production';
    }
}
