<?php

namespace ZnLib\Web\Symfony4\WebBundle\Twig;

use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class VariableExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('env', [$this, 'env'], ['is_safe' => ['html']]),
        ];
    }

    public function env(string $name)
    {
        return $_ENV[$name];
    }

}
