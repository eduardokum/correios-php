<?php
namespace Eduardokum\CorreiosPhp\Config;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Entities\Sender;

class Homologacao extends Config implements ConfigContract
{
    public function __construct()
    {
        parent::__construct();
        $this->environment = 'homologacao';
        $this->cnpj = '34028316000103';
        $this->user = null;
        $this->password = null;
        $this->administrativeCode = '08082650';
        $this->contract = '9912208555';
        $this->postCard = '0057018901';
        $this->serviceCode = '41076';
        $this->direction = '36';

        $this->setSender(Sender::create([
            'name' => 'Empresa Ltda',
            'street' => 'Avenida Central',
            'number' => '2370',
            'complement' => 'sala 1205,12° andar',
            'district' => 'Centro',
            'cep' => '70002900',
            'city' => 'Brasília',
            'state' => 'PR',
            'phone' => '6112345008',
            'mail' => 'cli@mail.com.br',
        ]));
    }
}
