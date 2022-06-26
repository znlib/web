<?php

namespace ZnLib\Web\AdminApp\Base;

use ZnLib\Web\WebApp\Base\BaseWebApp;

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
