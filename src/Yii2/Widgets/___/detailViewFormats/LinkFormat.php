<?php

namespace ZnLib\Web\Yii2\Widgets\detailViewFormats;

use ZnCore\Base\Libs\ArrayTools\Base\BaseCollection;
use ZnLib\Web\Yii2\Widgets\helpers\WidgetHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Legacy\Yii\Helpers\Html;

class LinkFormat {
	
	public $baseUrl = '';
	public $titleAttribute = 'title';
	public $idAttribute = 'id';
	public $urlTemplate = '/{base}?id={id}';
	
	public function run($value) {
		if($value instanceof BaseCollection || is_array($value)) {
			$result = [];
			foreach($value as $item) {
				$result[] = $this->renderItem($item);
			}
			return Html::ulRaw($result);
		} else {
			return $this->renderItem($value);
		}
	}

	private function renderItem($value) {
		$title = ArrayHelper::getValue($value, $this->titleAttribute);
		$id = ArrayHelper::getValue($value, $this->idAttribute);
		$url = WidgetHelper::renderTemplate($this->urlTemplate, [
			'base' => $this->baseUrl,
			'id' => $id,
		]);
		return $value ? Html::a($title, [$url]) : null;
	}
	
}
