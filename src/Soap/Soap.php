<?php
namespace Eduardokum\Soap;

use Eduardokum\Contracts\Soap\SoapInterface;

abstract class Soap implements SoapInterface
{
    /**
     * @var int
     */
    protected $soapProtocol = self::SSL_DEFAULT;
    /**
     * @var int
     */
    protected $soapTimeout = 20;
    /**
     * @var string
     */
    protected $proxyIP;
    /**
     * @var int
     */
    protected $proxyPort;
    /**
     * @var string
     */
    protected $proxyUser;
    /**
     * @var string
     */
    protected $proxyPass;
    /**
     * @var bool
     */
    protected $debugMode = false;
    /**
     * @var string
     */
    public $responseHead;
    /**
     * @var string
     */
    public $responseBody;
    /**
     * @var string
     */
    public $requestHead;
    /**
     * @var string
     */
    public $requestBody;
    /**
     * @var string
     */
    public $soaperror;
    /**
     * @var array
     */
    public $soapinfo = [];

    /**
     * Set option to encript private key before save in filesystem
     * for an additional layer of protection
     * @param bool $encript
     * @return bool
     */
    public function setEncriptPrivateKey($encript = true)
    {
        return $this->encriptPrivateKey = $encript;
    }

    /**
     * Set debug mode, this mode will save soap envelopes in temporary directory
     * @param bool $value
     * @return bool
     */
    public function setDebugMode($value = false)
    {
        return $this->debugMode = $value;
    }

    /**
     * Set timeout for communication
     *
     * @param int $timesecs
     *
     * @return int
     */
    public function timeout($timesecs)
    {
        return $this->soapTimeout = $timesecs;
    }

    /**
     * Set security protocol
     *
     * @param int $protocol
     *
     * @return int
     */
    public function protocol($protocol = self::SSL_DEFAULT)
    {
        return $this->soapProtocol = $protocol;
    }
    
    /**
     * Set proxy parameters
     * @param string $ip
     * @param int $port
     * @param string $user
     * @param string $password
     */
    public function proxy($ip, $port, $user, $password)
    {
        $this->proxyIP = $ip;
        $this->proxyPort = $port;
        $this->proxyUser = $user;
        $this->proxyPass = $password;
    }

    /**
     * @param string $url
     * @param string $action
     * @param string $request
     *
     * @return mixed
     */
    abstract public function send($url, $action = '', $request = '');

}
