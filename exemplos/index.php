<?php
include '../vendor/autoload.php';

$correios = new \Eduardokum\CorreiosPhp\Correios;
echo '<pre>';
print_r($correios->consultaCEP('80320320'));
