<?php
namespace CorpWechat\Notice;

use EasyWeChat\Core\AbstractAPI;
use EasyWeChat\Support\Collection;


class Notice extends AbstractAPI{
	const API_MESSAGE_SEND = 'https://qyapi.weixin.qq.com/cgi-bin/message/send';

	public function message($message)
    {
        $messageBuilder = new MessageBuilder($this);
        return $messageBuilder->message($message);
    }

    // @todo 这里要考虑如何添加多个用户, 按部门, 按标签等
    // $message 的组装逻辑见Notice/MessageBuilder
	public function send($message)
    {
        return $this->parseJSON('json', [self::API_MESSAGE_SEND, $message]);
    }
}