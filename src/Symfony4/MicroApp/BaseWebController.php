<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnCore\Base\Container\Helpers\ContainerHelper;
use ZnCore\Base\Helpers\LoadHelper;
use ZnCore\Base\Http\Enums\HttpStatusCodeEnum;
use ZnCore\Base\I18Next\Facades\I18Next;
use ZnCore\Base\Text\Helpers\TemplateHelper;
use ZnCore\Domain\Entity\Helpers\EntityHelper;
use ZnLib\Rest\Web\Controller\BaseCrudWebController;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\BuildFormInterface;
use ZnLib\Web\Symfony4\MicroApp\Libs\LayoutManager;
use ZnLib\Web\Symfony4\MicroApp\Traits\ControllerFormTrait;
use ZnLib\Web\Symfony4\MicroApp\Traits\ControllerUrlGeneratorTrait;
use ZnLib\Web\View\View;
use ZnLib\Web\Widgets\BreadcrumbWidget;

abstract class BaseWebController //implements ControllerLayoutInterface
{

    use ControllerUrlGeneratorTrait;
    use ControllerFormTrait;

//    protected $layout = __DIR__ . '/layouts/main.php';
//    protected $layoutParams = [];
    protected $viewsDir;
    protected $view;
    protected $fileExt = 'php';
    private $layoutManager;
    protected $baseUri;

    protected $toastrService;
    protected $breadcrumbWidget;

    public function getBaseRoute(): string
    {
        return trim($this->baseUri, '/');
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function setBaseUri(string $baseUri): void
    {
        $this->baseUri = $baseUri;
    }

    public function getLayoutManager(): LayoutManager
    {
        return $this->layoutManager;
    }

    public function setLayoutManager(LayoutManager $layoutManager): void
    {
        $this->layoutManager = $layoutManager;
    }

    /*public function getLayout(): ?string
    {
        return $this->layout;
    }*/

    /*public function setLayout(?string $layout): void
    {
        $this->layout = $layout;
    }*/

    /*public function getLayoutParams(): array
    {
        return $this->layoutParams;
    }*/

    /*public function setLayoutParams(array $layoutParams): void
    {
        $this->layoutParams = $layoutParams;
    }*/

    /*public function addLayoutParam(string $name, $value): void
    {
        $this->layoutParams[$name] = $value;
    }*/

    public function getViewsDir(): ?string
    {
        return $this->viewsDir;
    }

    public function setViewsDir(string $viewsDir): void
    {
        $this->viewsDir = $viewsDir;
    }

    /**
     * @return ToastrServiceInterface
     * @deprecated
     * @uses getLayoutManager()
     */
    public function getToastrService(): ToastrServiceInterface
    {
        return $this->toastrService ?? $this->getLayoutManager()->getToastrService();
    }

    /**
     * @param ToastrServiceInterface $toastrService
     * @deprecated
     * @uses getLayoutManager()
     */
    public function setToastrService(ToastrServiceInterface $toastrService): void
    {
        $this->toastrService = $toastrService;
    }

    /**
     * @return ToastrServiceInterface
     * @deprecated
     * @uses getLayoutManager()
     */
    public function getBreadcrumbWidget(): BreadcrumbWidget
    {
        return $this->breadcrumbWidget ?? $this->getLayoutManager()->getBreadcrumbWidget();
    }

    /**
     * @param BreadcrumbWidget $breadcrumbWidget
     * @deprecated
     * @uses getLayoutManager()
     */
    public function setBreadcrumbWidget(BreadcrumbWidget $breadcrumbWidget): void
    {
        $this->breadcrumbWidget = $breadcrumbWidget;
    }

    protected function buildForm(BuildFormInterface $form, Request $request): FormInterface
    {
        $formBuilder = $this->createFormBuilder($form);
        $formBuilder->add('save', SubmitType::class, [
            'label' => I18Next::t('core', 'action.send')
        ]);
        return $this->formBuilderToForm($formBuilder, $request);
    }

    protected function createFormInstance($definition = null, object $entity = null): object
    {
        $definition = $definition ?: $this->formClass;
        if (isset($definition)) {
            $form = ContainerHelper::getContainer()->get($definition);
            if (isset($entity)) {
                EntityHelper::setAttributesFromObject($entity, $form);
            }
        } elseif (isset($entity)) {
            $form = $entity;
        } else {
            $form = $this->getService()->createEntity();
        }

//            $entityAttributes = EntityHelper::toArray($entity);
//            $entityAttributes = ArrayHelper::extractByKeys($entityAttributes, EntityHelper::getAttributeNames($form));
        return $form;
    }

    protected function renderTemplate(string $file, array $params = []): Response
    {
        $content = TemplateHelper::loadTemplate($this->viewsDir . '/' . $file . '.' . $this->fileExt, $params);
        /*if (isset($this->layout)) {
//            $params = ArrayHelper::merge($this->getLayoutParams(), $params);
            $params['content'] = $content;
            $content = TemplateHelper::loadTemplate($this->layout, $params);
        }*/
        return new Response($content);
    }

    protected function getView(): View
    {
        if (empty($this->view)) {
            $this->view = \ZnCore\Base\Container\Helpers\ContainerHelper::getContainer()->get(View::class);
//            $this->view = new View();
        }
        return $this->view;
    }

    protected function renderFile(string $file, array $params = []): Response
    {
        $view = $this->getView();
        $view->setRenderDirectory($this->viewsDir);
        $pageContent = $view->renderFile($file, $params);
        /*if (isset($this->layout)) {
//            $params = ArrayHelper::merge($this->getLayoutParams(), $params);
            $params['content'] = $pageContent;
            $content = $view->renderFile($this->layout, $params);
        }*/
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
        /*if (isset($this->layout)) {
//            $params = ArrayHelper::merge($this->getLayoutParams(), $params);
            $params['content'] = $pageContent;
            $content = $view->renderFile($this->layout, $params);
        } else {
            $content = $pageContent;
        }*/
        $content = $pageContent;
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

    /*protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }*/
}
