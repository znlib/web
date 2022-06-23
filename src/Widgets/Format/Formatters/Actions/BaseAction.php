<?php

namespace ZnLib\Web\Widgets\Format\Formatters\Actions;

use ZnLib\Web\Helpers\Html;
use ZnLib\Web\Helpers\Url;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Domain\Entity\Helpers\EntityHelper;
use ZnCore\Domain\Entity\Interfaces\EntityIdInterface;

class BaseAction
{

    public $baseUrl;
    /** @var EntityIdInterface */
    public $entity;

    public $confirm;
    public $urlAction;
    public $type;
    public $title;
    public $icon;
    public $linkParams = [
        'id' => 'id',
    ];

    public function run()
    {
        $options = [];
        $options['href'] = Url::to($this->generateUrlParams());
        $options['type'] = $this->type;
        $options['title'] = $this->translate($this->title);
        $options['icon'] = $this->icon;
        if ($this->confirm) {
            $options['confirm'] = $this->translate($this->confirm);
        }
        return $this->generateActionTag($options);
    }

    protected function generateUrlParams(): array
    {
        $urlParams = [
            $this->baseUrl . '/' . $this->urlAction,
        ];
        foreach ($this->linkParams as $queryName => $entityAttribute) {
            $urlParams[$queryName] = EntityHelper::getValue($this->entity, $entityAttribute);
        }
        return $urlParams;
    }

    protected function translate($value): string
    {
        if (is_array($value)) {
            return I18Next::t($value[0], $value[1]);
        } else {
            return $value;
        }
    }

    protected function generateActionTag(array $options)
    {
        $options['class'] = "text-decoration-none";
        if (!empty($options['type'])) {
            $options['class'] .= " text-{$options['type']}";
        }
        if (empty($options['label'])) {
            $options['label'] = '';
        }
        if (isset($options['icon'])) {
            $options['label'] .= "<i class=\"{$options['icon']}\"></i>";
        }
        if (!empty($options['confirm'])) {
            $options['data-method'] = 'post';
            $options['data-confirm'] = $options['confirm'];
        }
        return $this->tag('a', $options);
    }

    protected function tag(string $tag, array $options): string
    {
        $content = $options['label'];
        unset($options['label']);
        return Html::tag($tag, $content, $options);
    }
}
