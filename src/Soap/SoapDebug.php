<?php
namespace Eduardokum\CorreiosPhp\Soap;

use Eduardokum\CorreiosPhp\Contracts\Soap\Soap as SoapContract;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class SoapDebug extends Soap implements SoapContract
{
    public function send($url, array $action = [], $request = '', $namespaces = [], $auth = [])
    {
        $result = new \stdClass();
        $result->args = func_get_args();
        $result->request = $request = $this->envelop($request, $namespaces);
        $result->headers = array_filter([
            'Content-Type: text/xml;charset=utf-8',
            array_key_exists('curl', $action) && !empty(trim($action['curl'])) ? sprintf('SOAPAction: "%s"', $action['curl']) : null,
            'Accept: text/xml',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            sprintf('Content-length: %s', strlen($request)),
        ]);

        $this->getDumper()->dump((new VarCloner())->cloneVar($result));
        die;
    }

    /**
     * @return HtmlDumper
     */
    private function getDumper()
    {
        $htmlDumper = new HtmlDumper();
        $htmlDumper->setStyles([
            'default' => 'background-color:#fff; color:#FF8400; line-height:1.2em; font:12px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: break-all',
            'num' => 'font-weight:bold; color:#000000',
            'const' => 'font-weight:bold',
            'str' => 'font-weight:bold; color:#888888',
            'note' => 'color:#555555; font-weight: bold;',
            'ref' => 'color:#b72904',
            'public' => 'color:#111111; font-weight: bold;',
            'protected' => 'color:#111111; font-weight: bold;',
            'private' => 'color:#111111; font-weight: bold;',
            'meta' => 'color:#b72904',
            'key' => 'color:#000000',
            'index' => 'color:#000000',
            'ellipsis' => 'color:#111111',
        ]);
        return $htmlDumper;
    }
}
