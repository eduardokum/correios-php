<?php
namespace Eduardokum\CorreiosPhp\Soap;

use Eduardokum\CorreiosPhp\Contracts\Soap\Soap as SoapContract;
use GuzzleHttp\Client as GuzzleClient;

class SoapCurl extends Soap implements SoapContract
{
    private $guzzle = null;

    public function __construct()
    {
        $this->guzzle = new GuzzleClient([
            'http_errors'    => false,
            'decode_content' => false,
            'verify'         => false,
        ]);
    }

    public function send($url, $action = '', $request = '', $namespaces = [])
    {
        $request = is_string($request) ? $this->envelop($request, $namespaces) : $request;
        $options = array_filter([
            'connect_timeout' => $this->soapTimeout,
            'body' => is_array($request) ? null : $request,
            'form_params' => is_array($request) ? $request : null,
            'headers' => [
                "Content-Type: text/xml;charset=utf-8",
                "SOAPAction: $action",
            ],
        ]);

        $response = $this->guzzle->post($url, $options);
        $status = $response->getStatusCode();

        if ($status >= 200 && $status < 300) {
            return $this->response($response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }
}
