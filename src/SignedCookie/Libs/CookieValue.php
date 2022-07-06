<?php

namespace ZnLib\Web\SignedCookie\Libs;

use ZnCore\DotEnv\Domain\Libs\DotEnv;
use ZnCrypt\Base\Domain\Enums\HashAlgoEnum;

class CookieValue
{

    private $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function encode($value): string
    {
        $cookieValue = json_encode([
            'value' => $value,
            'hash' => $this->generateHash($value),
        ]);
        return $cookieValue;
    }

    public function decode(string $encodedValue)
    {
        $json = json_decode($encodedValue, JSON_OBJECT_AS_ARRAY);
        $hash = $this->generateHash($json['value']);
        if($hash !== $json['hash']) {
            throw new \DomainException('Bad check cookie secure!');
        }
        return $json['value'];
    }

    private function generateHash($value): string {
        $jsonValue = json_encode($value);
        $scopedValue = DotEnv::get('CSRF_TOKEN_ID') . $jsonValue;
        return hash(HashAlgoEnum::SHA256, $scopedValue);
    }
}
