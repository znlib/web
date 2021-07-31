<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Legacy\Yii\Helpers\Url;
use ZnCore\Base\Libs\App\Helpers\ContainerHelper;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Domain\Base\BaseCrudService;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnCore\Domain\Helpers\QueryHelper;
use ZnCore\Domain\Helpers\ValidationHelper;
use ZnCore\Domain\Interfaces\Entity\EntityIdInterface;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityInterface;
use ZnCore\Domain\Interfaces\Service\CrudServiceInterface;
use ZnCore\Domain\Libs\Query;
use ZnLib\Web\Symfony4\MicroApp\Enums\CrudControllerActionEnum;
use ZnLib\Web\Symfony4\MicroApp\Interfaces\BuildFormInterface;
use ZnLib\Web\Symfony4\MicroApp\Traits\ControllerFormTrait;
use ZnLib\Web\Widgets\BreadcrumbWidget;
use ZnSandbox\Sandbox\Casbin\Domain\Enums\Rbac\ExtraPermissionEnum;

abstract class BaseWebCrudController extends BaseWebController
{

    use ControllerFormTrait;

    /** @var BaseCrudService */
    protected $service;
    protected $formClass;
    protected $baseUri;
    protected $toastrService;
    protected $breadcrumbWidget;
    protected $filterModel;

    protected function setService(CrudServiceInterface $service)
    {
        $this->service = $service;
    }

    public function getService(): BaseCrudService
    {
        return $this->service;
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

    protected function titleAttribute(): string
    {
        return 'title';
    }

    public function access(): array
    {
        return [
            'index' => [
                ExtraPermissionEnum::ADMIN_ONLY,
            ],
            'view' => [
                ExtraPermissionEnum::ADMIN_ONLY,
            ],
            'update' => [
                ExtraPermissionEnum::ADMIN_ONLY,
            ],
            'delete' => [
                ExtraPermissionEnum::ADMIN_ONLY,
            ],
            'create' => [
                ExtraPermissionEnum::ADMIN_ONLY,
            ],
        ];
    }

    public function with(): array
    {
        return [];
    }

    protected function prepareQuery(string $action, Request $request): Query
    {
        $query = new Query();
        $with = $this->with();
        if($with) {
            $query->with($with);
        }
        return $query;
    }

    public function index(Request $request): Response
    {
        $this->getView()->addAttribute('title', 'list');
        $query = $this->prepareQuery(CrudControllerActionEnum::INDEX, $request);
        $query = QueryHelper::getAllParams($request->query->all(), $query);
        $query->removeParam(Query::WHERE);
        $query->removeParam(Query::WHERE_NEW);
        $dataProvider = $this->getService()->getDataProvider($query);
        if ($this->filterModel) {
            $filterModel = $this->forgeFilterModel($request);
            $dataProvider->setFilterModel($filterModel);
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'baseUri' => $this->getBaseUri(),
//            'filterModel' => $filterModel,
        ]);
    }

    public function setFilterModel(?string $filterModel): void
    {
        $this->filterModel = $filterModel;
    }

    /*protected function getFilterModelInstance(): ValidateEntityInterface {
        return ;
    }*/
    
    private function forgeFilterModel(Request $request): object {

        $filterAttributes = $request->query->get('filter');
//            $filterAttributes = QueryHelper::getFilterParams($query);
        $filterAttributes = $filterAttributes ? $this->removeEmptyParameters($filterAttributes) : [];
        $filterModel = EntityHelper::createEntity($this->filterModel, $filterAttributes);
        try {
            ValidationHelper::validateEntity($filterModel);
        } catch (UnprocessibleEntityException $e) {
            $errorCollection = $e->getErrorCollection();
            $errors = [];
            foreach ($errorCollection as $errorEntity) {
                $errors[] = $errorEntity->getField() . ': ' . $errorEntity->getMessage();
            }
            throw new BadRequestHttpException(implode('<br/>', $errors));
        }
        return $filterModel;
    }

    private function removeEmptyParameters(array $filterAttributes): array {
        foreach ($filterAttributes as $attribute => $value) {
            if($value === '') {
                unset($filterAttributes[$attribute]);
            }
        }
        return $filterAttributes;
    }

    public function view(Request $request): Response
    {
        $query = $this->prepareQuery(CrudControllerActionEnum::VIEW, $request);
        $id = $request->query->get('id');
        $entity = $this->getService()->oneById($id, $query);
        return $this->showView($entity);
    }

    protected function showView(EntityIdInterface $entity, array $params = []): Response
    {
        $this->getBreadcrumbWidget()->add('view', Url::to([$this->getBaseUri() . '/view', 'id' => $entity->getId()]));
        $title = $this->getTitleFromEntity($entity);
        $this->getView()->addAttribute('title', $title);
        $params = ArrayHelper::merge($params, [
            'entity' => $entity,
            'baseUri' => $this->getBaseUri(),
        ]);
        return $this->render('view', $params);
    }

    protected function getTitleFromEntity(object $entity): string
    {
        return EntityHelper::getAttribute($entity, $this->titleAttribute());
    }

    public function update(Request $request): Response
    {
        $id = $request->query->get('id');
        /** @var BuildFormInterface $form */

        $query = $this->prepareQuery(CrudControllerActionEnum::UPDATE, $request);
        $entity = $this->getService()->oneById($id, $query);
        if(isset($this->formClass)) {
            $form = ContainerHelper::getContainer()->get($this->formClass);
//            $entityAttributes = EntityHelper::toArray($entity);
//            $entityAttributes = ArrayHelper::extractByKeys($entityAttributes, EntityHelper::getAttributeNames($form));
            EntityHelper::setAttributesFromObject($form, $entity);
        } else {
            $form = $entity;
        }

        $this->getBreadcrumbWidget()->add('update', Url::to([$this->getBaseUri() . '/update', 'id' => $id]));
        //$title = EntityHelper::getAttribute($form, $this->titleAttribute());
        $title = $this->getTitleFromEntity($entity);
        $this->getView()->addAttribute('title', $title);
        $buildForm = $this->buildForm($form, $request);
        if ($buildForm->isSubmitted() && $buildForm->isValid()) {
            try {
                $this->getService()->updateById($id, EntityHelper::toArray($form));
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
        $this->getService()->deleteById($id);
        $this->getToastrService()->success(['core', 'message.deleted_success']);
        return $this->redirect(Url::to([$this->getBaseUri()]));
    }

    public function create(Request $request): Response
    {
        $this->getBreadcrumbWidget()->add('create', Url::to([$this->getBaseUri() . '/create']));
        $this->getView()->addAttribute('title', 'create');

        if(isset($this->formClass)) {
            $form = ContainerHelper::getContainer()->get($this->formClass);
        } else {
            $form = $this->getService()->createEntity();
        }

        $buildForm = $this->buildForm($form, $request);
        if ($buildForm->isSubmitted() && $buildForm->isValid()) {
            try {
                $this->getService()->create(EntityHelper::toArray($form));
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
