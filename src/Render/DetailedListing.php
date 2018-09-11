<?php
namespace Eduardokum\CorreiosPhp\Render;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Entities\MailingList;
use Eduardokum\CorreiosPhp\Entities\PostalObject;

class DetailedListing extends Pdf
{
    /**
     * @var MailingList
     */
    private $mailingList;

    /**
     * @var mixed
     */
    private $postalServices;

    public function __construct(MailingList $mailingList, ConfigContract $config = null)
    {
        parent::__construct($config);
        $this->postalServices = json_decode(file_get_contents(CORREIOS_PHP_BASE . '/storage/postal_service.json'));
        $this->setMailingList($mailingList);

        $this->tcpdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8');
        $this->tcpdf->setPrintHeader(false);
        $this->tcpdf->setPrintFooter(false);
        $this->tcpdf->SetMargins(0, 0, 0, true);
        $this->tcpdf->SetCreator('Eduardokum\\CorreiosPhp');
        $this->tcpdf->SetAuthor('Eduardokum\\CorreiosPhp');
        $this->tcpdf->SetTitle('DetailedListing');
        $this->tcpdf->SetSubject('DetailedListing');
        $this->tcpdf->SetKeywords('DetailedListing');
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
     * @return DetailedListing
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
        $this->tcpdf->addPage();
        $x =  5;
        $y = 10;

        $width = $this->tcpdf->getPageWidth() - ($x * 2);

        $lastPosition = $this->logo($x, $y);
        $lastPosition = $this->title($x, $lastPosition[1], $width);
        $lastPosition = $this->info($x, $lastPosition[1], $width);
        $lastPosition = $this->tags($this->getMailingList()->toPrint(), $x, $lastPosition[1], $width);
        $this->signature($x, $lastPosition[1], $width);

        if ($lastPosition[1] + 35 + 10 > $this->tcpdf->getPageHeight()) {
            $this->tcpdf->addPage();
        }

        return $this->tcpdf->Output('detailed_listing.pdf', $output);
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
        $this->tcpdf->Cell($width, 10, 'LISTA DE POSTAGEM', 1, 0, 'C', false);
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

        $part = ($width - 10) / 3;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Nº da lista: ');
        $this->tcpdf->Write(5, $this->getMailingList()->getId());
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Contrato: ');
        $this->tcpdf->Write(5, $this->getConfig()->getContract());
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Remetente: ');
        $this->tcpdf->Write(5, $this->getConfig()->getSender()->getName());
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Telefone de contato: ');
        $this->tcpdf->Write(5, $this->getConfig()->getSender()->getPhone());
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Email de contato: ');
        $this->tcpdf->Write(5, $this->getConfig()->getSender()->getMail());
        $yEnd = $y + 5;

        $x = $part + $this->padding;
        $y = $yDefault + $this->padding;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Cliente: ');
        $this->tcpdf->Write(5, $this->getConfig()->getSender()->getName());
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Cód. administrativo: ');
        $this->tcpdf->Write(5, $this->getConfig()->getAdministrativeCode());
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Endereço: ');
        $this->tcpdf->Write(5, trim(vsprintf('%s, %s %s', [
            $this->getConfig()->getSender()->getStreet(),
            $this->getConfig()->getSender()->getNumber(),
            implode('-', str_split($this->getConfig()->getSender()->getCep(), 5)),
        ])));
        $y += 5;
        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(5, trim(vsprintf('%s - %s - %s/%s', [
            $this->getConfig()->getSender()->getComplement(),
            $this->getConfig()->getSender()->getDistrict(),
            $this->getConfig()->getSender()->getCity(),
            $this->getConfig()->getSender()->getState(),
        ])));

        $x = ($part * 2) + 20;
        $y = $yDefault + $this->padding;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'Cartão: ');
        $this->tcpdf->Write(5, $this->getConfig()->getPostCard());

        $this->tcpdf->Rect($xDefault, $yDefault, $width, $yEnd - $yDefault);

        $this->tcpdf->SetXY($xDefault, $yEnd);
        return [$this->tcpdf->GetX(), $this->tcpdf->GetY()];
    }

    private function tags(array $tags, $x, $y, $width)
    {
        $xDefault = $x;
        $yDefault = $y;

        $x += $this->padding;
        $y += $this->padding;

        $parts = $width / 100;
        $h = 6;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->SetFont($this->fontBold, 'B');
        $this->tcpdf->Cell($parts * 10, $h, 'Nº do objeto');
        $this->tcpdf->Cell($parts * 15, $h, 'Serviço:');
        $this->tcpdf->Cell($parts * 8, $h, 'CEP:');
        $this->tcpdf->Cell($parts * 8, $h, 'Peso(g):');
        $this->tcpdf->Cell($parts * 3, $h, 'AR:');
        $this->tcpdf->Cell($parts * 3, $h, 'MP:');
        $this->tcpdf->Cell($parts * 3, $h, 'VD:');
        $this->tcpdf->Cell($parts * 10, $h, 'Valor declarado:');
        $this->tcpdf->Cell($parts * 10, $h, 'Nota fiscal:');
        $this->tcpdf->Cell($parts * 6, $h, 'Volume:');
        $this->tcpdf->Cell($parts * 24, $h, 'Destinatário:');
        $this->tcpdf->SetFont($this->fontRegular, null);
        $y += $h;

        $fill = [
            0 => 210,
            1 => 255,
        ];

        $i = 0;
        foreach ($tags as $tag) {
            /** @var PostalObject $tag */
            $this->tcpdf->SetFillColor($fill[$i%2]);
            $name = $this->postalServices->{$tag->getService()}->name;
            $this->tcpdf->SetXY($x, $y);
            $this->tcpdf->Cell($parts * 10, $h, $tag->getTagDv(), 0, 0, '', true);
            $this->tcpdf->Cell($parts * 15, $h, $name, 0, 0, '', true);
            $this->tcpdf->Cell($parts * 8, $h, $tag->getRecipient()->getCep(), 0, 0, '', true);
            $this->tcpdf->Cell($parts * 8, $h, $tag->getWeight(), 0, 0, '', true);
            $this->tcpdf->Cell($parts * 3, $h, in_array('001', $tag->getAdditionalServices()) ? 'S' : 'N', 0, 0, '', true);
            $this->tcpdf->Cell($parts * 3, $h, in_array('002', $tag->getAdditionalServices()) ? 'S' : 'N', 0, 0, '', true);
            $this->tcpdf->Cell($parts * 3, $h, in_array('019', $tag->getAdditionalServices()) ? 'S' : 'N', 0, 0, '', true);
            $this->tcpdf->Cell($parts * 10, $h, $tag->getValueDeclared(), 0, 0, '', true);
            $this->tcpdf->Cell($parts * 10, $h, $tag->getInvoiceNumber(), 0, 0, '', true);
            $this->tcpdf->Cell($parts * 6, $h, '1/1', 0, 0, '', true);
            $this->tcpdf->Cell(($parts * 24) -1.5, $h, $tag->getRecipient()->getName(), 0, 0, '', true);
            $y += $h;
            $i++;
        }
        $this->tcpdf->SetFillColor(255);

        $y += 1;
        $this->tcpdf->SetXY($xDefault, $y);
        $this->tcpdf->Rect($xDefault, $yDefault, $width, $y - $yDefault);

        return [$this->tcpdf->GetX(), $this->tcpdf->GetY()];
    }

    private function signature($x, $y, $width)
    {
        $yDefault = $y;

        $this->tcpdf->Rect($x, $y, $width, 35);
        $x += 4;
        $y += 4;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'APRESENTAR ESSA LISTA EM CASO DE PEDIDO DE INFORMAÇÕES');
        $y += 5 + 2;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(5, 'Estou ciente do disposto na cláusula terceira do contrato de prestação de serviços');
        $y += 5 + 12;
        $x += 40;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Line($x - 15, $y, $x + 65, $y);
        $this->tcpdf->Write(5, 'ASSINATURA DO REMETENTE');

        $x = $this->tcpdf->getPageWidth() - 100;
        $y = $yDefault + 4;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(5, 'Carimbo e assinatura/matrícula dos correios');
    }
}
