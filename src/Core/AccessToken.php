<?php
namespace CorpWechat\Core;

use EasyWeChat\Core\Exceptions\HttpException;

class AccessToken extends \EasyWeChat\Core\AccessToken{
	protected $prefix = 'corp.wechat.access_token.';
	// API
    const API_TOKEN_GET = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken';


     public function getTokenFromServer()
    {
        $params = [
            'corpid' => $this->appId,
            'corpsecret' => $this->secret,
        ];

        $http = $this->getHttp();
        $token = $http->parseJSON($http->get(self::API_TOKEN_GET, $params));

        if (empty($token['access_token'])) {
            throw new HttpException('Request AccessToken fail. response: '.json_encode($token, JSON_UNESCAPED_UNICODE));
        }

        return $token;
    }

}