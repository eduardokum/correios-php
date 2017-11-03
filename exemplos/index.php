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

$o->addServiceNoticeReceipt();


$o2 = \Eduardokum\CorreiosPhp\Entities\PostalObject::create([
    'tag' => 'PH185540916BR',
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

$o2->addServiceNoticeReceipt();


$o3 = \Eduardokum\CorreiosPhp\Entities\PostalObject::create([
    'tag' => 'PH183560916BR',
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

$o3->addServiceNoticeReceipt();

$o4 = \Eduardokum\CorreiosPhp\Entities\PostalObject::create([
    'tag' => 'PH183560516BR',
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

$o4->addServiceNoticeReceipt();

$o5 = \Eduardokum\CorreiosPhp\Entities\PostalObject::create([
    'tag' => 'PH283560516BR',
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

$o5->addServiceNoticeReceipt();


$o6 = \Eduardokum\CorreiosPhp\Entities\PostalObject::create([
    'tag' => 'PH383560516BR',
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

$o6->addServiceNoticeReceipt();


$o7 = \Eduardokum\CorreiosPhp\Entities\PostalObject::create([
    'tag' => 'PH483560516BR',
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

$o7->addServiceNoticeReceipt();


$plp = new \Eduardokum\CorreiosPhp\Entities\Plp(1);
$plp->addObject($o);
$plp->addObject($o2);
$plp->addObject($o3);
$plp->addObject($o4);
$plp->addObject($o5);
$plp->addObject($o6);
$plp->addObject($o7);

$r = new Eduardokum\CorreiosPhp\Render\NoticeReceipt($plp);
$r->render();

//$plp->addObject($o);
//print_r($correio->fechaPlpVariosServicos($plp));