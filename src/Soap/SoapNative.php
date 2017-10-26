<?php
namespace Eduardokum\CorreiosPhp\Soap;

use Eduardokum\CorreiosPhp\Contracts\Soap\Soap as SoapContract;

class SoapNative extends Soap implements SoapContract
{
    public function send($url, $action = '', $request = '', $namespaces = [])
    {
    }
}
