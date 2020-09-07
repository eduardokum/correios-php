<?php
namespace Eduardokum\CorreiosPhp\Entities;

use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;
use Eduardokum\CorreiosPhp\Traits\MagicTrait;

class PostalObjectReverse
{
    use MagicTrait;

    const TYPE_POST_AUTHORIZATION = 'A';
    const TYPE_HOME_WITHDRAWAL_REQUIRED = 'C';
    const TYPE_HOME_WITHDRAWAL_NOT_REQUIRED = 'CA';

    private $tag;
    private $tagDv;
    /**
     * A - Autorização de Postagem
     * C - Coleta domiciliária
     * CA - Coleta domiciliar
     *
     * @var string
     */
    private $type = self::TYPE_POST_AUTHORIZATION;
    private $pickupNumber = null;
    private $id = '';
    private $additionalService = ['025' => '025'];
    private $valueDeclared = 0;
    private $recipient;
    private $recipientDocument;
    private $description;
    private $invoiceNumber;

    public function __construct()
    {
        $this->setRecipient(new Recipient);
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return mixed
     */
    public function getTagDv()
    {
        return $this->tagDv;
    }

    /**
     * @param mixed $tag
     *
     * @return PostalObjectReverse
     */
    public function setTag($tag)
    {
        $tags = PostalObject::normalizeTag($tag);
        $this->tag = $tags['tag'];
        $this->tagDv = $tags['tagDv'];

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return PostalObjectReverse
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getPickupNumber()
    {
        return $this->pickupNumber;
    }

    /**
     * @param int $pickupNumber
     *
     * @return PostalObjectReverse
     */
    public function setPickupNumber(int $pickupNumber)
    {
        $this->pickupNumber = $pickupNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return PostalObjectReverse
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValueDeclared()
    {
        return $this->valueDeclared;
    }

    /**
     * @param mixed $valueDeclared
     *
     * @return PostalObjectReverse
     */
    public function setValueDeclared($valueDeclared)
    {
        $this->valueDeclared = $valueDeclared;

        return $this;
    }

    /**
     * @return $this
     */
    public function addServiceOwnHand()
    {
        $this->additionalService['002'] = '002';
        return $this;
    }

    /**
     * @return $this
     */
    public function addServiceDeclaredValue()
    {
        $this->additionalService['019'] = '019';
        return $this;
    }

    /**
     * @return $this
     */
    public function addServiceNoticeReceipt()
    {
        $this->additionalService['001'] = '001';
        return $this;
    }

    /**
     * @return array
     */
    public function getAdditionalServices()
    {
        return $this->additionalService;
    }

    /**
     * @return Recipient
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param Recipient $recipient
     *
     * @return PostalObjectReverse
     */
    public function setRecipient(Recipient $recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecipientDocument()
    {
        return $this->recipientDocument;
    }

    /**
     * @param string $recipientDocument
     *
     * @return PostalObjectReverse
     */
    public function setRecipientDocument($recipientDocument)
    {
        $this->recipientDocument = $recipientDocument;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return PostalObjectReverse
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @param mixed $invoiceNumber
     *
     * @return PostalObjectReverse
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }
}
