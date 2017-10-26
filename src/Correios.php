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
        return $this->soap->send($url, 'buscaEventosLista', $request, $namespaces);
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
        $request = [
            'nCdEmpresa' => $this->config->getAdministrativeCode(),
            'sDsSenha' => $this->config->getPassword(),
            'nCdServico' => $service,
            'sCepOrigem' => preg_replace('/[^0-9]/', '', $cepFrom),
            'sCepDestino' => preg_replace('/[^0-9]/', '', $cepTo),
            'nVlPeso' => $weight,
            'nCdFormato' => $format,
            'nVlComprimento' => $length,
            'nVlAltura' => $height,
            'nVlLargura' => $width,
            'nVlDiametro' => $diameter,
            'sCdMaoPropria' => $maoPropria ? 'S' : 'N',
            'nVlValorDeclarado' => $price,
            'sCdAvisoRecebimento' => $ar ? 'S' : 'N',
            'StrRetorno' => 'XML',
            'nIndicaCalculo' => '3',
        ];
        return $this->soap->send($url, 'CalcPrecoPrazo', $request);
    }
}