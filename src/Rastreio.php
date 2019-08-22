<?php
namespace Eduardokum\CorreiosPhp;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;

class Rastreio extends Correios
{
    public function __construct(ConfigContract $config = null, $type = 'curl')
    {
        parent::__construct($config, $type);
        $this->setWs($this->getWs('rastreamento'));

        if ($this->getConfig()->getEnvironment() == 'testing') {
            $this->getConfig()->setUserRastro('ECT');
            $this->getConfig()->setPasswordRastro('SRO');
        }
    }

    /**
     * @param array $codes
     *
     * @return \stdClass
     */
    public function rastreamento(array $codes)
    {
        $request = '<res:buscaEventosLista>';
        $request .= sprintf('<usuario>%s</usuario>', $this->getConfig()->getUserRastro());
        $request .= sprintf('<senha>%s</senha>', $this->getConfig()->getPasswordRastro());
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
        $actions = [
            'curl' => 'buscaEventosLista',
            'native' => 'buscaEventosLista',
        ];

        $result = $this->getSoap()->send($this->url(), $actions, $request, $namespaces);
        $result = $result->return;
        $result->objeto = is_array($result->objeto) ? $result->objeto : [$result->objeto];
        foreach($result->objeto as $objeto) {
            $objeto->evento = isset($objeto->evento)
                ? is_array($objeto->evento) ? $objeto->evento : [$objeto->evento]
                : [];
        }
        return $result;
    }
}
