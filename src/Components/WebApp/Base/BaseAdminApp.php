<?php

namespace ZnLib\Web\Components\WebApp\Base;

use ZnLib\Web\Components\WebApp\Base\BaseWebApp;

abstract class BaseAdminApp extends BaseWebApp
{

    public function appName(): string
    {
        return 'admin';
    }

    public function import(): array
    {
        return ['i18next', 'container', 'rbac', 'symfonyAdmin'];
    }
}
