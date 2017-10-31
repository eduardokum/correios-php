<?php
namespace Eduardokum\CorreiosPhp;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Entity\PostalObject;
use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;
use Eduardokum\CorreiosPhp\Service\Plp;

class Sigep extends Correios
{
    public function __construct(ConfigContract $config = null, $type = 'curl')
    {
        parent::__construct($config, $type);
        $this->setWs($this->getWs('sigep'));
    }

    /**
     * @param $service
     * @param $cepFrom
     * @param $cepTo
     *
     * @return boolean
     */
    public function statusServico($service, $cepFrom, $cepTo)
    {
        $request = '<cli:verificaDisponibilidadeServico>';
        $request .= sprintf('<codAdministrativo>%s</codAdministrativo>', $this->getConfig()->getAdministrativeCode());
        $request .= sprintf('<numeroServico>%s</numeroServico>', $service);
        $request .= sprintf('<cepOrigem>%08s</cepOrigem>', preg_replace('/[^0-9]/', '', $cepFrom));
        $request .= sprintf('<cepDestino>%08s</cepDestino>', preg_replace('/[^0-9]/', '', $cepTo));
        $request .= sprintf('<usuario>%s</usuario>', $this->getConfig()->getUser());
        $request .= sprintf('<senha>%s</senha>', $this->getConfig()->getPassword());
        $request .= '</cli:verificaDisponibilidadeServico>';
        $namespaces = [
            'xmlns:cli' => 'http://cliente.bean.master.sigep.bsb.correios.com.br/',
        ];

        $result = $this->getSoap()->send($this->url(), null, $request, $namespaces);
        return $result->return;
    }

    /**
     * @return mixed
     */
    public function buscaCliente()
    {
        $request = '<cli:buscaCliente>';
        $request .= sprintf('<idContrato>%s</idContrato>', $this->getConfig()->getContract());
        $request .= sprintf('<idCartaoPostagem>%s</idCartaoPostagem>', $this->getConfig()->getPostCard());
        $request .= sprintf('<usuario>%s</usuario>', $this->getConfig()->getUser());
        $request .= sprintf('<senha>%s</senha>', $this->getConfig()->getPassword());
        $request .= '</cli:buscaCliente>';
        $namespaces = [
            'xmlns:cli' => 'http://cliente.bean.master.sigep.bsb.correios.com.br/',
        ];

        $result = $this->getSoap()->send($this->url(), null, $request, $namespaces);
        return $result->return;
    }

    /**
     * @param $cep
     *
     * @return mixed
     */
    public function consultaCEP($cep)
    {
        $request = '<cli:consultaCEP>';
        $request .= sprintf('<cep>%08s</cep>', preg_replace('/[^0-9]/', '', $cep));
        $request .= '</cli:consultaCEP>';
        $namespaces = [
            'xmlns:cli' => 'http://cliente.bean.master.sigep.bsb.correios.com.br/',
        ];

        $result = $this->getSoap()->send($this->url(), null, $request, $namespaces);
        return $result->return;
    }

    /**
     * @return string
     */
    public function getStatusCartaoPostagem()
    {
        $request = '<cli:getStatusCartaoPostagem>';
        $request .= sprintf('<numeroCartaoPostagem>%s</numeroCartaoPostagem>', $this->getConfig()->getPostCard());
        $request .= sprintf('<usuario>%s</usuario>', $this->getConfig()->getUser());
        $request .= sprintf('<senha>%s</senha>', $this->getConfig()->getPassword());
        $request .= '</cli:getStatusCartaoPostagem>';
        $namespaces = [
            'xmlns:cli' => 'http://cliente.bean.master.sigep.bsb.correios.com.br/',
        ];

        $result = $this->getSoap()->send($this->url(), null, $request, $namespaces);
        return $result->return;
    }

    /**
     * @param $service
     * @param $amount
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function solicitaEtiquetas($service, $amount)
    {
        $request = '<cli:solicitaEtiquetas>';
        $request .= sprintf('<tipoDestinatario>%s</tipoDestinatario>', 'C');
        $request .= sprintf('<identificador>%s</identificador>', $this->getConfig()->getCNPJ());
        $request .= sprintf('<idServico>%s</idServico>', $service);
        $request .= sprintf('<qtdEtiquetas>%d</qtdEtiquetas>', $amount);
        $request .= sprintf('<usuario>%s</usuario>', $this->getConfig()->getUser());
        $request .= sprintf('<senha>%s</senha>', $this->getConfig()->getPassword());
        $request .= '</cli:solicitaEtiquetas>';
        $namespaces = [
            'xmlns:cli' => 'http://cliente.bean.master.sigep.bsb.correios.com.br/',
        ];

        $result = $this->getSoap()->send($this->url(), null, $request, $namespaces);
        $tags = explode(',', $result->return);
        $result = [];
        foreach ($tags as $i => $tag) {
            $result[$tag] = PostalObject::calculateDv($tag);
        }

        return $result;
    }

    /**
     * @param Plp $plp
     *
     * @return array
     */
    public function fechaPlpVariosServicos(Plp $plp)
    {
        $request = '<cli:fechaPlpVariosServicos>';
        $request .= sprintf('<xml>%s</xml>', $plp->save($this->getConfig()));
        $request .= sprintf('<idPlpCliente>%s</idPlpCliente>', '1');
        $request .= sprintf('<cartaoPostagem>%s</cartaoPostagem>', $this->getConfig()->getPostCard());
        foreach ($plp->getTags() as $tag) {
            $request .= sprintf('<listaEtiquetas>%s</listaEtiquetas>', $tag);
        }
        $request .= sprintf('<usuario>%s</usuario>', $this->getConfig()->getUser());
        $request .= sprintf('<senha>%s</senha>', $this->getConfig()->getPassword());
        $request .= '</cli:fechaPlpVariosServicos>';
        $namespaces = [
            'xmlns:cli' => 'http://cliente.bean.master.sigep.bsb.correios.com.br/',
        ];

        $result = $this->getSoap()->send($this->url(), null, $request, $namespaces);
        $tags = explode(',', $result->return);
        $result = [];
        foreach ($tags as $i => $tag) {
            $result[$tag] = PostalObject::calculateDv($tag);
        }

        return $result;
    }
}