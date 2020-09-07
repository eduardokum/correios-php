Production
==========

To create a configuration, just instantiate the object and pass on your correct information

.. code-block:: php

    $config = new Production();
    $sender = new Sender();
    $sender->setLogo('/path/to/logo')
        ->setName('Company')
        ->setStreet('Street')
        ->setNumber('Number')
        ->setComplement('Complement')
        ->setDistrict('District')
        ->setCep('000000000')
        ->setCity('City')
        ->setState('ST')
        ->setPhone('1199999999')
        ->setMail('mail@company.com');

    $config->setCNPJ('00000000000000')
        ->setUser('user')
        ->setPassword('pass')
        ->setAdministrativeCode('12345678')
        ->setContract('1234567890')
        ->setPostCard('1234567890')
        ->setServiceCode('12345')
        ->setDirection('99')
        ->setSender($sender);


Or, Simply:

.. code-block:: php

    $config = new Production::create([
        'cnpj' => '00000000000000',
        'user' => 'user',
        'password' => 'pass',
        'administrativeCode' => '12345678',
        'contract' => '1234567890',
        'postCard' => '1234567890',
        'serviceCode' => '12345',
        'direction' => '99',
        'sender' => Sender::create([
            'logo' => '',
            'name' => 'Company',
            'street' => 'Street',
            'number' => 'Number',
            'complement' => 'Complement',
            'district' => 'District',
            'cep' => '000000000',
            'city' => 'City',
            'state' => 'ST',
            'phone' => '1199999999',
            'mail' => 'mail@company.com',
        ])
    ]);