<?php
namespace Eduardokum\CorreiosPhp;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Entities\ETicket;
use Eduardokum\CorreiosPhp\Entities\PostalObject;
use Eduardokum\CorreiosPhp\Entities\PostalObjectReverse;
use Eduardokum\CorreiosPhp\Exception\SoapException;

class Logistica extends Correios
{
    public function __construct(ConfigContract $config = null, $type = 'curl')
    {
        parent::__construct($config, $type);
        $this->setWs($this->getWs('logistica'));

        if ($this->getConfig()->getEnvironment() == 'testing') {
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
        $request .= sprintf('<codigo_servico>%s</codigo_servico>', $ETicket->getServiceCode());
        $request .= sprintf('<cartao>%s</cartao>', $this->getConfig()->getPostCard());
        $request .= '<destinatario>';
        $request .= sprintf('<nome>%s</nome>', $this->getConfig()->getSender()->getName());
        $request .= sprintf('<logradouro>%s</logradouro>', $this->getConfig()->getSender()->getStreet());
        $request .= sprintf('<numero>%s</numero>', $this->getConfig()->getSender()->getNumber());
        $request .= sprintf('<complemento>%s</complemento>', $this->getConfig()->getSender()->getComplement());
        $request .= sprintf('<bairro>%s</bairro>', $this->getConfig()->getSender()->getDistrict());
        $request .= sprintf('<referencia>%s</referencia>', '');
        $request .= sprintf('<cidade>%s</cidade>', $this->getConfig()->getSender()->getCity());
        $request .= sprintf('<uf>%s</uf>', $this->getConfig()->getSender()->getState());
        $request .= sprintf('<cep>%s</cep>', $this->getConfig()->getSender()->getCep());
        $request .= sprintf('<telefone>%s</telefone>', $phone = $this->getConfig()->getSender()->getPhone());
        $request .= sprintf('<ddd>%s</ddd>', (strlen($phone) > 9 ? substr($phone, 0, 2) : ''));
        $request .= sprintf('<email>%s</email>', $this->getConfig()->getSender()->getMail());
        $request .= '</destinatario>';
        $request .= $ETicket->save();
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
     * @param ETicket $ETicket
     *
     * @return \stdClass
     */
    public function solicitarPostagemSimultanea(ETicket $ETicket)
    {
        $request = '<cli:solicitarPostagemSimultanea>';
        $request .= sprintf('<codAdministrativo>%s</codAdministrativo>', $this->getConfig()->getAdministrativeCode());
        $request .= sprintf('<codigo_servico>%s</codigo_servico>', $ETicket->getServiceCode());
        $request .= sprintf('<cartao>%s</cartao>', $this->getConfig()->getPostCard());
        $request .= '<destinatario>';
        $request .= sprintf('<nome>%s</nome>', $this->getConfig()->getSender()->getName());
        $request .= sprintf('<logradouro>%s</logradouro>', $this->getConfig()->getSender()->getStreet());
        $request .= sprintf('<numero>%s</numero>', $this->getConfig()->getSender()->getNumber());
        $request .= sprintf('<complemento>%s</complemento>', $this->getConfig()->getSender()->getComplement());
        $request .= sprintf('<bairro>%s</bairro>', $this->getConfig()->getSender()->getDistrict());
        $request .= sprintf('<referencia>%s</referencia>', '');
        $request .= sprintf('<cidade>%s</cidade>', $this->getConfig()->getSender()->getCity());
        $request .= sprintf('<uf>%s</uf>', $this->getConfig()->getSender()->getState());
        $request .= sprintf('<cep>%s</cep>', $this->getConfig()->getSender()->getCep());
        $request .= sprintf('<telefone>%s</telefone>', $phone = $this->getConfig()->getSender()->getPhone());
        $request .= sprintf('<ddd>%s</ddd>', (strlen($phone) > 9 ? substr($phone, 0, 2) : ''));
        $request .= sprintf('<email>%s</email>', $this->getConfig()->getSender()->getMail());
        $request .= '</destinatario>';
        $request .= $ETicket->save(true);
        $request .= '</cli:solicitarPostagemSimultanea>';
        $namespaces = [
            'xmlns:cli' => 'http://service.logisticareversa.correios.com.br/',
        ];
        $actions = [
            'curl' => null,
            'native' => 'solicitarPostagemSimultanea',
        ];
        $auth = [
            'user' => $this->getConfig()->getUser(),
            'password' => $this->getConfig()->getPassword(),
        ];

        $result = $this->getSoap()->send($this->url(), $actions, $request, $namespaces, $auth);
        return $result->solicitarPostagemSimultanea;
    }

    /**
     * @param        $pickupNumber
     * @param string $type
     *
     * @return \stdClass
     */
    public function cancelarPedido($pickupNumber, $type = PostalObjectReverse::TYPE_POST_AUTHORIZATION)
    {
        $request = '<cli:cancelarPedido>';
        $request .= sprintf('<codAdministrativo>%s</codAdministrativo>', $this->getConfig()->getAdministrativeCode());
        $request .= sprintf('<numeroPedido>%s</numeroPedido>', $pickupNumber);
        $request .= sprintf('<tipo>%s</tipo>', $type);
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

    /**
     * @param        $pickupNumber
     * @param string $type
     * @param string $searchType U - Buscar somente o último status do pedido; H - Buscar todos os status do pedido.
     *
     * @return \stdClass
     */
    public function acompanharPedido($pickupNumber, $type = PostalObjectReverse::TYPE_POST_AUTHORIZATION, $searchType = 'H')
    {
        $request = '<cli:acompanharPedido>';
        $request .= sprintf('<codAdministrativo>%s</codAdministrativo>', $this->getConfig()->getAdministrativeCode());
        $request .= sprintf('<tipoBusca>%s</tipoBusca>', $searchType);
        $request .= sprintf('<tipoSolicitacao>%s</tipoSolicitacao>', $type);
        $request .= sprintf('<numeroPedido>%s</numeroPedido>', $pickupNumber);
        $request .= '</cli:acompanharPedido>';
        $namespaces = [
            'xmlns:cli' => 'http://service.logisticareversa.correios.com.br/',
        ];
        $actions = [
            'curl' => null,
            'native' => 'acompanharPedido',
        ];
        $auth = [
            'user' => $this->getConfig()->getUser(),
            'password' => $this->getConfig()->getPassword(),
        ];

        $result = $this->getSoap()->send($this->url(), $actions, $request, $namespaces, $auth);
        return $result->acompanharPedido;
    }

    /**
     * @param \DateTime|null $date
     * @param string         $type
     *
     * @return \stdClass
     */
    public function acompanharPedidoPorData(\DateTime $date = null, $type = PostalObjectReverse::TYPE_POST_AUTHORIZATION)
    {
        if (is_null($date)) {
            $date = new \DateTime();
        }

        $request = '<cli:acompanharPedidoPorData>';
        $request .= sprintf('<codAdministrativo>%s</codAdministrativo>', $this->getConfig()->getAdministrativeCode());
        $request .= sprintf('<%s>%s</%s>', 'data', $date->format('d/m/Y'), 'data');
        $request .= sprintf('<tipoSolicitacao>%s</tipoSolicitacao>', $type);
        $request .= '</cli:acompanharPedidoPorData>';
        $namespaces = [
            'xmlns:cli' => 'http://service.logisticareversa.correios.com.br/',
        ];
        $actions = [
            'curl' => null,
            'native' => 'acompanharPedidoPorData',
        ];
        $auth = [
            'user' => $this->getConfig()->getUser(),
            'password' => $this->getConfig()->getPassword(),
        ];

        $result = $this->getSoap()->send($this->url(), $actions, $request, $namespaces, $auth);
        return $result->acompanharPedidoPorData;
    }

    /**
     * @param string $service LR - PAC; LS - SEDEX; LV - e-SEDEX.
     * @param int    $amount
     * @param string $type    AP - Autorização de Postagem em Agência(e-Ticket); LR - Logística Reversa Domiciliar.
     *
     * @return array
     * @throws SoapException
     */
    public function solicitarRange($service, $amount, $type = 'AP')
    {
        $request = '<cli:solicitarRange>';
        $request .= sprintf('<codAdministrativo>%s</codAdministrativo>', $this->getConfig()->getAdministrativeCode());
        $request .= sprintf('<tipo>%s</tipo>', $type);
        $request .= sprintf('<servico>%s</servico>', $service);
        $request .= sprintf('<quantidade>%s</quantidade>', (int) $amount);
        $request .= '</cli:solicitarRange>';
        $namespaces = [
            'xmlns:cli' => 'http://service.logisticareversa.correios.com.br/',
        ];
        $actions = [
            'curl' => null,
            'native' => 'solicitarRange',
        ];
        $auth = [
            'user' => $this->getConfig()->getUser(),
            'password' => $this->getConfig()->getPassword(),
        ];

        $result = $this->getSoap()->send($this->url(), $actions, $request, $namespaces, $auth);
        $solicitarRange = $result->solicitarRange;
        if ($solicitarRange->cod_erro > 0) {
            throw new SoapException($solicitarRange->msg_erro);
        }

        $first = $result->solicitarRange->faixa_inicial;
        $last = $result->solicitarRange->faixa_final;
        for ($i = $first; $i <= $last; $i++) {
            $result[$i] = PostalObject::calculateDv($i);
        }

        return $result;
    }
}
