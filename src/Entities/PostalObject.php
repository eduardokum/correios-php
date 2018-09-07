<?php
namespace Eduardokum\CorreiosPhp\Entities;

use Eduardokum\CorreiosPhp\Contracts\Render\Printable as PrintableContract;
use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;
use Eduardokum\CorreiosPhp\Traits\MagicTrait;

class PostalObject implements PrintableContract
{
    use MagicTrait;

    private $tag;
    private $tagDv;
    private $service;
    private $cubing;
    private $weight;
    private $postalUserCode;
    private $costCenter;
    private $orderNumber;
    private $invoiceNumber;
    private $invoiceSeries;
    private $invoiceValue;
    private $description;
    private $valueCharge;
    private $valueDeclared = 0;
    private $length;
    private $height;
    private $width;
    private $diameter;
    private $additionalService = ['025' => '025'];
    private $type = '002';
    private $recipient;
    private $model = PrintableContract::MODEL_SINGLE;
    private $size = PrintableContract::SIZE_SMALL;

    public function __construct()
    {
        $this->setRecipient(new Recipient);
    }

    /**
     * @return $this
     */
    public function typeLetter()
    {
        $this->type = '001';
        return $this;
    }

    /**
     * @return $this
     */
    public function typePackage()
    {
        $this->type = '002';
        return $this;
    }

    /**
     * @return $this
     */
    public function typeRoll()
    {
        $this->type = '003';
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
     * @return PostalObject
     */
    public function setTag($tag)
    {
        $tags = self::normalizeTag($tag);
        $this->tag = $tags['tag'];
        $this->tagDv = $tags['tagDv'];

        return $this;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     *
     * @return PostalObject
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCubing()
    {
        return $this->cubing;
    }

    /**
     * @param mixed $cubing
     *
     * @return PostalObject
     */
    public function setCubing($cubing)
    {
        $this->cubing = $cubing;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     *
     * @return PostalObject
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
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
     * @return PostalObject
     */
    public function setRecipient(Recipient $recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostalUserCode()
    {
        return $this->postalUserCode;
    }

    /**
     * @param mixed $postalUserCode
     *
     * @return PostalObject
     */
    public function setPostalUserCode($postalUserCode)
    {
        $this->postalUserCode = $postalUserCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCostCenter()
    {
        return $this->costCenter;
    }

    /**
     * @param mixed $costCenter
     *
     * @return PostalObject
     */
    public function setCostCenter($costCenter)
    {
        $this->costCenter = $costCenter;

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
     * @return PostalObject
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param mixed $orderNumber
     *
     * @return PostalObject
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvoiceSeries()
    {
        return $this->invoiceSeries;
    }

    /**
     * @param mixed $invoiceSeries
     *
     * @return PostalObject
     */
    public function setInvoiceSeries($invoiceSeries)
    {
        $this->invoiceSeries = $invoiceSeries;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvoiceValue()
    {
        return $this->invoiceValue;
    }

    /**
     * @param mixed $invoiceValue
     *
     * @return PostalObject
     */
    public function setInvoiceValue($invoiceValue)
    {
        $this->invoiceValue = $invoiceValue;

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
     * @return PostalObject
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValueCharge()
    {
        return $this->valueCharge;
    }

    /**
     * @param mixed $valueCharge
     *
     * @return PostalObject
     */
    public function setValueCharged($valueCharge)
    {
        $this->valueCharge = $valueCharge;

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
     * @return PostalObject
     */
    public function setValueDeclared($valueDeclared)
    {
        $this->valueDeclared = $valueDeclared;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     *
     * @return PostalObject
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     *
     * @return PostalObject
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     *
     * @return PostalObject
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDiameter()
    {
        return $this->diameter;
    }

    /**
     * @param mixed $diameter
     *
     * @return PostalObject
     */
    public function setDiameter($diameter)
    {
        $this->diameter = $diameter;

        return $this;
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
        return [$this];
    }

    /**
     * @param $tag
     *
     * @return string
     */
    public static function calculateDv($tag)
    {
        if (!preg_match('/(?<prefix>\D{2})?\s?(?<number>\d{8})\s?(?<sufix>\D{2})?/', $tag, $matches)) {
            throw new InvalidArgumentException("Invalid tag '$tag'");
        }
        $matches += ['prefix' => null, 'sufix' => ''];
        $chars = str_split($matches['number'], 1);
        $sums = str_split("86423597", 1);
        $rest = array_reduce($chars, function (&$sum, $a) use (&$sums) {
                return $sum += $a * array_shift($sums);
            }) % 11;
        $dv = 5;
        if ($rest != 0) {
            $dv = $rest == 1 ? 0 : 11 - $rest;
        }
        return vsprintf('%s%s%s', [
            $matches['prefix'],
            $matches['number'] . $dv,
            $matches['sufix']
        ]);
    }

    /**
     * @param $tag
     *
     * @return array
     */
    public static function normalizeTag($tag)
    {
        if (!preg_match('/(?<prefix>\D{2})?\s?(?<number>\d{8,9})\s?(?<sufix>\D{2})?/', $tag, $matches)) {
            throw new InvalidArgumentException(sprintf("Tag '%s' is not acceptable.", $tag));
        }

        $matches = array_filter($matches);
        if (strlen($matches['number']) == 8) {
            return [
                'tag' => $tag,
                'tagDv' => self::calculateDv($tag)
            ];
        }

        return [
            'tag' => vsprintf('%s%s%s', [
                $matches['prefix'],
                substr($matches['number'], 0, -1),
                $matches['sufix'],
            ]),
            'tagDv' => $tag,
        ];
    }
}
