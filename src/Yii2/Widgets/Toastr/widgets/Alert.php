<?php

namespace ZnLib\Web\Yii2\Widgets\Toastr\widgets;

use yii\base\BaseObject;
use yii\base\Model;
use yii\base\Widget;
use yii2bundle\navigation\domain\entities\AlertEntity;
use ZnCore\Base\Helpers\ClassHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;

class Alert extends Widget
{

    /**
     * information alert
     */
    const TYPE_INFO = 'alert-info';
    /**
     * danger/error alert
     */
    const TYPE_DANGER = 'alert-danger';
    /**
     * success alert
     */
    const TYPE_SUCCESS = 'alert-success';
    /**
     * warning alert
     */
    const TYPE_WARNING = 'alert-warning';
    /**
     * primary alert
     */
    const TYPE_PRIMARY = 'bg-primary';
    /**
     * default alert
     */
    const TYPE_DEFAULT = 'well';
    /**
     * custom alert
     */
    const TYPE_CUSTOM = 'alert-custom';

    public $collection = [];

    private static $all = [];

	/**
	 * Runs the widget
	 */
	public function run()
	{
		$collection = $this->getCollection();
		$this->generateHtml($collection);
	}

    public static function create($content, $type = Alert::TYPE_SUCCESS, $delay = 5000) {
        $entity = new \stdClass();
        if(is_array($content)) {
            $content = I18Next::translateFromArray($content);
        }
        ClassHelper::configure($entity, [
            'type' => $type,
            'content' => $content,
            'delay' => $delay,
        ]);
	    self::$all[] = $entity;
        \Yii::$app->session->setFlash('flash-alert', self::$all);
    }

	private function getCollection() {
		$collection = $this->collection;
		if(empty($collection)) {
			$collection = \Yii::$app->session->getFlash('flash-alert');
		}
		if(empty($collection)) {
			$collection = [];
		}
		return $collection;
	}

	private function generateHtml($collection) {
		foreach($collection as $entity) {
		    $type = str_replace('alert-', '', $entity->type);
//		    dd("toastr.{$type}('{$entity->content}'); \n");
            $this->view->registerJs("toastr.{$type}('{$entity->content}'); \n");
		}
	}

}
