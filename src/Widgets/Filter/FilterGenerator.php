<?php

namespace ZnLib\Web\Widgets\Filter;

use ZnLib\Web\Widgets\Filter\Widgets\Number\NumberFilterWidget;
use ZnLib\Web\Widgets\Filter\Widgets\Select\SelectFilterWidget;
use ZnLib\Web\Widgets\Filter\Widgets\Text\TextFilterWidget;
use ZnCore\Base\Helpers\ClassHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;

class FilterGenerator
{

    public static function generateByDefinition($definition, string $name, $value): string
    {
        $definition = ClassHelper::normalizeComponentConfig($definition);
        $widgetInstance = ClassHelper::createObject($definition['class']);
        $widgetDefaultAttributes = ArrayHelper::toArray($widgetInstance);
        $definition = ArrayHelper::merge($definition, $widgetDefaultAttributes);
        unset($definition['class']);
        $definition['options']['onkeydown'] = 'submitForm(this, event)';
        $definition['name'] = $name;
        $definition['value'] = $value;
        ClassHelper::configure($widgetInstance, $definition);
        return $widgetInstance->run();
    }

    public static function generateByType(array $filterDefinition, string $name, $value, array $options = []): string
    {
        $widgetClass = self::getWidgetClassByType($filterDefinition['type']);
        unset($filterDefinition['type']);
        $options['onkeydown'] = 'submitForm(this, event)';
        $definition = ClassHelper::normalizeComponentConfig($widgetClass);
        $definition = ArrayHelper::merge($definition, [
            'options' => $options,
            'name' => $name,
            'value' => $value,
        ]);
        $definition = ArrayHelper::merge($definition, $filterDefinition);

        $widgetInstance = ClassHelper::createObject($definition);
        return $widgetInstance->run();
    }

    private static function getWidgetClassByType(string $type)
    {
        $widgetAssoc = self::widgetAssoc();
        return ArrayHelper::getValue($widgetAssoc, $type);
    }

    private static function widgetAssoc()
    {
        return [
            'text' => [
                'class' => TextFilterWidget::class,
                'options' => [
                    'class' => 'form-control'
                ],
            ],
            'number' => [
                'class' => NumberFilterWidget::class,
                'options' => [
                    'class' => 'form-control'
                ],
            ],
            'choice' => [
                'class' => SelectFilterWidget::class,
                'options' => [
                    'class' => 'form-control'
                ],
            ],
        ];
    }
}