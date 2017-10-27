<?php
include '../vendor/autoload.php';

$correio = new \Eduardokum\CorreiosPhp\Sigep;
echo '<pre>';
print_r($correio->solicitaEtiquetas('124884', 5));
