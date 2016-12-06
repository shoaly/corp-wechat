<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * MessageBuilder.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace CorpWechat\Notice;


use EasyWeChat\Core\Exceptions\InvalidArgumentException;
use EasyWeChat\Core\Exceptions\RuntimeException;
use EasyWeChat\Message\AbstractMessage;
use EasyWeChat\Message\Raw as RawMessage;
use EasyWeChat\Message\Text;



class MessageBuilder{
	protected $agentid;
    protected $message;
    protected $to_users;
    protected $to_tags;
    protected $to_appartments;
    protected $notice;

    public function __construct(Notice $notice)
    {
        $this->notice = $notice;
    }

    public function agent($agentid){
        $this->agentid = $agentid;
        return $this;
    }

     public function send()
    {
        if (empty($this->message)) {
            throw new RuntimeException('No message to send.');
        }

        $transformer = new \EasyWeChat\Staff\Transformer();

        if ($this->message instanceof RawMessage) {
            $message = $this->message->get('content');
        } else {
            $content = $transformer->transform($this->message);
            $message = [
                'agentid' => $this->agentid,
            ];
            if($this->to_users){
                $message['touser'] = implode('|', $this->to_users);
            }else if($this->to_tags){
                $message['totag'] = implode('|', $this->to_tags);
            }else if($this->to_departments){
                $message['toparty'] = implode('|', $this->to_departments);
            }else{
                throw new RuntimeException('to_users || to_tags || to_departments missing');
            }
            $message = array_merge($message, $content);
            // dd(json_encode($message,true));
        }
        return $this->notice->send($message);
    }

    public function message($message)
    {
        if (is_string($message)) {
            $message = new Text(['content' => $message]);
        }

        $this->message = $message;

        return $this;
    }

  
    public function to_users($userids)
    {
        $this->to_users = $userids;
        return $this;
    }

    public function to_tags($tags)
    {
        $this->to_tags = $tags;
        return $this;
    }

    public function to_departments($to_departments)
    {
        $this->to_departments = $to_departments;
        return $this;
    }

   
    /**
     * Return property.
     *
     * @param $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }



}
