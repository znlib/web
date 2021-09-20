<?php

namespace ZnLib\Web\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Tests\Enums\UserEnum;
use Tests\Helpers\FixtureHelper;
use ZnTool\Test\Traits\BaseUrlTrait;
use ZnTool\Test\Traits\FixtureTrait;

abstract class BaseWebTest extends TestCase
{

    use FixtureTrait;
    use BaseUrlTrait;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        //$this->authProvider = new RpcAuthProvider($this->rpcProvider);
    }

    protected function setUp(): void
    {
        $this->setBaseUrl($_ENV['WEB_URL']);
        $this->initFixtureProvider($_ENV['API_URL']);
        parent::setUp();
    }

    protected function sendRequest(HttpBrowser $browser, string $uri, string $method = 'GET'): Crawler
    {
        $url = $this->getBaseUrl() . '/' . $uri;
        return $browser->request($method, $url, [], [], [
            //'HTTP_ENV_NAME' => 'test',
        ]);
    }

    protected function createAssert(HttpBrowser $browser): WebAssert
    {
        return new WebAssert(null, [], '', $browser);
    }

    protected function sendForm(HttpBrowser $browser, string $uri, string $buttonValue, array $formValues): Crawler
    {
        $crawler = $this->sendRequest($browser, $uri);
        $form = $crawler->selectButton($buttonValue)->form();
        foreach ($formValues as $name => $value) {
            $form[$name] = $value;
        }
        $crawler = $browser->submit($form);
        return $crawler;
    }

    protected function assertUnauthorized(string $uri, string $method = 'GET')
    {
        $browser = $this->getBrowser();
        $this->sendRequest($browser, $uri, $method);
        $this->createAssert($browser)
            ->assertUnauthorized();
    }

    protected function getBrowser(): HttpBrowser
    {
        $browser = new HttpBrowser(HttpClient::create());
        $browser->setServerParameter('HTTP_ENV_NAME', 'test');
        return $browser;
    }

    protected function getBrowserByLogin(string $login = null, string $password = UserEnum::PASSWORD): HttpBrowser
    {
        $browser = $this->getBrowser();
        if ($login == null) {
            return $browser;
        }
        $this->authByLogin($browser, $login, $password);
        return $browser;
    }

    private function authByLogin(HttpBrowser $browser, string $login = null, string $password = UserEnum::PASSWORD)
    {
        $this->sendForm($browser, 'auth', 'Вход', [
            'form[login]' => $login,
            'form[password]' => $password,
        ]);
    }
}
