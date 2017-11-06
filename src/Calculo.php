<?php
namespace Eduardokum\CorreiosPhp;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;

class Calculo extends Correios
{
    const SERVICE_SEDEX = '04014';
    const SERVICE_SEDEX_COBRAR = '04065';
    const SERVICE_PAC = '04510';
    const SERVICE_PAC_COBRAR = '04707';
    const SERVICE_SEDEX_12 = '40169';
    const SERVICE_SEDEX_10 = '40215';
    const SERVICE_SEDEX_HOJE = '40290';

    const FORMAT_PACOTE  = 1;
    const FORMAT_ROLO  = 1;
    const FORMAT_ENVELOPE  = 3;

    public function __construct(ConfigContract $config = null, $type = 'curl')
    {
        parent::__construct($config, $type);
        $this->setWs($this->getWs('calculo'));
    }

    /**
     * @param         $service
     * @param         $cepFrom
     * @param         $cepTo
     * @param int     $weight
     * @param int     $format
     * @param int     $length
     * @param int     $height
     * @param int     $width
     * @param int     $diameter
     * @param boolean $maoPropria
     * @param int     $price
     * @param boolean $ar
     *
     * @return \stdClass
     * @throws InvalidArgumentException
     */
    public function calcularPrecoPrazo(
        $service,
        $cepTo,
        $cepFrom = null,
        $weight = 1,
        $format = 1,
        $length = 16,
        $height = 2,
        $width = 11,
        $diameter = 1,
        $price = 0,
        $maoPropria = false,
        $ar = false
    ) {
        if ($format == self::FORMAT_ENVELOPE && $weight > 1) {
            throw new InvalidArgumentException('The weight value can not be greater than 1kg when the format is letter');
        }
        if ($length < 16) {
            throw new InvalidArgumentException('Length less than 16cm is not accepted');
        }
        if ($height < 2) {
            throw new InvalidArgumentException('Height less than 2cm is not accepted');
        }
        if ($width < 11) {
            throw new InvalidArgumentException('Width less than 11cm is not accepted');
        }

        $cepFrom = $cepFrom ?: $this->getConfig()->getSender()->getCep();

        $request = '<CalcPrecoPrazo xmlns="http://tempuri.org/">';
        $request .= sprintf('<nCdEmpresa>%s</nCdEmpresa>', $this->getConfig()->getAdministrativeCode());
        $request .= sprintf('<sDsSenha>%s</sDsSenha>', $this->getConfig()->getPassword());
        $request .= sprintf('<nCdServico>%s</nCdServico>', $service);
        $request .= sprintf('<sCepOrigem>%08s</sCepOrigem>', preg_replace('/[^0-9]/', '', $cepFrom));
        $request .= sprintf('<sCepDestino>%08s</sCepDestino>', preg_replace('/[^0-9]/', '', $cepTo));
        $request .= sprintf('<nVlPeso>%d</nVlPeso>', $weight);
        $request .= sprintf('<nCdFormato>%d</nCdFormato>', $format);
        $request .= sprintf('<nVlComprimento>%d</nVlComprimento>', $length);
        $request .= sprintf('<nVlAltura>%d</nVlAltura>', $height);
        $request .= sprintf('<nVlLargura>%d</nVlLargura>', $width);
        $request .= sprintf('<nVlDiametro>%d</nVlDiametro>', $diameter);
        $request .= sprintf('<sCdMaoPropria>%s</sCdMaoPropria>', $maoPropria ? 'S' : 'N');
        $request .= sprintf('<nVlValorDeclarado>%s</nVlValorDeclarado>', $price);
        $request .= sprintf('<sCdAvisoRecebimento>%s</sCdAvisoRecebimento>', $ar ? 'S' : 'N');
        $request .= '</CalcPrecoPrazo>';
        $namespaces = [];
        $actions = [
            'curl' => 'http://tempuri.org/CalcPrecoPrazo',
            'native' => 'http://tempuri.org/CalcPrecoPrazo',
        ];

        $result = $this->getSoap()->send($this->url(), $actions, $request, $namespaces);

        return $result->CalcPrecoPrazoResult;
    }
}
