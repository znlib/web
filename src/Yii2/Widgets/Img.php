<?php

namespace ZnLib\Web\Yii2\Widgets;

use yii\base\Widget;
use ZnCore\Base\Legacy\Yii\Helpers\Html;

class Img extends Widget
{
	
	const TYPE_IMG = 'img';
	const TYPE_IMG_DATA = 'img_data';
 
	public $url;
	public $file;
	public $type;

	public function run()
	{
		if($this->type == self::TYPE_IMG) {
		    ?><img src="<?= $this->url ?>" /><?php
        } elseif($this->type == self::TYPE_IMG_DATA) {
			?><img src="<?= Html::getDataUrl($this->file) ?>" /><?php
		}
	}

}
