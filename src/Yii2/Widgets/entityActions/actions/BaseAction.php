<?php

namespace ZnLib\Web\Yii2\Widgets\entityActions\actions;

use yii\helpers\Url;
use ZnLib\Web\Yii2\Widgets\helpers\WidgetHelper;
use ZnCore\Base\Legacy\Yii\Helpers\Html;
use ZnSandbox\Sandbox\Lang\Yii2\Helpers\LangHelper;

class BaseAction {
	
	public $baseUrl;
	public $id;
	public $iconTemplate = '<i class="fa fa-{name} text-{type}"></i>';
	public $urlTemplate = '/{base}/{action}?id={id}';
	public $linkOptions = [];
	
	public $icon;
	public $textType;
	public $action;
	public $title;
	public $data;
	
	public function render() {
		$iconHtml =  $this->renderTemplate('iconTemplate', [
			'name' => $this->icon,
			'type' => $this->textType,
		]);
		$url = $this->renderTemplate('urlTemplate', [
			'base' => $this->baseUrl,
			'action' => $this->action,
			'id' => $this->id,
		]);
		$url = Url::to($url);
		$anchorOptions = $this->linkOptions;
		$anchorOptions['title'] = LangHelper::extract($this->title);
		if($this->data) {
			$anchorOptions['data'] = $this->data;
		}
		return Html::a($iconHtml, $url, $anchorOptions);
	}
	
	private function renderTemplate($name, $options) {
		$template = $this->{$name};
		return WidgetHelper::renderTemplate($template, $options);
	}
	
}