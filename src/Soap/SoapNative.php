<?php
namespace Eduardokum\CorreiosPhp\Soap;

use Eduardokum\CorreiosPhp\Contracts\Soap\Soap as SoapContract;
use Eduardokum\CorreiosPhp\Exception\SoapException;

class SoapNative extends Soap implements SoapContract
{
    public function send($url, $action = '', $request = '', $namespaces = [])
    {
        throw new SoapException('Method not implemented yet');
    }
}
