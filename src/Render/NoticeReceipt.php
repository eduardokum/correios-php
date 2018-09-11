<?php
namespace Eduardokum\CorreiosPhp\Render;

use Eduardokum\CorreiosPhp\Contracts\Render\Printable as PrintableContract;
use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Entities\PostalObject;
use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;

class NoticeReceipt extends Pdf
{

    /**
     * @var PrintableContract
     */
    private $printable;

    /**
     * @var int
     */
    private $perPage = 3;

    /**
     * @var array
     */
    private $positions = [
        3 => [
            1 => [2.5, 2.5],
            2 => [2.5, 97 + 3.5],
            3 => [2.5, (97 * 2) + (2.5 * 2)],
        ],
    ];

    /**
     * @var array
     */
    private $noticeReceiptSize = [205, 95];

    public function __construct(PrintableContract $printable, ConfigContract $config = null)
    {
        parent::__construct($config);
        $this->setPrintable($printable);

        $this->tcpdf = new \TCPDF(null, 'mm', 'A4', true, 'UTF-8');
        $this->tcpdf->setPrintHeader(false);
        $this->tcpdf->setPrintFooter(false);
        $this->tcpdf->SetMargins(0, 0, 0, true);
        $this->tcpdf->SetCreator('Eduardokum\\CorreiosPhp');
        $this->tcpdf->SetAuthor('Eduardokum\\CorreiosPhp');
        $this->tcpdf->SetTitle('AR');
        $this->tcpdf->SetSubject('AR');
        $this->tcpdf->SetKeywords('AR');
        $this->tcpdf->SetAutoPageBreak(false, 0);
        $this->tcpdf->SetFont($this->fontRegular, '', 9, '', false);
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
     * @return NoticeReceipt
     */
    public function setPrintable(PrintableContract $printable)
    {
        $this->printable = $printable;
        return $this;
    }

    /**
     * @param string $output
     *
     * @return string
     */
    public function render($output = 'I')
    {
        $tags = $this->filterTags($this->getPrintable()->toPrint());

        if (count($tags) == 0) {
            throw new InvalidArgumentException('No tags available for printing');
        }

        $noticeCount = 0;
        foreach ($tags as $tag) {
            $noticeCount++;
            $position = $rest = $noticeCount % $this->getPerPage();
            $position = $position == 0 ? $this->getPerPage() : $position;
            if ($rest === 1) {
                $this->tcpdf->addPage();
            }
            $this->noticeReceipt($tag, $position);
        }

        return $this->tcpdf->Output('notice_receipt.pdf', $output);
    }

    /**
     * @param array $tags
     *
     * @return array
     */
    private function filterTags(array $tags)
    {
        return array_filter($tags, function ($tag) {
            /** @var PostalObject $tag */
            return in_array('001', $tag->getAdditionalServices());
        });
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
     * @return int
     */
    private function getNoticeReceiptWidth()
    {
        return $this->noticeReceiptSize[0];
    }

    /**
     * @return int
     */
    private function getNoticeReceiptHeight()
    {
        return $this->noticeReceiptSize[1];
    }

    /**
     * @param $tag
     * @param $position
     */
    private function noticeReceipt(PostalObject $tag, $position)
    {
        list($x, $y) = $this->getPositionXY($position);
        $this->frame($x, $y);

        $x += 7;
        $this->tcpdf->SetXY($x, $y);
        $lastPosition = $topPosition = $this->logo($x, $y);
        $lastPosition = $this->recipient($tag, $x, $lastPosition[1]);
        $lastPosition = $this->barcode($tag, $x, $lastPosition[1]);
        $lastPosition = $this->sender($x, $lastPosition[1]);
        $this->signatures($x, $lastPosition[1]);
        $lastPosition = $this->retry($x, $topPosition[1]);
        $this->stamp($lastPosition[0], $topPosition[1]);
    }

    /**
     * @param $x
     * @param $y
     */
    private function frame($x, $y)
    {
        $this->tcpdf->Rect($x + 7, $y, $this->getNoticeReceiptWidth() - 7, $this->getNoticeReceiptHeight());
        $this->tcpdf->StartTransform();
        $this->tcpdf->SetXY($x, $y + $this->getNoticeReceiptHeight());
        $this->tcpdf->Rotate(90);
        $border_dashed = ['LRTB' => ['dash' => '3,2']];
        $this->tcpdf->Cell($this->getNoticeReceiptHeight(), 6, sprintf('Cole aqui %40s Cole aqui', ''), $border_dashed, 0, 'C');
        $this->tcpdf->StopTransform();
        $this->tcpdf->SetLineStyle(['dash' => 0]);
    }

    /**
     * @param $x
     * @param $y
     *
     * @return array
     */
    private function logo($x, $y)
    {
        $xDefault = $x;
        $image = CORREIOS_PHP_BASE . '/resources/assets/images/correios.png';
        $this->tcpdf->Image($image, $x + $this->padding, $y + $this->padding, null, 8, 'png', null, null, false, 300, null, false, false, 0, true);

        $this->tcpdf->SetXY($x + 55, $y);
        $this->tcpdf->SetFontSize(18);
        $this->writeBold(10, 'SIGEP');
        $this->tcpdf->SetFontSize(9);

        $x = $this->tcpdf->GetX() + 2;
        $this->tcpdf->SetXY($x, $y + $this->padding);
        $this->writeBold(3, 'AVISO DE');
        $this->tcpdf->SetXY($x, $y + 5);
        $this->writeBold(3, 'RECEBIMENTO');

        $x = $this->tcpdf->GetX() + 15;
        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(10, sprintf('CONTRATO %s', $this->getConfig()->getContract()));
        $y += 10;
        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Line($xDefault, $y, $this->tcpdf->getPageWidth() - 2.5, $y);
        return [$this->tcpdf->GetX(),$this->tcpdf->GetY()];
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
        $x += 2;
        $y += 1;
        $this->tcpdf->SetXY($x, $y);

        $this->writeBold(5, 'DESTINATÁRIO: ');
        $y += 6;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(4, $tag->getRecipient()->getName());
        $y += 4;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(4, sprintf('%s, %s', $tag->getRecipient()->getStreet(), $tag->getRecipient()->getNumber()));
        $y += 4;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(4, trim(sprintf('%s %s', $tag->getRecipient()->getComplement(), $tag->getRecipient()->getDistrict())));
        $y += 4;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(4, implode('-', str_split($tag->getRecipient()->getCep(), 5)));
        $this->tcpdf->Write(4, sprintf(' %s/%s', $tag->getRecipient()->getCity(), $tag->getRecipient()->getState()));
        $y += 4;

        $this->tcpdf->SetXY($x, $y);
        return [$this->tcpdf->GetX(), $this->tcpdf->GetY()];
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
        $x += 10;
        $y += 2;

        $code = implode(' ', array_merge(
            [substr($tag->getTagDv(), 0, 2)],
            str_split(preg_replace('/[^0-9]/', '', $tag->getTagDv()), 3),
            [substr($tag->getTagDv(), - 2)]
        ));

        $this->tcpdf->SetXY($x + 20, $y);
        $this->tcpdf->SetFontSize(11);
        $this->writeBold(5, $code);
        $this->tcpdf->SetFontSize(9);

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

        $this->tcpdf->write1DBarcode($tag->getTagDv(), 'C128', $x + 5, $y + 5, 65, 15, null, $style);

        $y += 5 + 18;
        $this->tcpdf->SetXY($x, $y);
        return [$this->tcpdf->GetX(), $this->tcpdf->GetY()];
    }

    /**
     * @param        $x
     * @param        $y
     *
     * @return array
     */
    private function sender($x, $y)
    {
        $x += 2;
        $y += $this->padding;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(4, 'REMETENTE: ');
        $this->tcpdf->Write(4, $this->getConfig()->getSender()->getName());
        $y += 4;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(4, 'ENDEREÇO PARA DEVOLUÇÃO DO OBJETO: ');
        $y += 4;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(4, sprintf('%s, %s ', $this->getConfig()->getSender()->getStreet(), $this->getConfig()->getSender()->getNumber()));
        $this->tcpdf->Write(4, trim(sprintf('%s %s', $this->getConfig()->getSender()->getComplement(), $this->getConfig()->getSender()->getDistrict())));
        $y += 4;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(4, implode('-', str_split($this->getConfig()->getSender()->getCep(), 5)));
        $this->tcpdf->Write(4, sprintf(' %s/%s', $this->getConfig()->getSender()->getCity(), $this->getConfig()->getSender()->getState()));
        $y += 4;
        $this->tcpdf->SetXY($x, $y);

        return [$this->tcpdf->GetX(), $this->tcpdf->GetY()];
    }

    /**
     * @param $x
     * @param $y
     *
     * @return array
     */
    private function signatures($x, $y)
    {
        $y += 2.5;
        $this->tcpdf->Line($x, $y - 0.5, $this->tcpdf->getPageWidth() - 2.5, $y - 0.5);
        $this->tcpdf->SetXY($x, $y);

        $this->tcpdf->SetFontSize(5);
        $this->tcpdf->Write(2, 'DECLARAÇÂO DE CONTEÚDO');
        $y += 6;

        $x2Colum = $this->tcpdf->getPageWidth() - 82;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Line($x, $y - 0.5, $this->tcpdf->getPageWidth() - 2.5, $y - 0.5);
        $this->tcpdf->Write(2, 'ASSINATURA DO RECEBEDOR');
        $this->tcpdf->SetX($x2Colum);
        $this->tcpdf->Write(2, 'DATA DE ENTREGA');
        $this->tcpdf->Line($x2Colum - $this->padding, $y - 0.5, $x2Colum - $this->padding, $y + 5.5);
        $y += 6;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Line($x, $y - 0.5, $this->tcpdf->getPageWidth() - 2.5, $y - 0.5);
        $this->tcpdf->Write(2, 'NOME LEGIVÉL DO RECEBEDOR');
        $this->tcpdf->SetX($x2Colum);
        $this->tcpdf->Write(2, 'N DOC DE IDENTIDADE');
        $this->tcpdf->Line($x2Colum - $this->padding, $y - 0.5, $x2Colum - $this->padding, $y + 5.5);
        $y += 6;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->SetFontSize(9);
        return [$this->tcpdf->GetX(), $this->tcpdf->GetY()];
    }

    /**
     * @param $x
     * @param $y
     *
     * @return array
     */
    private function retry($x, $y)
    {
        $x += 95;
        $xDefault  = $x;
        $this->tcpdf->Rect($x, $y, 65, $this->getNoticeReceiptHeight() - 22, 'DF', [], [255, 255, 255]);

        $x += 2;
        $y += 1;
        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(5, 'TENTATIVAS DE ENTREGA:');
        $y += 8;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(6, '1º');
        $this->tcpdf->Write(6, ' ___/___/_____        _____:_____h');
        $y += 6;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(6, '2º');
        $this->tcpdf->Write(6, ' ___/___/_____        _____:_____h');
        $y += 6;

        $this->tcpdf->SetXY($x, $y);
        $this->writeBold(6, '3º');
        $this->tcpdf->Write(6, ' ___/___/_____        _____:_____h');
        $y += 16;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Write(5, 'MOTIVO DE DEVOLUÇÃO:');
        $y += 7;

        $this->tcpdf->SetFontSize(6);

        $yTop = $y;
        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Cell(6, 4, '1', 1, 0, 'C');
        $this->tcpdf->Write(4, 'Mudou-se');
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Cell(6, 4, '2', 1, 0, 'C');
        $this->tcpdf->Write(4, 'Endereço insulficiente');
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Cell(6, 4, '3', 1, 0, 'C');
        $this->tcpdf->Write(4, 'Não existe o número');
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Cell(6, 4, '4', 1, 0, 'C');
        $this->tcpdf->Write(4, 'Desconhecido');
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Cell(6, 4, '9', 1, 0, 'C');
        $this->tcpdf->Write(4, 'Outros: ____________________________________');
        $y = $yTop;
        $x += 32;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Cell(6, 4, '5', 1, 0, 'C');
        $this->tcpdf->Write(4, 'Recusado');
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Cell(6, 4, '6', 1, 0, 'C');
        $this->tcpdf->Write(4, 'Não procurado');
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Cell(6, 4, '7', 1, 0, 'C');
        $this->tcpdf->Write(4, 'Ausente');
        $y += 5;

        $this->tcpdf->SetXY($x, $y);
        $this->tcpdf->Cell(6, 4, '8', 1, 0, 'C');
        $this->tcpdf->Write(4, 'Falecido');
        $y += 5;

        $this->tcpdf->SetFontSize(9);
        $this->tcpdf->SetXY($xDefault + 65, $y);
        return [$this->tcpdf->GetX(), $this->tcpdf->GetY()];
    }

    /**
     * @param $x
     * @param $y
     */
    private function stamp($x, $y)
    {
        $this->tcpdf->SetFontSize(6);
        $allSize = $this->getNoticeReceiptHeight() - 10;

        $first = ($allSize/10)*6;
        $last = ($allSize/10)*4;

        $this->tcpdf->SetXY($x + 4, $y + $this->padding);
        $this->tcpdf->Rect($x, $y, 38, $first, 'DF', [], [255, 255, 255]);
        $this->tcpdf->MultiCell(30, 7, 'CARIMBO UNIDADE DE ENTREGA', 0, 'C');
        $y += $first;

        $this->tcpdf->SetXY($x + 4, $y + $this->padding);
        $this->tcpdf->Rect($x, $y, 38, $last, 'DF', [], [255, 255, 255]);
        $this->tcpdf->MultiCell(30, 7, 'RUBRICA E MATRÍCULA DO CARTEIRO', 0, 'C');
        $this->tcpdf->SetFontSize(9);
    }
}
