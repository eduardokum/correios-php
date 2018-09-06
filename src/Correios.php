<?php
namespace Eduardokum\CorreiosPhp;

use Eduardokum\CorreiosPhp\Config\Testing;
use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Exception\InvalidSoapException;
use Eduardokum\CorreiosPhp\Soap\Soap;

abstract class Correios
{
    /**
     * @var \stdClass
     */
    private $ws = [];

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
        $this->config = $config ?: new Testing();
        $this->makeSoap($type);
        $webservices = realpath(CORREIOS_PHP_BASE) . '/storage/webservices.json';
        $this->setWs(json_decode(file_get_contents($webservices)));
    }

    /**
     * @param \stdClass $ws
     *
     * @return $this
     */
    protected function setWs(\stdClass $ws)
    {
        $this->ws = $ws;
        return $this;
    }

    /**
     * @param null $key
     *
     * @return \stdClass
     */
    protected function getWs($key = null)
    {
        return $key && property_exists($this->ws, $key)
            ? $this->ws->$key
            : $this->ws;
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

        if (class_exists($soapClass) && !empty(trim($type))) {
            $this->soap = new $soapClass();
            return $this->soap;
        }

        throw new InvalidSoapException("The type '$type' is not acceptable");
    }

    /**
     * @return mixed
     */
    protected function url()
    {
        $env = $this->config->getEnvironment() == 'testing' ? 'homologacao' : 'producao';
        return $this->ws->$env;
    }

    /**
     * @return ConfigContract
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Soap
     */
    public function getSoap()
    {
        return $this->soap;
    }
}
