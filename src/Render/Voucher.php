<?php
namespace Eduardokum\CorreiosPhp\Render;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Entities\MailingList;
use Eduardokum\CorreiosPhp\Entities\PostalObject;
use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;

class Voucher extends Pdf
{
    /**
     * @var MailingList
     */
    private $mailingList;

    /**
     * @var int
     */
    private $docCount = 0;

    /**
     * @var mixed
     */
    private $postalServices;

    public function __construct(MailingList $mailingList, ConfigContract $config = null)
    {
        parent::__construct($config);
        $this->postalServices = json_decode(file_get_contents(CORREIOS_PHP_BASE . '/storage/postal_service.json'));
        $this->setMailingList($mailingList);

        $this->tcpdf = new \TCPDF(null, 'mm', 'A4', true, 'UTF-8');
        $this->tcpdf->setPrintHeader(false);
        $this->tcpdf->setPrintFooter(false);
        $this->tcpdf->SetMargins(0, 0, 0, true);
        $this->tcpdf->SetCreator('Eduardokum\\CorreiosPhp');
        $this->tcpdf->SetAuthor('Eduardokum\\CorreiosPhp');
        $this->tcpdf->SetTitle('Voucher');
        $this->tcpdf->SetSubject('Voucher');
        $this->tcpdf->SetKeywords('Voucher');
        $this->tcpdf->SetAutoPageBreak(false, 0);
        $this->tcpdf->SetFont($this->fontRegular, '', 9, '', false);
    }

    /**
     * @return MailingList
     */
    public function getMailingList()
    {
        return $this->mailingList;
    }

    /**
     * @param MailingList $mailingList
     *
     * @return Voucher
     */
    public function setMailingList(MailingList $mailingList)
    {
        $this->mailingList = $mailingList;
        return $this;
    }

    /**
     * @param string $output
     *
     * @return string
     */
    public function render($output = 'I')
    {
        $services = $this->filterTags($this->mailingList->toPrint());
        if (count($services) == 0) {
            throw new InvalidArgumentException('No tags available for printing');
        }

        $this->tcpdf->addPage();
        $lastPosition = $this->voucher($services, 5, 10);
        $this->voucher($services, $lastPosition[0], $lastPosition[1]);

        return $this->tcpdf->Output('voucher.pdf', $output);
    }

    /**
     * @param array $tags
     *
     * @return array
     */
    private function filterTags(array $tags)
    {
        $mailingListParsed = [];
        foreach ($tags as $postalObject) {
            /** @var PostalObject $postalObject */
            $mailingListParsed[$postalObject->getService()][] = $postalObject;
        }
        return $mailingListParsed;
    }

    /**
     * @param array $services
     * @param       $x
     * @param       $y
     *
     * @return array
     */
    private function voucher(array $services, $x, $y)
    {
        $this->docCount++;
        $width = $this->tcpdf->getPageWidth() - ($x * 2);

        $lastPosition = $this->logo($x, $y);
        $lastPosition = $this->title($x, $lastPosition[1], $width);
        $lastPosition = $this->info($x, $lastPosition[1], $width);
        $this->barcode($x, $lastPosition[1]);
        $lastPosition = $this->services($services, $x, $lastPosition[1], $width);
        return $this->dash($x, $lastPosition[1], $width);
    }

    /**
     * @param $x
     * @param $y
     *
     * @return array
     */
    private function logo($x, $y)
    {
        $image = CORREIOS_PHP_BASE . '/resources/assets/images/correios.png';
        $this->tcpdf->Image($image, $x, $y - 1, null, 8, 'png', null, null, false, 300, null, false, false, 0, true);

        $this->tcpdf->SetXY($x + 55, $y);
        $this->tcpdf->SetFontSize(14);
        $this->writeBold(10, 'EMPRESA BRASILEITA DE CORREIOS E TELÉGRAFOS');
        $this->tcpdf->SetFontSize(9);

        $this->tcpdf->SetXY($x, $y + 10);
        return [$this->tcpdf->GetX(),$this->tcpdf->GetY()];
    }

    /**
     * @param $x
     * @param $y
     * @param $width
     *
     * @return array
     */
    private function title($x, $y, $width)
    {
        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->SetFont($this->fontBold, 'B', 14);
        $this->tcpdf->Cell($width, 10, 'PRÉ - LISTA DE POSTAGEM - PLP - SIGEP WEB', 1, 0, 'C', false);
        $this->tcpdf->SetFont($this->fontRegular, null, 9);

        $this->tcpdf->SetXY($x, $y + 10);
        return [$this->tcpdf->GetX(), $this->tcpdf->GetY()];
    }

    /**
     * @param $x
     * @param $y
     * @param $width
     *
     * @return array
     */
    private function info($x, $y, $width)
    {
        $xDefault = $x;
        $yDefault = $y;

        $x += $this->padding;
        $y += $this->padding;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'SIGEP WEB - Gerenciador de postagens dos correios');
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Contrato: ');
        $this->tcpdf->Write(5, $this->getConfig()->getContract());
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Cliente: ');
        $this->tcpdf->Write(5, $this->getConfig()->getSender()->getName());
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Telefone de contato: ');
        $this->tcpdf->Write(5, $this->getConfig()->getSender()->getPhone());
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Email de contato: ');
        $this->tcpdf->Write(5, $this->getConfig()->getSender()->getMail());
        $y += 5;

        $this->tcpdf->Rect($xDefault, $yDefault, $width, $y - $yDefault);

        $this->tcpdf->SetXY($x, $y);
        return [$this->tcpdf->GetX(), $this->tcpdf->GetY()];
    }

    /**
     * @param $x
     * @param $y
     */
    private function barcode($x, $y)
    {
        $style = [
            'position' => '',
            'align'    => 'C',
            'fitwidth' => false,
            'border'   => 0,
            'hpadding' => 0,
            'vpadding' => 0,
            'fgcolor'  => [0, 0, 0],
            'bgcolor'  => [255, 255, 255],
            'text'     => false,
        ];

        $x += 140;
        $y -= 25;

        $this->tcpdf->SetXY($x + 12, $y);
        $this->writeBold(5, sprintf('Nº PLP: %d', $this->getMailingList()->getId()));
        $y += 5;

        $this->tcpdf->write1DBarcode($this->getMailingList()->getId(), 'C128', $x, $y, 50, null, null, $style);
    }

    /**
     * @param $services
     * @param $x
     * @param $y
     * @param $width
     *
     * @return array
     */
    private function services($services, $x, $y, $width)
    {
        $xDefault = $x;
        $yDefault = $y;

        $x += $this->padding;
        $y += $this->padding;

        $parts = $width / 10;
        $h = 7;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->SetFont($this->fontBold, 'B');
        $this->tcpdf->Cell($parts * 2, $h, 'Cód. Serviço:');
        $this->tcpdf->Cell($parts * 2, $h, 'Quantidade:');
        $this->tcpdf->Cell($parts * 3, $h, 'Serviço:');
        $this->tcpdf->SetFont($this->fontRegular, null);
        $y += $h;

        $sTot = 0;
        foreach ($services as $service => $tags) {
            $name = $this->postalServices->$service->name;
            $this->tcpdf->SetXY($x, $y);
            $this->tcpdf->Cell($parts * 2, $h, $service);
            $this->tcpdf->Cell($parts * 2, $h, $sTot = count($tags));
            $this->tcpdf->Cell($parts * 3, $h, $name);
            $y += $h;
        }

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Cell($parts * 2, $h, 'Total');
        $this->tcpdf->Cell($parts * 2, $h, $sTot);
        $this->tcpdf->Cell($parts * 3, $h, '');
        $yEnd = $y + $h + $this->padding;

        $x = $this->tcpdf->GetX();
        $y = $yDefault + 3;
        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Data de entraga: ____/____/______');
        $y += 5 + 5;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Line($x, $y, $x + ($parts * 3) - 5, $y);
        $this->tcpdf->Write(4, 'Assinatura/Matrícula dos correios');
        $y += 4;
        
        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(4, sprintf('%dº via - %s', $this->docCount, ($this->docCount == 1 ? 'correios' : 'cliente')));

        $this->tcpdf->SetXY($xDefault, $yEnd);
        $this->tcpdf->Rect($xDefault, $yDefault, $width, $yEnd - $yDefault);

        return [$this->tcpdf->GetX(), $yEnd];
    }

    /**
     * @param $x
     * @param $y
     * @param $width
     *
     * @return array
     */
    private function dash($x, $y, $width)
    {
        if ($this->docCount > 1) {
            return null;
        }

        $y += 3;
        $this->tcpdf->SetLineStyle(['dash' => '4,2']);
        $this->tcpdf->Line($x, $y, $x+$width, $y, []);
        $this->tcpdf->SetLineStyle(['dash' => 0]);
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        return [$this->tcpdf->GetX(), $this->tcpdf->GetY()];
    }
}