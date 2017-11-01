<?php
namespace Eduardokum\CorreiosPhp\Traits;

trait MagicTrait
{

    /**
     * @param array $params
     * @return $this;
     */
    public function fill(array $params)
    {
        foreach ($params as $param => $value) {
            $param = str_replace(' ', '', ucwords(str_replace('_', ' ', $param)));
            if (method_exists($this, 'set' . ucwords($param))) {
                $this->{'set' . ucwords($param)}($value);
            }
        }
        return $this;
    }

    /**
     * @param array $params
     *
     * @return self
     */
    public static function create(array $params)
    {
        $obj = new self();
        return $obj->fill($params);
    }
}
