<?php

namespace ZnLib\Web\Widgets\Format\Formatters;

use ZnCore\Domain\Entity\Helpers\EntityHelper;

class ObjectFormatter extends BaseFormatter implements FormatterInterface
{

    public function render($object)
    {
        $array = EntityHelper::toArray($object);
        $arrayFormatter = new ArrayFormatter();
        return $arrayFormatter->render($array);
    }
}
