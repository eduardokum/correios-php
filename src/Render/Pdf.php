<?php
namespace Eduardokum\CorreiosPhp\Render;

use Eduardokum\CorreiosPhp\Config\Testing;
use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;

abstract class Pdf
{
    /**
     * @var \TCPDF
     */
    protected $tcpdf;

    /**
     * @var ConfigContract
     */
    protected $config;

    /**
     * @var int
     */
    protected $padding = 1;

    /**
     * @var mixed|string
     */
    protected $fontRegular;

    /**
     * @var mixed|string
     */
    protected $fontBold;

    public function __construct(ConfigContract $config = null)
    {
        $this->config = $config ?: new Testing();
        $this->fontRegular = \TCPDF_FONTS::addTTFfont(CORREIOS_PHP_BASE . '/resources/assets/fonts/Arial.ttf', 'TrueTypeUnicode', '', 96);
        $this->fontBold = \TCPDF_FONTS::addTTFfont(CORREIOS_PHP_BASE . '/resources/assets/fonts/ArialBold.ttf', 'TrueTypeUnicode', '', 96);
    }

    /**
     * @return ConfigContract
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param        $h
     * @param        $txt
     * @param string $link
     * @param bool   $fill
     * @param string $align
     * @param bool   $ln
     * @param int    $stretch
     * @param bool   $firstline
     * @param bool   $firstblock
     * @param int    $maxh
     * @param int    $wadj
     * @param string $margin
     */
    protected function writeBold($h, $txt, $link = '', $fill = false, $align = '', $ln = false, $stretch = 0, $firstline = false, $firstblock = false, $maxh = 0, $wadj = 0, $margin = '')
    {
        $this->tcpdf->SetFont($this->fontBold, 'B');
        $this->tcpdf->Write($h, $txt, $link, $fill, $align, $ln, $stretch, $firstline, $firstblock, $maxh, $wadj, $margin);
        $this->tcpdf->SetFont($this->fontRegular, null);
    }
}