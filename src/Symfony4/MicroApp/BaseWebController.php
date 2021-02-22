<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Symfony\Component\HttpFoundation\Response;
use ZnCore\Base\Helpers\LoadHelper;
use ZnLib\Rest\Web\Controller\BaseCrudWebController;
use ZnLib\Web\View\View;

abstract class BaseWebController
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

    protected function render(string $file, array $params = []): Response
    {

        $view = new View();
        $view->setRenderDirectory($this->viewsDir);
        $pageContent = $view->render($file, $params);
        if (isset($this->layout)) {
            $content = $view->renderFile($this->layout, [
                'content' => $pageContent,
            ]);
        }
        return new Response($content);
    }
}
