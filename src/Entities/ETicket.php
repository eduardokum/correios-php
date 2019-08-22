<?php
namespace Eduardokum\CorreiosPhp\Entities;

use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;

class ETicket
{
    /**
     * @var int
     */
    private $id = 1;

    /**
     * @var \DOMDocument
     */
    private $dom;

    /**
     * @var array
     */
    private $objects = [];

    /**
     * @var int
     */
    private $serviceCode;

    /**
     * ETicket constructor.
     *
     * @param $id
     * @param $serviceCode
     */
    public function __construct($id, $serviceCode)
    {
        $this->id = $id;
        $this->serviceCode = $serviceCode;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getServiceCode()
    {
        return $this->serviceCode;
    }

    /**
     * @param PostalObjectReverse $postalObjectReverse
     */
    public function addObject(PostalObjectReverse $postalObjectReverse)
    {

        if (count($this->objects) >= 10) {
            throw new InvalidArgumentException('Too many tags added. Max 10.');
        }

        $this->objects[] = $postalObjectReverse;
    }

    /**
     * @param bool $simultaneous
     *
     * @return string
     */
    public function save($simultaneous = false)
    {
        $this->dom = new \DOMDocument('1.0', 'UTF-8');
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = false;

        foreach ($this->objects as $object) {
            /** @var PostalObjectReverse $object */
            $coletas_solicitadas = $this->dom->createElement('coletas_solicitadas');
            $coletas_solicitadas->appendChild($this->dom->createElement('tipo', $object->getType()));
            $coletas_solicitadas->appendChild($this->dom->createElement('id_cliente', $object->getInvoiceNumber()));
            $coletas_solicitadas->appendChild($this->dom->createElement('valor_declarado', $object->getValueDeclared()));
            $coletas_solicitadas->appendChild($this->dom->createElement('descricao', ''));
            $coletas_solicitadas->appendChild($this->dom->createElement('cklist'));
            $coletas_solicitadas->appendChild($this->dom->createElement('documento', $object->getRecipientDocument()));
            $coletas_solicitadas->appendChild($this->remetente($object));
            if ($simultaneous) {
                if (!$tagDv = $object->getTagDv()) {
                    throw new InvalidArgumentException('Object has no tag');
                }
                $coletas_solicitadas->appendChild($this->dom->createElement('obj', $tagDv));
            } else {
                $coletas_solicitadas->appendChild($this->dom->createElement('ar', (int) array_key_exists('001', $object->getAdditionalServices())));
                $coletas_solicitadas->appendChild($this->dom->createElement('numero', $object->getPickupNumber()));
                $coletas_solicitadas->appendChild($this->dom->createElement('ag'));
                $coletas_solicitadas->appendChild($this->dom->createElement('cartao'));
                $coletas_solicitadas->appendChild($this->dom->createElement('servico_adicional'));
                $coletas_solicitadas->appendChild($this->objCol($object));
            }

            $this->dom->appendChild($coletas_solicitadas);
        }

        return preg_replace('/\<\?xml.+?\?\>|[\n\t\r]/', '', $this->dom->saveXML($this->dom));
    }

    /**
     * @param PostalObjectReverse $postalObjectReverse
     *
     * @return \DOMElement
     */
    private function remetente(PostalObjectReverse $postalObjectReverse)
    {
        $remetente = $this->dom->createElement('remetente');
        $remetente->appendChild($this->dom->createElement('nome', $postalObjectReverse->getRecipient()->getName()));
        $remetente->appendChild($this->dom->createElement('logradouro', $postalObjectReverse->getRecipient()->getStreet()));
        $remetente->appendChild($this->dom->createElement('numero', $postalObjectReverse->getRecipient()->getNumber()));
        $remetente->appendChild($this->dom->createElement('complemento', $postalObjectReverse->getRecipient()->getComplement()));
        $remetente->appendChild($this->dom->createElement('bairro', $postalObjectReverse->getRecipient()->getDistrict()));
        $remetente->appendChild($this->dom->createElement('cep', $postalObjectReverse->getRecipient()->getCep()));
        $remetente->appendChild($this->dom->createElement('referencia', ''));
        $remetente->appendChild($this->dom->createElement('cidade', $postalObjectReverse->getRecipient()->getCity()));
        $remetente->appendChild($this->dom->createElement('uf', $postalObjectReverse->getRecipient()->getState()));
        $remetente->appendChild($this->dom->createElement('telefone', $phone = $postalObjectReverse->getRecipient()->getPhone()));
        $remetente->appendChild($this->dom->createElement('ddd', (strlen($phone) > 9 ? substr($phone, 0, 2) : '')));
        $remetente->appendChild($this->dom->createElement('email', $postalObjectReverse->getRecipient()->getMail()));
        $remetente->appendChild($this->dom->createElement('celular', ''));
        $remetente->appendChild($this->dom->createElement('ddd_celular', ''));
        $remetente->appendChild($this->dom->createElement('sms', 'N'));
        $remetente->appendChild($this->dom->createElement('identificacao', ''));
        return $remetente;
    }

    /**
     * @param PostalObjectReverse $postalObjectReverse
     *
     * @return \DOMElement
     */
    private function objCol(PostalObjectReverse $postalObjectReverse)
    {
        $remetente = $this->dom->createElement('obj_col');
        $remetente->appendChild($this->dom->createElement('item', '1'));
        $remetente->appendChild($this->dom->createElement('desc', $postalObjectReverse->getDescription()));
        $remetente->appendChild($this->dom->createElement('entrega', ''));
        $remetente->appendChild($this->dom->createElement('num', ''));
        $remetente->appendChild($this->dom->createElement('id', $this->getId()));
        return $remetente;
    }
}
