<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnCore\Base\Legacy\Yii\Helpers\Url;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Domain\Base\BaseCrudService;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnCore\Domain\Interfaces\Service\CrudServiceInterface;
use ZnCore\Domain\Libs\Query;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\BuildFormInterface;
use ZnLib\Web\Symfony4\MicroApp\Traits\ControllerFormTrait;
use ZnLib\Web\Widgets\BreadcrumbWidget;

abstract class BaseWebCrudController extends BaseWebController
{

    use ControllerFormTrait;

    /** @var BaseCrudService */
    protected $service;
    protected $baseUri;
    protected $toastrService;
    protected $breadcrumbWidget;

    protected function setService(CrudServiceInterface $service)
    {
        $this->service = $service;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function setBaseUri(string $baseUri): void
    {
        $this->baseUri = $baseUri;
    }

    public function getToastrService(): ToastrServiceInterface
    {
        return $this->toastrService;
    }

    public function setToastrService(ToastrServiceInterface $toastrService): void
    {
        $this->toastrService = $toastrService;
    }

    public function getBreadcrumbWidget(): BreadcrumbWidget
    {
        return $this->breadcrumbWidget;
    }

    public function setBreadcrumbWidget(BreadcrumbWidget $breadcrumbWidget): void
    {
        $this->breadcrumbWidget = $breadcrumbWidget;
    }

    public function index(Request $request): Response
    {
        $this->getView()->addAttribute('title', 'list');
        $query = new Query();
        $query->perPage($request->query->get('per-page'));
        $query->page($request->query->get('page'));
        $dataProvider = $this->service->getDataProvider($query);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'baseUri' => $this->getBaseUri(),
        ]);
    }

    protected function titleAttribute(): string
    {
        return 'title';
    }

    public function view(Request $request): Response
    {
        $id = $request->query->get('id');
        $entity = $this->service->oneById($id);
        $this->getBreadcrumbWidget()->add('view', Url::to([$this->getBaseUri() . '/view', 'id' => $id]));
        $title = EntityHelper::getAttribute($entity, $this->titleAttribute());
        $this->getView()->addAttribute('title', $title);
        return $this->render('view', [
            'entity' => $entity,
            'baseUri' => $this->getBaseUri(),
        ]);
    }

    public function update(Request $request): Response
    {
        $id = $request->query->get('id');
        /** @var BuildFormInterface $form */
        $form = $this->service->oneById($id);
        $this->getBreadcrumbWidget()->add('update', Url::to([$this->getBaseUri() . '/update', 'id' => $id]));
        $title = EntityHelper::getAttribute($form, $this->titleAttribute());
        $this->getView()->addAttribute('title', $title);
        $buildForm = $this->buildForm($form, $request);
        if ($buildForm->isSubmitted() && $buildForm->isValid()) {
            try {
                $this->service->updateById($id, EntityHelper::toArray($form));
                $this->getToastrService()->success(['core', 'message.saved_success']);
                return $this->redirect(Url::to([$this->getBaseUri()]));
            } catch (UnprocessibleEntityException $e) {
                $this->setUnprocessableErrorsToForm($buildForm, $e);
            }
        }
        return $this->render('form', [
            'formView' => $buildForm->createView(),
            'baseUri' => $this->getBaseUri(),
        ]);
    }

    public function delete(Request $request): Response
    {
        $id = $request->query->get('id');
        $this->service->deleteById($id);
        $this->getToastrService()->success(['core', 'message.deleted_success']);
        return $this->redirect(Url::to([$this->getBaseUri()]));
    }

    public function create(Request $request): Response
    {
        $this->getBreadcrumbWidget()->add('create', Url::to([$this->getBaseUri() . '/create']));
        $this->getView()->addAttribute('title', 'create');
        $form = $this->service->createEntity();
        $buildForm = $this->buildForm($form, $request);
        if ($buildForm->isSubmitted() && $buildForm->isValid()) {
            try {
                $this->service->create(EntityHelper::toArray($form));
                $this->getToastrService()->success(['core', 'message.saved_success']);
                return $this->redirect(Url::to([$this->getBaseUri()]));
            } catch (UnprocessibleEntityException $e) {
                $this->setUnprocessableErrorsToForm($buildForm, $e);
            }
        }
        return $this->render('form', [
            'formView' => $buildForm->createView(),
        ]);
    }

    protected function buildForm(BuildFormInterface $form, Request $request): FormInterface
    {
        $formBuilder = $this->createFormBuilder($form);
        $formBuilder->add('save', SubmitType::class, [
            'label' => I18Next::t('core', 'action.send')
        ]);
        return $this->formBuilderToForm($formBuilder, $request);
    }
}
