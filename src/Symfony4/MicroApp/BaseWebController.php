<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use ZnCore\Base\Helpers\LoadHelper;
use ZnLib\Rest\Web\Controller\BaseCrudWebController;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\ControllerLayoutInterface;
use ZnLib\Web\View\View;

abstract class BaseWebController implements ControllerLayoutInterface
{

    protected $layout = __DIR__ . '/layouts/main.php';
    protected $viewsDir;
    protected $fileExt = 'php';

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function setLayout(?string $layout): void
    {
        $this->layout = $layout;
    }

    public function getViewsDir(): ?string
    {
        return $this->viewsDir;
    }

    public function setViewsDir(string $viewsDir): void
    {
        $this->viewsDir = $viewsDir;
    }

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

    protected function renderFile(string $file, array $params = []): Response
    {
        $view = new View();
        $view->setRenderDirectory($this->viewsDir);
        $pageContent = $view->renderFile($file, $params);
        if (isset($this->layout)) {
            $content = $view->renderFile($this->layout, [
                'content' => $pageContent,
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

    protected function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    protected function redirectToHome(int $status = 302): RedirectResponse
    {
        return $this->redirect('/', $status);
    }

    protected function redirectToBack(Request $request): RedirectResponse
    {
        $referer = $request->headers->get('referer');
        //$request->getSession()->setFlash('error', $exception->getMessage());
        return new RedirectResponse($referer);
    }

    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }
}
