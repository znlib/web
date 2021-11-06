<?php

namespace ZnLib\Web\View;

use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;

class Js
{

    private static $code = '';
    private static $files = [];

    public function registerFile(string $file, array $options = [])
    {
        self::$files[] = [
            'file' => $file,
            'options' => $options,
        ];
    }

    public function getFiles(): array
    {
        return self::$files;
    }

    public function resetFiles()
    {
        self::$files = [];
    }

    public function registerCode(string $code)
    {
        self::$code .= PHP_EOL . $code . PHP_EOL;
    }

    public function getCode(): string
    {
        return self::$code;
    }

    public function resetCode()
    {
        self::$code = '';
    }

    public function registerVar(string $name, $value)
    {
        if (is_object($value)) {
            $value = ArrayHelper::toArray($value);
        }
        $json = json_encode($value);
        $code = "$name = " . $json . ";";
        $this->registerCode($code);
    }
}
