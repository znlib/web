<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use ZnCore\Base\Helpers\LoadHelper;
use ZnLib\Rest\Web\Controller\BaseCrudWebController;

abstract class BaseWebController extends AbstractController
{

    protected $layout = __DIR__ . '/layouts/main.php';
    protected $viewsDir;
    protected $fileExt = 'php';

    protected function renderTemplate(string $file, array $params = []): Response
    {
        $content = LoadHelper::loadTemplate($this->viewsDir . '/' . $file . '.' . $this->fileExt, $params);
        if (isset($this->layout)) {
            $content = LoadHelper::loadTemplate($this->layout, [
                'content' => $content,
            ]);
        }
        return new Response($content);
    }
}
