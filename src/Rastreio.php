<?php
namespace Eduardokum\CorreiosPhp;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;

class Rastreio extends Correios
{
    public function __construct(ConfigContract $config = null, $type = 'curl')
    {
        parent::__construct($config, $type);
        $this->setWs($this->getWs('rastreamento'));
    }

    /**
     * @param array $codes
     *
     * @return mixed
     */
    public function rastreamento(array $codes)
    {
        $user = $this->getConfig()->getEnvironment() == 'homologacao' ? 'ECT' : $this->getConfig()->getUser();
        $pass = $this->getConfig()->getEnvironment() == 'homologacao' ? 'SRO' : $this->getConfig()->getPassword();

        $request = '<res:buscaEventosLista>';
        $request .= sprintf('<usuario>%s</usuario>', $user);
        $request .= sprintf('<senha>%s</senha>', $pass);
        $request .= sprintf('<tipo>%s</tipo>', 'L');
        $request .= sprintf('<resultado>%s</resultado>', 'T');
        $request .= sprintf('<lingua>%s</lingua>', '101');
        foreach ($codes as $c) {
            $request .= sprintf('<objetos>%s</objetos>', $c);
        }
        $request .= '</res:buscaEventosLista>';
        $namespaces = [
            'xmlns:res' => 'http://resource.webservice.correios.com.br/',
        ];
        $result = $this->getSoap()->send($this->url(), 'buscaEventosLista', $request, $namespaces);
        return $result->return;
    }
}