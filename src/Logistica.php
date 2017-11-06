<?php
namespace Eduardokum\CorreiosPhp;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Entities\ETicket;
use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;

class Logistica extends Correios
{
    public function __construct(ConfigContract $config = null, $type = 'curl')
    {
        parent::__construct($config, $type);
        $this->setWs($this->getWs('logistica'));

        if ($this->getConfig()->getEnvironment() == 'homologacao') {
            $this->getConfig()->setUser('empresacws');
            $this->getConfig()->setPassword('123456');
        }
    }

    /**
     * @return \stdClass
     */
    public function sobreWebService()
    {
        $request = '<cli:sobreWebService></cli:sobreWebService>';
        $namespaces = [
            'xmlns:cli' => 'http://service.logisticareversa.correios.com.br/',
        ];
        $actions = [
            'curl' => null,
            'native' => 'sobreWebService',
        ];
        $auth = [
            'user' => $this->getConfig()->getUser(),
            'password' => $this->getConfig()->getPassword(),
        ];

        $result = $this->getSoap()->send($this->url(), $actions, $request, $namespaces, $auth);
        return $result->sobreWebService;
    }

    /**
     * @param ETicket $ETicket
     *
     * @return \stdClass
     */
    public function solicitarPostagemReversa(ETicket $ETicket)
    {
        $request = '<cli:solicitarPostagemReversa>';
        $request .= sprintf('<codAdministrativo>%s</codAdministrativo>', $this->getConfig()->getAdministrativeCode());
        $request .= sprintf('<codigo_servico>%s</codigo_servico>', $this->getConfig()->getServiceCode());
        $request .= sprintf('<cartao>%s</cartao>', $this->getConfig()->getPostCard());
        $request .= '</cli:solicitarPostagemReversa>';
        $namespaces = [
            'xmlns:cli' => 'http://service.logisticareversa.correios.com.br/',
        ];
        $actions = [
            'curl' => null,
            'native' => 'solicitarPostagemReversa',
        ];
        $auth = [
            'user' => $this->getConfig()->getUser(),
            'password' => $this->getConfig()->getPassword(),
        ];

        $result = $this->getSoap()->send($this->url(), $actions, $request, $namespaces, $auth);
        return $result->solicitarPostagemReversa;
    }

    /**
     * @param $ETicket
     *
     * @return \stdClass
     */
    public function cancelarPedido($ETicket)
    {
        $ETicketNumber = false;
        if ($ETicket instanceof ETicket) {
            $ETicketNumber = $ETicket->getAutNumber();
        }
        if (is_numeric($ETicket)) {
            $ETicketNumber = $ETicket;
        }

        if (!$ETicketNumber) {
            throw new InvalidArgumentException('Invalid eticket');
        }

        $request = '<cli:cancelarPedido>';
        $request .= sprintf('<codAdministrativo>%s</codAdministrativo>', $this->getConfig()->getAdministrativeCode());
        $request .= sprintf('<numeroPedido>%s</numeroPedido>', $ETicketNumber);
        $request .= sprintf('<cartao>%s</cartao>', $this->getConfig()->getPostCard());
        $request .= '</cli:cancelarPedido>';
        $namespaces = [
            'xmlns:cli' => 'http://service.logisticareversa.correios.com.br/',
        ];
        $actions = [
            'curl' => null,
            'native' => 'cancelarPedido',
        ];
        $auth = [
            'user' => $this->getConfig()->getUser(),
            'password' => $this->getConfig()->getPassword(),
        ];

        $result = $this->getSoap()->send($this->url(), $actions, $request, $namespaces, $auth);
        return $result->cancelarPedido;
    }
}
