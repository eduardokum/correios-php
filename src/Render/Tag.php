<?php
namespace Eduardokum\CorreiosPhp\Render;

use Eduardokum\CorreiosPhp\Contracts\Render\Printable as PrintableContract;
use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Entities\PostalObject;

class Tag extends Pdf
{
    /**
     * @var PrintableContract
     */
    private $printable;

    /**
     * @var string
     */
    private $model;

    /**
     * @var string
     */
    private $size;

    /**
     * @var int
     */
    private $perPage = 1;

    /**
     * @var array
     */
    private $positions = [
        1 => [
            1 => [0, 0],
        ],
        4 => [
            1 => [1.6, 1.6],
            2 => [106.36 + 1.6, 1.6],
            3 => [1.6, 138.11 + 1.6],
            4 => [106.36 + 1.6, 138.11 + 1.6],
        ],
        6 => [
            1 => [4, 12.7],
            2 => [101.6 + 8, 12.7],
            3 => [4, 84.7 + 12.7],
            4 => [101.6 + 8, 84.7 + 12.7],
            5 => [4, (84.7 * 2) + 12.7],
            6 => [101.6 + 8, (84.7 * 2) + 12.7],
        ]
    ];

    /**
     * @var null
     */
    private $additionalServices = null;

    /**
     * @var null
     */
    private $postalServices = null;

    /**
     * @var array
     */
    private $tagSize = [
        PrintableContract::SIZE_BIG => [106.36, 138.11],
        PrintableContract::SIZE_SMALL => [101.6, 84.7],
    ];

    public function __construct(PrintableContract $printable, ConfigContract $config = null)
    {
        parent::__construct($config);
        $this->additionalServices = json_decode(file_get_contents(CORREIOS_PHP_BASE . '/storage/additional_services.json'));
        $this->postalServices = json_decode(file_get_contents(CORREIOS_PHP_BASE . '/storage/postal_service.json'));
        $this->setPrintable($printable);
        $size =  [215.9, 279.4];

        if ($this->getModel() == PrintableContract::MODEL_SINGLE) {
            $size = $this->getTagSize();
        } else {
            if ($this->getSize() == PrintableContract::SIZE_BIG) {
                $this->perPage = 4;
            }
            if ($this->getSize() == PrintableContract::SIZE_SMALL) {
                $this->perPage = 6;
            }
        }

        $this->tcpdf = new \TCPDF(null, 'mm', $size, true, 'UTF-8');
        $this->tcpdf->setPrintHeader(false);
        $this->tcpdf->setPrintFooter(false);
        $this->tcpdf->SetMargins(0, 0, 0, true);
        $this->tcpdf->SetCreator('Eduardokum\\CorreiosPhp');
        $this->tcpdf->SetAuthor('Eduardokum\\CorreiosPhp');
        $this->tcpdf->SetTitle('Etiquetas');
        $this->tcpdf->SetSubject('Etiquetas');
        $this->tcpdf->SetKeywords('Etiquetas');
        $this->tcpdf->SetAutoPageBreak(false, 0);
        $this->tcpdf->SetFontSize(9);
        $this->tcpdf->SetFont($this->fontRegular, '', 9, '', false);
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @return PrintableContract
     */
    public function getPrintable()
    {
        return $this->printable;
    }

    /**
     * @param PrintableContract $printable
     *
     * @return Tag
     */
    public function setPrintable(PrintableContract $printable)
    {
        $this->printable = $printable;
        $this->model = $printable->getModel();
        $this->size = $printable->getSize();
        return $this;
    }

    /**
     * @param string $output
     *
     * @return string
     */
    public function render($output = 'I')
    {
        $tags = $this->getPrintable()->toPrint();
        $tagsCount = 0;
        foreach ($tags as $tag) {
            $tagsCount++;
            $position = $rest = $tagsCount % $this->getPerPage();
            $position = $position == 0 ? $this->getPerPage() : $position;
            if ($rest === 1 || count($tags) === 1) {
                $this->tcpdf->AddPage();
            }
            $this->tag($tag, $position);
        }
        return $this->tcpdf->Output('etiquetas.pdf', $output);
    }

    /**
     * @param $position
     *
     * @return array
     */
    private function getPositionXY($position)
    {
        return $this->positions[$this->getPerPage()][$position];
    }

    /**
     * @return array
     */
    private function getTagSize()
    {
        return $this->tagSize[$this->getSize()];
    }

    /**
     * @return int
     */
    private function getTagWidth()
    {
        return $this->tagSize[$this->getSize()][0];
    }

    /**
     * @return int
     */
    private function getTagHeight()
    {
        return $this->tagSize[$this->getSize()][1];
    }

    /**
     * @param $tag
     * @param $position
     */
    private function tag(PostalObject $tag, $position)
    {
        list($x, $y) = $this->getPositionXY($position);

        $this->tcpdf->Rect($x, $y, $this->getTagWidth(), $this->getTagHeight());

        $this->tcpdf->SetXY($x, $y);
        $y += $this->padding;
        $x += $this->padding;

        $this->logo($x, $y);
        if ($this->getSize() == PrintableContract::SIZE_BIG) {
            $this->dataMatrix($tag, $x + (($this->getTagWidth() - 25) / 2), $y);
            $this->forwardingSymbol($tag, $x + ($this->getTagWidth() - 22), $y + 5 + $this->padding);
            $this->serviceInfoBig($tag, $x + 5 + $this->padding, $y + 25 + ($this->padding * 2));
            $lastPosition = $this->barcode($tag, $x, $y + 35);
        } else {
            $this->dataMatrix($tag, $x, $y);
            $this->forwardingSymbol($tag, $x + ($this->getTagWidth() - 18), $y + $this->padding);
            $this->serviceInfoSmall($tag, $x + 25 + $this->padding, $y + $this->padding);
            $lastPosition = $this->barcode($tag, $x, $y + 20);
        }
        $lastPosition = $this->recipient($tag, $x, $lastPosition[1]);
        $this->sender($x, $lastPosition[1]);
    }

    /**
     * @param PostalObject $tag
     * @param              $x
     * @param              $y
     */
    private function dataMatrix(PostalObject $tag, $x, $y)
    {
        $style = array(
            'border' => 0,
            'vpadding' => 1,
            'hpadding' => 1,
            'fgcolor' => array(0,0,0),
            'bgcolor' => array(255,255,255),
        );

        $validRecipientCep = str_split(preg_replace('/[^0-9]/', '', $tag->getRecipient()->getCep()), 1);
        $validRecipientCep = array_sum($validRecipientCep);
        $validRecipientCep = (ceil($validRecipientCep / 10) * 10) - $validRecipientCep;

        $additional = '';
        $additional .= array_key_exists('002', $tag->getAdditionalServices()) ? 'MP' : null;
        $additional .= array_key_exists('019', $tag->getAdditionalServices()) ? 'VD' : null;
        $additional .= array_key_exists('001', $tag->getAdditionalServices()) ? 'AR' : null;

        $matrixCode = vsprintf('%08d%05d%08d%05d%d%02d%013s%-08s%10d%05d%02d%05d%020d%05d%012s%-010s%-010s%s%-30s', [
            $tag->getRecipient()->getCep(),
            $tag->getRecipient()->getNumber(),
            $this->getConfig()->getSender()->getCep(),
            $this->getConfig()->getSender()->getNumber(),
            $validRecipientCep,
            '51',
            $tag->getTagDv(),
            $additional,
            $this->getConfig()->getPostCard(),
            $tag->getService(),
            '01',
            $tag->getRecipient()->getNumber(),
            $tag->getRecipient()->getComplement(),
            $tag->getValueDeclared(),
            $tag->getRecipient()->getPhone(),
            '-00.000000',
            '-00.000000',
            '|',
            'a'
        ]);

        $this->tcpdf->write2DBarcode($matrixCode, 'DATAMATRIX', $x, $y, 25, 25, $style);
    }

    /**
     * @param PostalObject $tag
     * @param              $x
     * @param              $y
     */
    private function forwardingSymbol(PostalObject $tag, $x, $y)
    {
        $key = $this->postalServices->{$tag->getService()}->key;
        $imgPath = CORREIOS_PHP_BASE . '/resources/assets/images';
        $image = sprintf("$imgPath/%s.png", $key);
        $this->tcpdf->Image($image, $x, $y, 15, 20, 'png', null, null, false, 300, null, false, false, 0, true);
    }

    /**
     * @param $x
     * @param $y
     */
    private function logo($x, $y)
    {
        $y += $this->padding;
        $x += $this->padding;
        if ($this->getSize() == PrintableContract::SIZE_BIG) {
            $x += 5;
            $image = $this->getConfig()->getSender()->getLogo();
            if (!file_exists($image)) {
                $image = CORREIOS_PHP_BASE . '/resources/assets/images/without_logo.png';
            }
        } else {
            $x += 25;
            $image = CORREIOS_PHP_BASE . '/resources/assets/images/correios.png';
        }
        $this->tcpdf->Image($image, $x, $y, 25, null, 'png', null, null, false, 300, null, false, false, 0, true);
    }

    /**
     * @param PostalObject $tag
     * @param              $x
     * @param              $y
     */
    private function serviceInfoSmall(PostalObject $tag, $x, $y)
    {
        $yDefault = $y;
        // Contract and Service data
        $y += 6;
        $this->tcpdf->SetXY($x-1, $y);
        $this->tcpdf->Write(3, 'Contrato: ');
        $this->writeBold(3, $this->getConfig()->getContract());

        $y += 3;
        $this->tcpdf->SetXY($x-1, $y);
        $name = $this->postalServices->{$tag->getService()}->name;
        $this->writeBold(3, $name);

        // Object data
        $y = $yDefault;
        $x += 33;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(3, 'NF: ');
        $this->writeBold(3, $tag->getInvoiceNumber());
        $y += 3;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(3, 'Pedido: ');
        $this->writeBold(3, $tag->getOrderNumber());
        $y += 3;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(3, 'Volume ');
        $this->tcpdf->Write(3, '1/1');
        $y += 3;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(3, 'Peso (g): ');
        $this->writeBold(3, $tag->getWeight());
    }

    /**
     * @param PostalObject $tag
     * @param              $x
     * @param              $y
     */
    private function serviceInfoBig(PostalObject $tag, $x, $y)
    {
        $yDefault = $y;
        $this->tcpdf->SetXY($x, $y);

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(3, 'NF: ');
        $this->writeBold(3, $tag->getInvoiceNumber());
        $y += 3;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(3, 'Pedido: ');
        $this->writeBold(3, $tag->getOrderNumber());

        $x += 30;
        $y = $yDefault;
        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(3, 'Contrato: ');
        $this->writeBold(3, $this->getConfig()->getContract());
        $y += 3;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(3, 'Sedex 10');

        $x += 40;
        $y = $yDefault;
        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(3, 'Volume ');
        $this->tcpdf->Write(3, '1/1');
        $y += 3;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(3, 'Peso (g): ');
        $this->writeBold(3, $tag->getWeight());
    }

    /**
     * @param PostalObject $tag
     * @param              $x
     * @param              $y
     *
     * @return array
     */
    private function barcode(PostalObject $tag, $x, $y)
    {
        $xDefault = $x;
        $code = implode(' ', array_merge(
            [substr($tag->getTagDv(), 0, 2)],
            str_split(preg_replace('/[^0-9]/', '', $tag->getTagDv()), 3),
            [substr($tag->getTagDv(), -2)]
        ));

        $this->tcpdf->SetXY($x + 25, $y);
        $this->tcpdf->SetFontSize(11);
        $this->writeBold(5, $code);
        $this->tcpdf->SetFontSize(9);

        $style = array(
            'position' => '',
            'align' => 'C',
            'fitwidth' => false,
            'border' => 0,
            'hpadding' => 0,
            'vpadding' => 0,
            'fgcolor' => array(0,0,0),
            'bgcolor' => array(255,255,255),
            'text' => false,
        );

        $this->tcpdf->write1DBarcode($tag->getTagDv(), 'C128', $x + 5, $y + 5, 75, 18, null, $style);

        $additionals = [];
        foreach ($tag->getAdditionalServices() as $additional) {
            $additionals[] = $this->additionalServices->$additional->initials;
        }
        $additionals = array_filter($additionals);

        $y = $yDefault = $y + 5 + $this->padding;
        $x += 83 + $this->padding;
        $this->tcpdf->SetFontSize(11);
        foreach ($additionals as $i => $additional) {
            if ($i % 4 === 0 && $i > 0) {
                $x += 8;
                $y = $yDefault;
            }
            $this->tcpdf->SetXY($x, $y);
            $this->writeBold(5, $additional);
            $y += 4;
        }
        $this->tcpdf->SetFontSize(9);

        if ($this->getSize() == PrintableContract::SIZE_BIG) {
            $y = $yDefault + 19;
            $x = $xDefault;
            $h = 4;
        } else {
            $y = $yDefault + 17;
            $x = $xDefault;
            $h = 4;
        }

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write($h, 'Recebedor: ');
        $this->tcpdf->Line($x + $this->padding, $y + $h, $x + $this->getTagWidth() - 3, $y + $h);
        $y += $h;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write($h, sprintf('Assinatura: %40s Documento:', ''));
        $this->tcpdf->Line($x + $this->padding, $y + $h, $x + $this->getTagWidth() - 3, $y + $h);
        $y += $h + 1;

        $this->tcpdf->SetXY($xDefault, $y);
        return [$this->tcpdf->GetX(), $this->tcpdf->GetY()];
    }

    /**
     * @param PostalObject $tag
     * @param              $x
     * @param              $y
     *
     * @return array
     */
    private function recipient(PostalObject $tag, $x, $y)
    {
        $this->tcpdf->SetFontSize(11);
        $xDefault = $x;
        $style = array(
            'position' => '',
            'align' => 'C',
            'fitwidth' => false,
            'border' => 0,
            'hpadding' => 0,
            'vpadding' => 0,
            'fgcolor' => array(0,0,0),
            'bgcolor' => array(255,255,255),
            'text' => false,
        );

        if ($this->getSize() == PrintableContract::SIZE_BIG) {
            $this->tcpdf->Line($xDefault - 1, $y, $x + $this->getTagWidth() - 1, $y);
            $this->tcpdf->Line($xDefault - 1, $y + 46, $x + $this->getTagWidth() - 1, $y + 46);
            $this->tcpdf->write1DBarcode($tag->getRecipient()->getCep(), 'C128', $x + 5, $y + 25, 40, 18, null, $style);
            $image = CORREIOS_PHP_BASE . '/resources/assets/images/correios.png';
            $this->tcpdf->Image($image, $this->getTagWidth() + $x - 30, $y + $this->padding, 25, null, 'png', null, null, false, 300, null, false, false, 0, true);
            $h = 4;
            $hTitle = 5;
            $x += 5;
            $yAdd = 21;
        } else {
            $this->tcpdf->Line($xDefault - 1, $y, $x + $this->getTagWidth() - 1, $y);
            $this->tcpdf->Line($xDefault - 1, $y + 20, $x + $this->getTagWidth() - 1, $y + 20);
            $this->tcpdf->write1DBarcode($tag->getRecipient()->getCep(), 'C128', $x + 55, $y + 1, 40, 18, null, $style);
            $h = 3;
            $hTitle = 5;
            $yAdd = -1;
        }

        $this->tcpdf->Rect($xDefault - 1, $y, 40, $hTitle, 'DF', [], [0,0,0]);
        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->SetTextColor(255);
        $this->writeBold($hTitle, 'DESTINATÁRIO');
        $this->tcpdf->SetTextColor(0);
        $y += $hTitle;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write($h, $tag->getRecipient()->getName());
        $y += $h;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write($h, sprintf('%s, %s', $tag->getRecipient()->getStreet(), $tag->getRecipient()->getNumber()));
        $y += $h;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write($h, trim(sprintf('%s %s', $tag->getRecipient()->getComplement(), $tag->getRecipient()->getDistrict())));
        $y += $h;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold($h, implode('-', str_split($tag->getRecipient()->getCep(), 5)));
        $this->tcpdf->Write($h, sprintf(' %s/%s', $tag->getRecipient()->getCity(), $tag->getRecipient()->getState()));
        $y += $h + 4;

        $this->tcpdf->SetXY($x, $y + $yAdd);
        $this->tcpdf->SetFontSize(9);
        return [$this->tcpdf->GetX(), $this->tcpdf->GetY()];
    }

    /**
     * @param        $x
     * @param        $y
     */
    private function sender($x, $y)
    {
        $this->tcpdf->SetFontSize(10);
        if ($this->getSize() == PrintableContract::SIZE_BIG) {
            $h = 4;
            $y += 3;
        } else {
            $h = 3;
        }

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold($h, 'Remetente: ');
        $this->tcpdf->Write($h, $this->getConfig()->getSender()->getName());
        $y += $h;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write($h, sprintf('%s, %s ', $this->getConfig()->getSender()->getStreet(), $this->getConfig()->getSender()->getNumber()));

        if ($this->getSize() == PrintableContract::SIZE_BIG) {
            $y += $h;
            $this->tcpdf->SetXY($x, $y);
            $h = 4;
        }

        $this->tcpdf->Write($h, trim(sprintf('%s %s', $this->getConfig()->getSender()->getComplement(), $this->getConfig()->getSender()->getDistrict())));
        $y += $h;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold($h, implode('-', str_split($this->getConfig()->getSender()->getCep(), 5)));
        $this->tcpdf->Write($h, sprintf(' %s/%s', $this->getConfig()->getSender()->getCity(), $this->getConfig()->getSender()->getState()));
        $y += $h;
        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->SetFontSize(9);
    }
}
