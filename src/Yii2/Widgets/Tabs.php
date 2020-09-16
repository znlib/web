<?php

namespace ZnLib\Web\Yii2\Widgets;

use yii\base\Widget;
use yii\bootstrap\Nav;
use yii2rails\extension\menu\helpers\MenuHelper;

/**
 * Class Tabs
 *
 * @package ZnLib\Web\Yii2\Widgets
 *
 * @deprecated
 */
class Tabs extends Widget
{

	public $id = 'tabs_navigation';
    public $items = [];
	
	public function run() {
		echo Nav::widget([
			'options' => [
				'id' => $this->id,
                'class' => 'nav nav-tabs',
            ],
			'items' => $this->items,
		]);
	}

}
