<?php
include '../vendor/autoload.php';

$correio = new \Eduardokum\CorreiosPhp\Sigep();

$o = \Eduardokum\CorreiosPhp\Entities\PostalObject::create([
    'tag' => 'PH185560916BR',
    'service' => '41068',
    'weight' => '200',
    'recipient' => \Eduardokum\CorreiosPhp\Entities\Recipient::create([
        'name' => 'Eduardo',
        'phone' => '41984796288',
        'cellphone' => '41984796288',
        'mail' => 'eduguscontra3@hotmail.com',
        'street' => 'Rua Pedro collere',
        'number' => '819',
        'complement' => 'ap 603B',
        'district' => 'Vila Izabel',
        'city' => 'Curitiba',
        'state' => 'PR',
        'cep' => '80320320',
    ]),
    'length' => '38',
    'height' => '20',
    'width' => '30',
    'diameter' => '0',
]);

$r = new Eduardokum\CorreiosPhp\Render\Pdf($o);
$r->render();

//$plp->addObject($o);
//print_r($correio->fechaPlpVariosServicos($plp));