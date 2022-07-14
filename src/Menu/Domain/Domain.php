<?php

namespace ZnLib\Web\Menu\Domain;

use ZnDomain\Domain\Interfaces\DomainInterface;

class Domain implements DomainInterface
{

    public function getName()
    {
        return 'layout';
    }
}
