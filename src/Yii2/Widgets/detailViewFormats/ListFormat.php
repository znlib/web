<?php

namespace ZnLib\Web\Yii2\Widgets\detailViewFormats;

use ZnCore\Base\Legacy\Yii\Helpers\Html;

class ListFormat {
	
	const VIEW_LIST = 'VIEW_LIST';
	const VIEW_INLINE = 'VIEW_INLINE';
	
	public $view = self::VIEW_LIST;
	
	public function run($value) {
		if($this->view == self::VIEW_LIST) {
			return Html::ulRaw($value);
		} else {
			return implode(', ', $value);
		}
	}
	
}
