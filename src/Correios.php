<?php
namespace Eduardokum\CorreiosPhp;

use Eduardokum\CorreiosPhp\Config\Homologacao;
use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;
use Eduardokum\CorreiosPhp\Exception\InvalidSoapException;
use Eduardokum\CorreiosPhp\Soap\Soap;

class Correios
{
    /**
     * @var \stdClass
     */
    protected $ws = [];

    /**
     * @var ConfigContract
     */
    private $config = null;

    /**
     * @var Soap
     */
    private $soap = null;

    public function __construct(ConfigContract $config = null, $type = 'curl')
    {
        $this->config = $config ?: new Homologacao();
        $this->makeSoap($type);
        $webservices = realpath(__DIR__ . '/storage/') . '/webservices.json';
        $this->ws = json_decode(file_get_contents($webservices));
    }

    /**
     * @param $type
     *
     * @return Soap
     * @throws InvalidSoapException
     */
    private function makeSoap($type)
    {
        $soapClass = '\\Eduardokum\\CorreiosPhp\\Soap\\Soap' . ucfirst(strtolower($type));

        if (class_exists($soapClass)) {
            $this->soap = new $soapClass();
            return $this->soap;
        }

        throw new InvalidSoapException("The type $type is not acceptable");
    }

    /**
     * @param $service
     *
     * @return mixed
     */
    private function url($service)
    {
        return $this->ws->{$this->config->getEnvironment()}->$service;
    }

    /**
     * @param array $codes
     *
     * @return mixed
     */
    public function rastreamento(array $codes)
    {
        $user = $this->config->getEnvironment() == 'homologacao' ? 'ECT' : $this->config->getUser();
        $pass = $this->config->getEnvironment() == 'homologacao' ? 'SRO' : $this->config->getPassword();

        $request = '<res:buscaEventosLista>';
        $request .= sprintf('<usuario>%s</usuario>', $user);
        $request .= sprintf('<senha>%s</senha>', $pass);
        $request .= sprintf('<tipo>%s</tipo>', 'L');
        $request .= sprintf('<resultado>%s</resultado>', 'T');
        $request .= sprintf('<lingua>%s</lingua>', '101');
        foreach ($codes as $c) {
            $request .= sprintf('<objetos>%s</objetos>', $c);
        }
        $request .= '</res:buscaEventosLista>';
        $url = $this->url('rastreamento');
        $namespaces = [
            'xmlns:res' => 'http://resource.webservice.correios.com.br/',
        ];
        $result = $this->soap->send($url, 'buscaEventosLista', $request, $namespaces);
        return $result->return;
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
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function calcularPrecoPrazo(
        $service,
        $cepFrom,
        $cepTo,
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
        if ($format == 1 && $weight > 1) {
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

        $url = $this->url('calculo-preco-prazo');
        $request = '<CalcPrecoPrazo xmlns="http://tempuri.org/">';
        $request .= sprintf('<nCdEmpresa>%s</nCdEmpresa>', $this->config->getAdministrativeCode());
        $request .= sprintf('<sDsSenha>%s</sDsSenha>', $this->config->getPassword());
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

        $result = $this->soap->send($url, 'http://tempuri.org/CalcPrecoPrazo', $request);

        return $result->CalcPrecoPrazoResult;
    }
}