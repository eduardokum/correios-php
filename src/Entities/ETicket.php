<?php
namespace Eduardokum\CorreiosPhp\Entities;

use Eduardokum\CorreiosPhp\Contracts\Config\Config as ConfigContract;
use Eduardokum\CorreiosPhp\Contracts\Render\Printable as PrintableContract;
use Eduardokum\CorreiosPhp\Exception\InvalidArgumentException;

class ETicket
{
    const TYPE_POST_AUTHORIZATION = 'A';
    const TYPE_HOME_WITHDRAWAL_REQUIRED = 'C';
    const TYPE_HOME_WITHDRAWAL_NOT_REQUIRED = 'CA';

    /**
     * @var int
     */
    private $id = 1;

    /**
     * A - Autorização de Postagem
     * C - Coleta domiciliária
     * CA - Coleta domiciliar
     *
     * @var string
     */
    private $type = self::TYPE_POST_AUTHORIZATION;

    /**
     * @var int
     */
    private $autNumber = null;

    /**
     * Plp constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return ETicket
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getAutNumber()
    {
        return $this->autNumber;
    }

    /**
     * @param int $autNumber
     *
     * @return ETicket
     */
    public function setAutNumber(int $autNumber)
    {
        $this->autNumber = $autNumber;

        return $this;
    }
}
