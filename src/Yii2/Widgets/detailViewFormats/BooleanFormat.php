<?php

namespace ZnLib\Web\Yii2\Widgets\detailViewFormats;

use ZnCore\Base\Legacy\Yii\Helpers\Html;

class BooleanFormat {
	
	public function run($value) {
		return Html::renderBoolean($value);
	}

}
