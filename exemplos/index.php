<?php
include __DIR__ . '/../vendor/autoload.php';

$config = new \Eduardokum\CorreiosPhp\Config\Production();
$sender = new \Eduardokum\CorreiosPhp\Entities\Sender();
$sender->setLogo('/path/to/logo')
    ->setName('PREMIER IMPORTACAO E EXPORTACAO EIRELI - EPP')
    ->setStreet('Rua Marquês de Itu')
    ->setNumber('266')
    ->setComplement('')
    ->setDistrict('Vila Buarque')
    ->setCep('01223000')
    ->setCity('São Paulo')
    ->setState('SP')
    ->setPhone('1136666220')
    ->setMail('financeiro@premieriluminacao.com.br');

$config->setCNPJ('08768469000187')
    ->setUser('0876846900')
    ->setPassword('HDF5iH:2:G')
    ->setUserRastro('0876846900')
    ->setPasswordRastro('HDF5iH:2:G')
    ->setAdministrativeCode('14502763')
    ->setContract('9912366519')
    ->setPostCard('0070490597')
    ->setDirection('72')
    ->setSender($sender);

//echo sprintf('user %s e senha %s' . PHP_EOL, $config->getUser(), $config->getPassword());
//$rastro = new \Eduardokum\CorreiosPhp\Rastreio($config);
//print_r($rastro->rastreamento(['PS290586454BR']));

$sigep = new \Eduardokum\CorreiosPhp\Sigep($config);
print_r($sigep->consultaSRO(['PT294789418BR']));