<?php

namespace Eduardokum\CorreiosPhp\Contracts\Soap;

interface Soap
{
    //constants
    const SSL_DEFAULT = 0; //default
    const SSL_TLSV1 = 1; //TLSv1
    const SSL_SSLV2 = 2; //SSLv2
    const SSL_SSLV3 = 3; //SSLv3
    const SSL_TLSV1_0 = 4; //TLSv1.0
    const SSL_TLSV1_1 = 5; //TLSv1.1
    const SSL_TLSV1_2 = 6; //TLSv1.2
    
    /**
     * Set timeout for connection
     * @param int $timesecs
     */
    public function timeout($timesecs);
    
    /**
     * Set security protocol for soap communications
     * @param int $protocol
     */
    public function protocol($protocol = self::SSL_DEFAULT);
    
    /**
     * Set proxy parameters
     * @param string $ip
     * @param int $port
     * @param string $user
     * @param string $password
     */
    public function proxy($ip, $port, $user, $password);

    /**
     * @param        $url
     * @param array  $action
     * @param string $request
     * @param array  $namespaces
     * @param array  $auth
     *
     * @return mixed
     */
    public function send($url, array $action = [], $request = '', $namespaces = [], $auth = []);
}
