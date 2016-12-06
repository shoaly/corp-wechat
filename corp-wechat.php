<?php

return [
    /**
     * Debug 模式，bool 值：true/false
     *
     * 当值为 false 时，所有的日志都不会记录
     */
    'debug'  => false,

    /**
     * 账号基本信息，请从微信公众平台/开放平台获取
     */
     
    'corp_id'  => '必填',         // CorpId 
    'secret'  => '必填',     // Secret
    'token'   => '非必填',          // Token
    'aes_key' => '非必填',                    // EncodingAESKey

    /**
     * 日志配置
     *
     * level: 日志级别, 可选为：
     *         debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level' => 'debug',
        'file'  => '/tmp/easywechat.log',
    ],

    /**
     * OAuth 配置
     *
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址
     */
    'oauth' => [
        'callback' => '以http://....开始的回调网址',
    ],

    /**
     * 微信支付
     */
    'payment' => [

        // 'sub_app_id'      => '',
        // 'sub_merchant_id' => '',
        // ...
    ],

    /**
     * Guzzle 全局设置
     *
     * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
     */
    'guzzle' => [
        'timeout' => 3.0, // 超时时间（秒）
        'verify' => false, // 关掉 SSL 认证（强烈不建议！！！） @todo 上https证书的时候一起看
    ],

    // 所有应用的agent id
    'applications'=>[
        '督办系统'=>10,
    ],

];
