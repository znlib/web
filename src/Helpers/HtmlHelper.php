<?php

namespace ZnLib\Web\Helpers;

use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Legacy\Yii\Helpers\Html;
use ZnCore\Base\Legacy\Yii\Helpers\Url;

/**
 * Работа с HTML
 */
class HtmlHelper
{

    /**
     * Получить содержимое тэга
     * @param string $string
     * @param string $tagname
     * @return mixed
     */
    public static function getTagContent(string $string, string $tagname)
    {
        $pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
        preg_match($pattern, $string, $matches);
        return $matches[1];
    }

    /*public static function generateDataList(string $id, array $options, string $fieldName)
    {
        $optionsHtml = '';
        foreach ($options as $option) {
            $value = $option[$fieldName];
            $optionsHtml .= Html::tag('option', '', ['value' => $value]);
        }
        return Html::tag('datalist', $optionsHtml, ['id' => $id]);
    }*/

    /**
     * Конвертировать файл в последовательность base64
     * @param string $content
     * @param string $mimeType
     * @return string
     */
    public static function generateBase64Content(string $content, string $mimeType): string
    {
        $base64Content = base64_encode($content);
        return "data:{$mimeType};base64, {$base64Content}";
    }
}
