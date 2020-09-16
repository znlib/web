<?php

namespace ZnLib\Web\Yii2\Widgets\Toastr\assets;

use yii\web\AssetBundle;

class ToastrAsset extends AssetBundle
{

    public $baseUrl = '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest';
    public $css = [
        'css/toastr.css',
    ];
    public $js = [
        'js/toastr.min.js',
    ];
    public $depends = [
    ];

    public function init()
    {
        parent::init();
    }
}

/*
// options
toastr.options = {"positionClass": "toast-top-center"}

// fire toastr.js
$('button').on('click',function () {
  toastr.success('Work saved! Sike...')
})
*/
