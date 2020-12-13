<?php

namespace ZnLib\Web\Yii2\Widgets\Toastr\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use ZnYii\Base\Web\Assets\BaseAsset;

class ToastrAsset extends BaseAsset
{

    public $sourcePath = __DIR__ . '/dist';
    public $css = [
        'toastr.min.css',
    ];
    public $js = [
        'toastr.min.js',
    ];
    public $depends = [
        JqueryAsset::class,
    ];
}

/**
 * @example https://codeseven.github.io/toastr/demo.html
 */

/*
// options
toastr.options = {"positionClass": "toast-top-center"}

// fire toastr.js
$('button').on('click',function () {
  toastr.success('Work saved! Sike...')
})
*/

