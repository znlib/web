<?php

namespace ZnLib\Web\TwBootstrap\Widgets\Format\Formatters;

use ZnDomain\Entity\Helpers\EntityHelper;

class ObjectFormatter extends BaseFormatter implements FormatterInterface
{

    public function render($object)
    {
        $array = EntityHelper::toArray($object);
        $arrayFormatter = new ArrayFormatter();
        return $arrayFormatter->render($array);
    }
}
