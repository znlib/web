<?php

namespace ZnLib\Web\Test;

use Symfony\Component\BrowserKit\HttpBrowser;
use ZnTool\Test\Asserts\BaseAssert;

class WebAssert extends BaseAssert
{

    protected $crawler;
    protected $browser;

    public function __construct($name = null, array $data = [], $dataName = '', HttpBrowser $browser)
    {
        parent::__construct($name, $data, $dataName);
        $this->crawler = $browser->getCrawler();
        $this->browser = $browser;
    }

    public function assertContainsContent(string $content)
    {
        $html = html_entity_decode($this->crawler->html());
        $this->assertContains($content, $html);
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
        $html = html_entity_decode($this->crawler->html());
        $this->assertContains('Логин', $html);
        return $this;
    }

    public function assertIsFormError()
    {
        $html = html_entity_decode($this->crawler->html());
        $this->assertContains('Has errors!', $html);
        return $this;
    }

    public function assertIsNotFormError()
    {
        $html = html_entity_decode($this->crawler->html());
        $this->assertNotContains('Has errors!', $html);
        return $this;
    }

    /*public function assertFormError($message)
    {
        //$this->assertIsFormError();
        $this->assertContains($message, $this->crawler->html());
        return $this;
    }*/
}
