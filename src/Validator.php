<?php
namespace Eduardokum\CorreiosPhp;

class Validator
{

    /**
     * @param $xml
     * @param $xsd
     *
     * @return bool
     * @throws \Exception
     */
    public static function isValid($xml, $xsd)
    {
        if (!self::isXML($xml)) {
            throw new \Exception('XML invalid');
        }
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        libxml_clear_errors();
        if (! $dom->schemaValidate($xsd)) {
            $errors = [];
            foreach (libxml_get_errors() as $error) {
                $errors[] = $error->message;
            }
            throw new \Exception('Errors found: ' . implode(', ', $errors));
        }
        return true;
    }

    /**
     * @param $content
     *
     * @return bool
     */
    public static function isXML($content)
    {
        $content = trim($content);
        if (empty($content)) {
            return false;
        }
        if (stripos($content, '<!DOCTYPE html>') !== false
            || stripos($content, '</html>') !== false
        ) {
            return false;
        }
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        simplexml_load_string($content, \SimpleXMLElement::class, LIBXML_NOCDATA);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        return empty($errors);
    }
}