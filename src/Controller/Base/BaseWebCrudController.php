<?php

namespace ZnLib\Web\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use ZnCore\Arr\Helpers\ArrayHelper;
use ZnDomain\Validator\Exceptions\UnprocessibleEntityException;
use ZnDomain\Validator\Helpers\ValidationHelper;
use ZnDomain\Entity\Helpers\EntityHelper;
use ZnDomain\Entity\Interfaces\EntityIdInterface;
use ZnDomain\Query\Entities\Query;
use ZnDomain\Service\Base\BaseCrudService;
use ZnDomain\Service\Interfaces\CrudServiceInterface;
use ZnLib\Web\Controller\Enums\CrudControllerActionEnum;
use ZnLib\Web\Controller\Helpers\WebQueryHelper;
use ZnLib\Web\Form\Interfaces\BuildFormInterface;
use ZnLib\Web\Html\Helpers\Url;
use ZnUser\Rbac\Domain\Enums\Rbac\ExtraPermissionEnum;

abstract class BaseWebCrudController extends BaseWebController
{

//    use ControllerFormTrait;

    /** @var BaseCrudService */
    protected $service;
    protected $formClass;
    protected $filterModel;

    protected function setService(CrudServiceInterface $service)
    {
        $this->service = $service;
    }

    public function getService(): BaseCrudService
    {
        return $this->service;
    }

    public function setFilterModel(?string $filterModel): void
    {
        $this->filterModel = $filterModel;
    }

    /*protected function getFilterModelInstance(): ValidateEntityInterface {
        return ;
    }*/

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
        if ($with) {
            $query->with($with);
        }
        return $query;
    }

    private function forgeFilterModel(Request $request): object
    {

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

    private function removeEmptyParameters(array $filterAttributes): array
    {
        foreach ($filterAttributes as $attribute => $value) {
            if ($value === '') {
                unset($filterAttributes[$attribute]);
            }
        }
        return $filterAttributes;
    }

    protected function getTitleFromEntity(object $entity): string
    {
        return EntityHelper::getAttribute($entity, $this->titleAttribute());
    }

    public function index(Request $request): Response
    {
        $this->getView()->addAttribute('title', 'list');
        $query = $this->prepareQuery(CrudControllerActionEnum::INDEX, $request);
        $query = WebQueryHelper::getAllParams($request->query->all(), $query);
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
            'baseRoute' => $this->getBaseRoute(),
//            'filterModel' => $filterModel,
        ]);
    }

    public function view(Request $request): Response
    {
        $query = $this->prepareQuery(CrudControllerActionEnum::VIEW, $request);
        $id = $request->query->get('id');
        $entity = $this->getService()->findOneById($id, $query);
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
            'baseRoute' => $this->getBaseRoute(),
        ]);
        return $this->render('view', $params);
    }

    public function update(Request $request): Response
    {
        $id = $request->query->get('id');
        /** @var BuildFormInterface $form */

        $query = $this->prepareQuery(CrudControllerActionEnum::UPDATE, $request);
        $entity = $this->getService()->findOneById($id, $query);
        $form = $this->createFormInstance($this->formClass, $entity);

        /*if (isset($this->formClass)) {
            $form = $this->createFormInstance();
            EntityHelper::setAttributesFromObject($form, $entity);
        } else {
            $form = $entity;
        }*/

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
            'baseRoute' => $this->getBaseRoute(),
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

        $form = $this->createFormInstance($this->formClass);

        /*if (isset($this->formClass)) {
            $form = ContainerHelper::getContainer()->get($this->formClass);
        } else {
            $form = $this->getService()->createEntity();
        }*/

        $buildForm = $this->buildForm($form, $request);
        if ($buildForm->isSubmitted() && $buildForm->isValid()) {
            try {
                $this->runCreate($form);
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

    protected function runCreate(object $form)
    {
        $this->getService()->create(EntityHelper::toArray($form));
    }
}
