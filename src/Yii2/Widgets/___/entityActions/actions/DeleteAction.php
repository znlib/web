<?php

namespace ZnLib\Web\Yii2\Widgets\entityActions\actions;

class DeleteAction extends BaseAction {
	
	public $icon = 'trash';
	public $textType = 'danger';
	public $action = 'delete';
	public $title = ['action', 'delete'];
	public $data = [
		'method' => 'post',
		'confirm' => ['yii', 'Are you sure you want to delete this item?'],
	];
	
}