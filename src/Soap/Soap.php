<?php
namespace Eduardokum\CorreiosPhp\Soap;

use Eduardokum\CorreiosPhp\Contracts\Soap\Soap as SoapContract;
use Eduardokum\CorreiosPhp\Exception\SoapException;

abstract class Soap implements SoapContract
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
    public $response;
    /**
     * @var mixed
     */
    public $request;
    /**
     * @var mixed
     */
    public $soapError;
    /**
     * @var mixed
     */
    public $soapInfo;

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
     * @param array  $action
     * @param string $request
     * @param array  $namespaces
     * @param array  $auth
     *
     * @return mixed
     */
    abstract public function send($url, array $action = [], $request = '', $namespaces = [], $auth = []);

    /**
     * Mount soap envelope
     *
     * @param string $request
     * @param array  $namespaces
     *
     * @return string
     */
    protected function envelop($request, $namespaces = [])
    {
        $namespaces = array_merge([
            'xmlns:soap' => 'http://schemas.xmlsoap.org/soap/envelope/',
            'xmlns:xsi'     => "http://www.w3.org/2001/XMLSchema-instance",
            'xmlns:xsd'     => "http://www.w3.org/2001/XMLSchema",
        ], $namespaces);

        $envelope = '<?xml version="1.0" encoding="UTF-8"?><soap:Envelope';
        foreach ($namespaces as $key => $value) {
            $envelope .= vsprintf(' %s="%s"', [$key, $value]);
        }
        $envelope .= ">";
        $envelope .= "<soap:Body>$request</soap:Body>";
        $envelope .=  "</soap:Envelope>";
        return $envelope;
    }

    /**
     * @param $response
     *
     * @return \stdClass
     * @throws SoapException
     */
    protected function response($response)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($response);

        if (strpos($response, 'faultstring') !== false) {
            $exception = $dom->getElementsByTagName('faultstring')->item(0)->nodeValue;
            throw new SoapException($exception);
        }

        $response = $dom->getElementsByTagName('Body')
            ->item(0) // Get Body
            ->childNodes->item(0); // Get Result Object;
        $response  = simplexml_load_string($dom->saveXML($response), \SimpleXMLElement::class, LIBXML_NOCDATA);
        $response = json_encode($response, JSON_PRETTY_PRINT);
        return json_decode($response);
    }
}
