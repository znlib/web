<?php

namespace ZnLib\Web\Menu\Domain\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Yii;
use ZnCore\Instance\Helpers\ClassHelper;
use ZnCore\Text\Helpers\Inflector;
use ZnCore\Contract\User\Exceptions\ForbiddenException;
use ZnCore\Contract\User\Exceptions\UnauthorizedException;
use ZnCore\Collection\Interfaces\Enumerable;
use ZnDomain\Entity\Helpers\EntityHelper;
use ZnDomain\Query\Entities\Query;
use ZnDomain\Service\Base\BaseCrudService;
use ZnLib\I18Next\Exceptions\NotFoundBundleException;
use ZnLib\I18Next\Facades\I18Next;
use ZnLib\Web\Html\Helpers\Url;
use ZnLib\Web\Menu\Domain\Entities\MenuEntity;
use ZnLib\Web\Menu\Domain\Interfaces\MenuInterface;
use ZnLib\Web\Menu\Domain\Interfaces\Repositories\MenuRepositoryInterface;
use ZnLib\Web\Menu\Domain\Interfaces\Services\MenuServiceInterface;
use ZnUser\Rbac\Domain\Interfaces\Services\ManagerServiceInterface;

class MenuService extends BaseCrudService implements MenuServiceInterface
{

    private $managerService;
    private $urlGenerator;

    public function __construct(
        MenuRepositoryInterface $repository,
        ManagerServiceInterface $managerService,
        UrlGeneratorInterface $urlGenerator = null
    )
    {
        $this->setRepository($repository);
        $this->managerService = $managerService;
        $this->urlGenerator = $urlGenerator;
    }

    public function allByFileName(string $fileName): Enumerable
    {
        $this->getRepository()->setFileName($fileName);
        return $this->findAll();
    }

    public function findAll(Query $query = null): Enumerable
    {
        /** @var MenuEntity[] $collection */
        $collection = parent::findAll($query);
        foreach ($collection as $menuEntity) {
            $this->prepareEntity($menuEntity);
        }
        return $collection;
    }

    private function getRoute(): string
    {
        if (class_exists(Yii::class)) {
            $action = Yii::$app->requestedAction;
            $route = $action->controller->module->id . '/' . $action->controller->id;
            return $route;
        }
        $request = Request::createFromGlobals();
        $route = $request->getPathInfo();
        $route = trim($route, '/');
        return $route;
    }

    private function generateUrl(string $route, array $params = []): string
    {
        if ($this->urlGenerator instanceof UrlGeneratorInterface) {
            return $this->urlGenerator->generate($route, $params);
            try {

            } catch (RouteNotFoundException $e) {

            }
        }
        return Url::to(['/' . $route]);
    }

    private function prepareEntity(MenuEntity $menuEntity)
    {
        if ($menuEntity->getWidget()) {
            /** @var MenuInterface $widgetInstance */
            $widgetInstance = ClassHelper::createObject($menuEntity->getWidget());
            $item = $widgetInstance->menu();
            EntityHelper::setAttributes($menuEntity, $item);
        }

        if ($menuEntity->getModule()) {
            if (class_exists(Yii::class)) {
                $isVisible = array_key_exists($menuEntity->getModule(), Yii::$app->modules);
            } else {
                $isVisible = true;
            }
            $menuEntity->setVisible($isVisible);
            if (!$isVisible) {
                return;
            }
        }

        if ($menuEntity->getRoute()) {
            if ($menuEntity->getLabel() === null && $menuEntity->getLabelTranslate() == null) {
                $this->prepareLabelForRoute($menuEntity);
            }
            if ($menuEntity->getActive() === null) {
                $menuEntity->setActive($this->getRoute() == $menuEntity->getRoute());
            }
            if ($menuEntity->getUrl() === null) {
                $menuEntity->setUrl($this->generateUrl($menuEntity->getRoute()));
            }
        }
        if ($menuEntity->getAccess()) {
            $this->prepareAccess($menuEntity);
        }
        if ($menuEntity->getLabel() == null && $menuEntity->getLabelTranslate() != null) {
            $this->prepareLabelForTranslate($menuEntity);
        }
        if ($menuEntity->getActiveRoutes()) {
            $active = in_array($this->getRoute(), $menuEntity->getActiveRoutes());
            $menuEntity->setActive($active);
        }
        if ($menuEntity->getUrl() == null) {
            $menuEntity->addOption('class', 'nav-header');
        }
        if ($menuEntity->getItems()) {
            $isVisibleItems = false;
            foreach ($menuEntity->getItems() as $itemMenuEntity) {
                $this->prepareEntity($itemMenuEntity);
                if ($itemMenuEntity->getVisible()) {
                    $isVisibleItems = true;
                }
            }
            $menuEntity->setVisible($isVisibleItems);
        }
        /*if($menuEntity->getUrl() == null) {
            foreach ($menuEntity->getItems() as $subMenuEntity) {

            }
            $menuEntity->setVisible(false);
        }*/
        /*if(is_array($menuEntity->getLabel())) {
            $menuEntity->setLabel(I18Next::translateFromArray($menuEntity->getLabel()));
        }*/
    }

    private function prepareLabelForTranslate(MenuEntity $menuEntity)
    {
        $labelTranslate = $menuEntity->getLabelTranslate();
        try {
            $label = I18Next::t($labelTranslate[0], $labelTranslate[1]);
        } catch (NotFoundBundleException $e) {
            $label = "{$labelTranslate[0]}.{$labelTranslate[1]}";
        }
        $menuEntity->setLabel($label);
    }

    private function prepareLabelForRoute(MenuEntity $menuEntity)
    {
        $key = $menuEntity->getController() . '.title';
        try {
            $label = I18Next::t($menuEntity->getModule(), $key);
            if ($label == $key) {
                throw new NotFoundBundleException();
            }
        } catch (NotFoundBundleException $e) {
            $label = $menuEntity->getModule() . ' ' . $menuEntity->getController();
            $label = Inflector::titleize($label);
        }
        $menuEntity->setLabel($label);
    }

    private function prepareAccess(MenuEntity $menuEntity)
    {
        $menuEntity->setVisible(false);
        try {
            $this->managerService->checkMyAccess($menuEntity->getAccess());
            $menuEntity->setVisible(true);
        } catch (ForbiddenException|UnauthorizedException $e) {
            $menuEntity->setVisible(false);
        }

        /*foreach ($menuEntity->getAccess() as $accessItem) {

            if(class_exists(Yii::class)) {
                if (Yii::$app->authManager->checkAccess(Yii::$app->user->id, $accessItem)) {
                    $menuEntity->setVisible(true);
                    break;
                }
            } else {
                $menuEntity->setVisible(true);
            }
        }*/
    }
}
