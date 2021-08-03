<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use ZnCore\Base\Enums\Http\HttpStatusCodeEnum;
use ZnCore\Base\Helpers\LoadHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use ZnLib\Rest\Web\Controller\BaseCrudWebController;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\ControllerLayoutInterface;
use ZnLib\Web\View\View;

abstract class BaseWebController implements ControllerLayoutInterface
{

    protected $layout = __DIR__ . '/layouts/main.php';
    protected $layoutParams = [];
    protected $viewsDir;
    protected $view;
    protected $fileExt = 'php';

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function setLayout(?string $layout): void
    {
        $this->layout = $layout;
    }

    public function getLayoutParams(): array
    {
        return $this->layoutParams;
    }

    public function setLayoutParams(array $layoutParams): void
    {
        $this->layoutParams = $layoutParams;
    }

    public function addLayoutParam(string $name, $value): void
    {
        $this->layoutParams[$name] = $value;
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
            $params = ArrayHelper::merge($this->getLayoutParams(), $params);
            $params['content'] = $content;
            $content = LoadHelper::loadTemplate($this->layout, $params);
        }
        return new Response($content);
    }

    protected function getView(): View {
        if(empty($this->view)) {
            $this->view = new View();
        }
        return $this->view;
    }

    protected function renderFile(string $file, array $params = []): Response
    {
        $view = $this->getView();
        $view->setRenderDirectory($this->viewsDir);
        $pageContent = $view->renderFile($file, $params);
        if (isset($this->layout)) {
            $params = ArrayHelper::merge($this->getLayoutParams(), $params);
            $params['content'] = $pageContent;
            $content = $view->renderFile($this->layout, $params);
        }
        return new Response($content);
    }

    protected function downloadFile(string $fileName, string $aliasFileName = null): Response
    {
        $aliasFileName = $aliasFileName ?? basename($fileName);
        $content = file_get_contents($fileName);
        return $this->downloadFileContent($content, $aliasFileName);
    }

    protected function downloadFileContent(string $content, string $aliasFileName = null): Response
    {
        $response = new Response($content);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $aliasFileName
        );
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }

    protected function render(string $file, array $params = []): Response
    {
        $view = $this->getView();
        $view->setRenderDirectory($this->viewsDir);
        $pageContent = $view->render($file, $params);
        if (isset($this->layout)) {
            $params = ArrayHelper::merge($this->getLayoutParams(), $params);
            $params['content'] = $pageContent;
            $content = $view->renderFile($this->layout, $params);
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

    protected function redirectToBack(Request $request, string $fallbackUrl = null): RedirectResponse
    {
        $referer = $request->headers->get('referer') ?? $fallbackUrl;
        //$request->getSession()->setFlash('error', $exception->getMessage());
        return new RedirectResponse($referer);
    }

    protected function redirect(string $url, int $status = HttpStatusCodeEnum::MOVED_TEMPORARILY): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }
}
