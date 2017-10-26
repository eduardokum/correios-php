<?php
namespace Eduardokum\Soap;

use Eduardokum\Contracts\Soap\SoapInterface;

class SoapCurl extends Soap implements SoapInterface
{
    public function send($url, $action = '', $request = '') {
    }
}
