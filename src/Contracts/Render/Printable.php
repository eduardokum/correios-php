<?php
namespace Eduardokum\CorreiosPhp\Contracts\Render;

interface Printable
{

    const MODEL_SINGLE = 'single';
    const MODEL_MULTIPLE = 'multiple';

    const SIZE_SMALL = 'small';
    const SIZE_BIG = 'big';

    public function getModel();
    public function setModel($model);

    public function getSize();
    public function setSize($size);

    /**
     * @return array
     */
    public function toPrint();
}
