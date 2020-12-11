<?php

namespace ZnLib\Web\Yii2\Widgets\Toastr\assets;

use yii\web\AssetBundle;

class ToastrAsset extends AssetBundle
{

    public $baseUrl = 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4';
    public $css = [
        'toastr.min.css',
    ];
    public $js = [
        'toastr.min.js',
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
