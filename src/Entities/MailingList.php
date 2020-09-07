<?php
namespace Eduardokum\CorreiosPhp\Entities;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Contracts\Render\Printable as PrintableContract;
use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;
use Eduardokum\CorreiosPhp\Validator;

class MailingList implements PrintableContract
{

    /**
     * @var int
     */
    private $id = 1;

    /**
     * MailingList constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @var PostalObject[]
     */
    private $objects = [];

    /**
     * @var array
     */
    private $tags = [];

    /**
     * @var  \DOMDocument
     */
    private $dom;

    /**
     * @var string
     */
    private $model = PrintableContract::MODEL_MULTIPLE;

    /**
     * @var string
     */
    private $size = PrintableContract::SIZE_SMALL;

    /**
     * @param PostalObject $postalObject
     */
    public function addObject(PostalObject $postalObject)
    {
        if (in_array($postalObject->getTag(), $this->tags)) {
            throw new InvalidArgumentException(sprintf("Tag '%s' already added", $postalObject->getTagDv()));
        }

        $this->tags[] = $postalObject->getTag();
        $this->objects[] = $postalObject;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param ConfigContract $config
     *
     * @return string
     * @throws \Exception
     */
    public function save(ConfigContract $config)
    {
        $this->dom = new \DOMDocument('1.0', 'ISO-8859-1');
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = false;

        $correioslog = $this->dom->createElement('correioslog');
        $correioslog->appendChild($this->dom->createElement('tipo_arquivo', 'Postagem'));
        $correioslog->appendChild($this->dom->createElement('versao_arquivo', '2.3'));
        $correioslog->appendChild($this->plp($config));
        $correioslog->appendChild($this->remetente($config));
        $correioslog->appendChild($this->dom->createElement('forma_pagamento'));

        foreach ($this->objects as $object) {
            $correioslog->appendChild($this->objectPostal($object));
        }

        $this->dom->appendChild($correioslog);
        $xml = utf8_encode($this->dom->saveXML());
        Validator::isValid($xml, realpath(CORREIOS_PHP_BASE . '/storage/schemes/') . '/plp.xsd');
        return htmlentities($xml);
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param $model
     *
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return array
     */
    public function toPrint()
    {
        return $this->objects;
    }

    /**
     * @param ConfigContract $config
     *
     * @return mixed
     */
    private function plp(ConfigContract $config)
    {
        $plp = $this->dom->createElement('plp');
        $plp->appendChild($this->dom->createElement('id_plp'));
        $plp->appendChild($this->dom->createElement('valor_global'));
        $plp->appendChild($this->dom->createElement('mcu_unidade_postagem'));
        $plp->appendChild($this->dom->createElement('nome_unidade_postagem'));
        $plp->appendChild($this->dom->createElement('cartao_postagem', $config->getPostCard()));

        return $plp;
    }

    /**
     * @param ConfigContract $config
     *
     * @return mixed
     */
    private function remetente(ConfigContract $config)
    {
        $remetente = $this->dom->createElement('remetente');
        $remetente->appendChild(
            $this->dom->createElement('numero_contrato', $config->getContract())
        );
        $remetente->appendChild(
            $this->dom->createElement('numero_diretoria', $config->getDirection())
        );
        $remetente->appendChild(
            $this->dom->createElement('codigo_administrativo', $config->getAdministrativeCode())
        );

        $remetente->appendChild(
            $nome_remetente = $this->dom->createElement('nome_remetente')
        );
        $nome_remetente->appendChild($this->dom->createCDATASection($config->getSender()->getName()));

        $remetente->appendChild(
            $logradouro_remetente = $this->dom->createElement('logradouro_remetente')
        );
        $logradouro_remetente->appendChild($this->dom->createCDATASection($config->getSender()->getStreet()));

        $remetente->appendChild(
            $this->dom->createElement('numero_remetente', $config->getSender()->getNumber())
        );

        $remetente->appendChild(
            $complemento_remetente = $this->dom->createElement('complemento_remetente')
        );
        $complemento_remetente->appendChild($this->dom->createCDATASection($config->getSender()->getComplement()));

        $remetente->appendChild(
            $bairro_remetente = $this->dom->createElement('bairro_remetente')
        );
        $bairro_remetente->appendChild($this->dom->createCDATASection($config->getSender()->getDistrict()));

        $remetente->appendChild(
            $cep_remetente = $this->dom->createElement('cep_remetente')
        );
        $cep_remetente->appendChild($this->dom->createCDATASection($config->getSender()->getCep()));

        $remetente->appendChild(
            $cidade_remetente = $this->dom->createElement('cidade_remetente')
        );
        $cidade_remetente->appendChild($this->dom->createCDATASection($config->getSender()->getCity()));

        $remetente->appendChild(
            $this->dom->createElement('uf_remetente', $config->getSender()->getState())
        );

        $remetente->appendChild(
            $telefone_remetente = $this->dom->createElement('telefone_remetente')
        );
        $telefone_remetente->appendChild($this->dom->createCDATASection($config->getSender()->getPhone()));

        $remetente->appendChild(
            $fax_remetente = $this->dom->createElement('fax_remetente')
        );
        $fax_remetente->appendChild($this->dom->createCDATASection(''));

        $remetente->appendChild(
            $email_remetente = $this->dom->createElement('email_remetente')
        );
        $email_remetente->appendChild($this->dom->createCDATASection($config->getSender()->getMail()));

        $remetente->appendChild(
            $email_remetente = $this->dom->createElement('celular_remetente')
        );
        $email_remetente->appendChild($this->dom->createCDATASection($config->getSender()->getCellphone()));

        return $remetente;
    }

    /**
     * @param PostalObject $object
     *
     * @return \DOMElement
     */
    private function objectPostal(PostalObject $object)
    {
        $objeto_postal = $this->dom->createElement('objeto_postal');
        $objeto_postal->appendChild($this->dom->createElement('numero_etiqueta', $object->getTagDv()));
        $objeto_postal->appendChild($this->dom->createElement('codigo_objeto_cliente'));
        $objeto_postal->appendChild($this->dom->createElement('codigo_servico_postagem', $object->getService()));
        $objeto_postal->appendChild($this->dom->createElement('cubagem', $object->getCubing()));
        $objeto_postal->appendChild($this->dom->createElement('peso', $object->getWeight()));
        $objeto_postal->appendChild($this->dom->createElement('rt1'));
        $objeto_postal->appendChild($this->dom->createElement('rt2'));
        $objeto_postal->appendChild($this->destinatario($object));
        $objeto_postal->appendChild($this->nacional($object));
        $objeto_postal->appendChild($this->adicional($object));
        $objeto_postal->appendChild($this->dimensao($object));
        $objeto_postal->appendChild($this->dom->createElement('data_postagem_sara'));
        $objeto_postal->appendChild($this->dom->createElement('status_processamento', '0'));
        $objeto_postal->appendChild($this->dom->createElement('numero_comprovante_postagem'));
        $objeto_postal->appendChild($this->dom->createElement('valor_cobrado'));

        return $objeto_postal;
    }

    /**
     * @param PostalObject $object
     *
     * @return \DOMElement
     */
    private function destinatario(PostalObject $object)
    {
        $destinatario = $this->dom->createElement('destinatario');
        $destinatario->appendChild(
            $nome_destinatario = $this->dom->createElement('nome_destinatario')
        );
        $nome_destinatario->appendChild($this->dom->createCDATASection($object->getRecipient()->getName()));

        $destinatario->appendChild(
            $telefone_destinatario = $this->dom->createElement('telefone_destinatario')
        );
        $telefone_destinatario->appendChild($this->dom->createCDATASection($object->getRecipient()->getPhone()));

        $destinatario->appendChild(
            $celular_destinatario = $this->dom->createElement('celular_destinatario')
        );
        $celular_destinatario->appendChild($this->dom->createCDATASection($object->getRecipient()->getCellphone()));

        $destinatario->appendChild(
            $email_destinatario = $this->dom->createElement('email_destinatario')
        );
        $email_destinatario->appendChild($this->dom->createCDATASection($object->getRecipient()->getMail()));

        $destinatario->appendChild(
            $logradouro_destinatario = $this->dom->createElement('logradouro_destinatario')
        );
        $logradouro_destinatario->appendChild($this->dom->createCDATASection($object->getRecipient()->getStreet()));

        $destinatario->appendChild(
            $complemento_destinatario = $this->dom->createElement('complemento_destinatario')
        );
        $complemento_destinatario->appendChild($this->dom->createCDATASection($object->getRecipient()->getComplement()));

        $destinatario->appendChild($this->dom->createElement(
            'numero_end_destinatario',
            $object->getRecipient()->getNumber()
        ));

        return $destinatario;
    }

    /**
     * @param PostalObject $object
     *
     * @return \DOMElement
     */
    private function nacional(PostalObject $object)
    {
        if ($object->getRecipient()->isNational()) {
            $nacional = $this->dom->createElement('nacional');
            $nacional->appendChild(
                $bairro_destinatario = $this->dom->createElement('bairro_destinatario')
            );
            $bairro_destinatario->appendChild($this->dom->createCDATASection($object->getRecipient()->getDistrict()));
            $nacional->appendChild(
                $cidade_destinatario = $this->dom->createElement('cidade_destinatario')
            );
            $cidade_destinatario->appendChild($this->dom->createCDATASection($object->getRecipient()->getCity()));
            $nacional->appendChild($this->dom->createElement(
                'uf_destinatario',
                $object->getRecipient()->getState()
            ));
            $nacional->appendChild(
                $cep_destinatario = $this->dom->createElement('cep_destinatario')
            );
            $cep_destinatario->appendChild($this->dom->createCDATASection($object->getRecipient()->getCep()));
            $nacional->appendChild($this->dom->createElement(
                'codigo_usuario_postal',
                $object->getPostalUserCode()
            ));
            $nacional->appendChild($this->dom->createElement(
                'centro_custo_cliente',
                $object->getCostCenter()
            ));
            $nacional->appendChild($this->dom->createElement(
                'numero_nota_fiscal',
                $object->getInvoiceNumber()
            ));
            $nacional->appendChild($this->dom->createElement(
                'serie_nota_fiscal',
                $object->getInvoiceSeries()
            ));
            $nacional->appendChild($this->dom->createElement(
                'valor_nota_fiscal',
                $object->getInvoiceValue()
            ));
            $nacional->appendChild($this->dom->createElement('natureza_nota_fiscal'));
            $nacional->appendChild(
                $descricao_objeto = $this->dom->createElement('descricao_objeto')
            );
            $descricao_objeto->appendChild($this->dom->createCDATASection($object->getDescription()));
            $nacional->appendChild($this->dom->createElement(
                'valor_a_cobrar',
                $object->getValueCharge()
            ));
        } else {
            $nacional = $this->dom->createElement('internacional');
        }

        return $nacional;
    }

    /**
     * @param PostalObject $object
     *
     * @return \DOMElement
     */
    private function adicional(PostalObject $object)
    {
        $servico_adicional = $this->dom->createElement('servico_adicional');
        foreach ($object->getAdditionalServices() as $aditional_service) {
            $servico_adicional->appendChild($this->dom->createElement('codigo_servico_adicional', $aditional_service));
        }
        $servico_adicional->appendChild($this->dom->createElement('valor_declarado', $object->getValueDeclared()));
        return $servico_adicional;
    }

    /**
     * @param PostalObject $object
     *
     * @return \DOMElement
     */
    private function dimensao(PostalObject $object)
    {
        $dimensao_objeto = $this->dom->createElement('dimensao_objeto');
        $dimensao_objeto->appendChild($this->dom->createElement('tipo_objeto', $object->getType()));
        $dimensao_objeto->appendChild($this->dom->createElement('dimensao_altura', $object->getHeight()));
        $dimensao_objeto->appendChild($this->dom->createElement('dimensao_largura', $object->getWidth()));
        $dimensao_objeto->appendChild($this->dom->createElement('dimensao_comprimento', $object->getLength()));
        $dimensao_objeto->appendChild($this->dom->createElement('dimensao_diametro', $object->getDiameter() ?: '0'));

        return $dimensao_objeto;
    }
}
