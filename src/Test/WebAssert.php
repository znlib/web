<?php

namespace ZnLib\Web\Test;

use Symfony\Component\DomCrawler\Crawler;
use ZnTool\Test\Asserts\BaseAssert;

class WebAssert extends BaseAssert
{

    protected $crawler;

    public function __construct($name = null, array $data = [], $dataName = '', Crawler $crawler = null)
    {
        parent::__construct($name, $data, $dataName);
        $this->crawler = $crawler;
    }

    public function assertContainsContent(string $content)
    {
        $this->assertContains($content, $this->crawler->html());
        return $this;
    }

    public function assertFormValues(string $buttonValue, array $values)
    {
        $form = $this->crawler->selectButton($buttonValue)->form();
        $this->assertArraySubset($values, $form->getValues());
        return $this;
    }

    public function assertUnauthorized()
    {
        $this->assertContains('Логин или телефон', $this->crawler->html());
        return $this;
    }

    public function assertIsFormError()
    {
        $this->assertContains('Has errors!', $this->crawler->html());
        return $this;
    }

    public function assertIsNotFormError()
    {
        $this->assertNotContains('Has errors!', $this->crawler->html());
        return $this;
    }

    public function assertFormError($message)
    {
        //$this->assertIsFormError();
        $this->assertContains($message, $this->crawler->html());
        return $this;
    }
}
