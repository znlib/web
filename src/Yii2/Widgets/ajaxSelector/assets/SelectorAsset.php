<?php

namespace ZnLib\Web\Yii2\Widgets\ajaxSelector\assets;

use yii\web\AssetBundle;

class SelectorAsset extends AssetBundle
{
	public $sourcePath = '@yii2rails/extension/widget/ajaxSelector/assets/dist';
	public $js = [
		'js/main.js',
	];
	public $depends = [
		'yii2bundle\applicationTemplate\common\assets\main\ScriptAsset',
        'yii2bundle\rest\web\assets\rest\RestAsset',
	];
}
