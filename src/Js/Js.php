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
 * Js.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace CorpWechat\Js;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use EasyWeChat\Core\AbstractAPI;
use EasyWeChat\Support\Str;
use EasyWeChat\Support\Url as UrlHelper;

/**
 * Class Js.
 */
class Js extends \EasyWeChat\Js\Js
{
   

    /**
     * Api of ticket.
     */
    const TICKET_CACHE_PREFIX = 'overtrue.corp.wechat.jsapi_ticket.';
    const API_TICKET = 'https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket';

    /**
     * Api of group ticket
     */
    
    const TICKET_GROUP_CACHE_PREFIX = 'overtrue.corp.group.wechat.jsapi_ticket.';
    const API_GROUP_TICKET = 'https://qyapi.weixin.qq.com/cgi-bin/ticket/get';

   
    // 由于上面两个 const变量 有变动, 所以下面方法也必须要写出来了, 不然会直接调用到父类的方法
    public function ticket()
    {

        $key = self::TICKET_CACHE_PREFIX.$this->getAccessToken()->getAppId();
        if ($ticket = $this->getCache()->fetch($key)) {
            return $ticket;
        }
        $result = $this->parseJSON('get', [self::API_TICKET, ['type' => 'jsapi']]);
        $this->getCache()->save($key, $result['ticket'], $result['expires_in'] - 500);

        return $result['ticket'];
    }


    //需要的参数列表
	/*
    var config = <?php echo $wechat_js->config_group([
           'departmentIds' => [0],    // 非必填，可选部门ID列表（如果ID为0，表示可选管理组权限下所有部门）
            // 'tagIds' => [0],    // 非必填，可选标签ID列表（如果ID为0，表示可选所有标签）
            // 'userIds' => [],    // 非必填，可选用户ID列表
            'mode' => 'multi',    // 必填，选择模式，single表示单选，multi表示多选
            // 'type' => ['department','tag','user'],    // 必填，选择限制类型，指定department、tag、user中的一个或者多个
            'type' => ['user'],    // 必填，选择限制类型，指定department、tag、user中的一个或者多个
            'selectedDepartmentIds' => [],    // 非必填，已选部门ID列表
            'selectedTagIds' => [],    // 非必填，已选标签ID列表
            'selectedUserIds' => [],    // 非必填，已选用户ID列表
        ]) ?>;

    console.log(config);
    WeixinJSBridge.invoke("openEnterpriseContact", config, function(res) {}

    */
    public function config_group(array $params,$json = true)
    {
        $config = $this->signature_of_system_params();
        
        $config['params'] = $params;
        // dd($config);
        return $json ? json_encode($config) : $config;
    }

    // 生成管理组 ticket 凭据
    public function ticket_with_groupId(){
    	$key = self::TICKET_GROUP_CACHE_PREFIX.$this->getAccessToken()->getAppId();
        if ($ticket = $this->getCache()->fetch($key)) {
            return $ticket;
        }
        $result = $this->parseJSON('get', [self::API_GROUP_TICKET, ['type' => 'contact']]);

        $cached_value = [
            'ticket'=>$result['ticket'],
            'group_id'=>$result['group_id'],
        ];

        $this->getCache()->save($key, $cached_value, $result['expires_in'] - 500);
        return $cached_value;
    }


    // 生成管理组 js_sdk 签名和其他需要系统配置的参数
    public function signature_of_system_params($url = null, $nonce = null, $timestamp = null)
    {
        $url = $url ? $url : $this->getUrl();
        $nonce = $nonce ? $nonce : Str::quickRandom(10);
        $timestamp = $timestamp ? $timestamp : time();

        $ticket_with_groupId =$this->ticket_with_groupId();
        // dd($ticket_with_groupId);
        $ticket = $ticket_with_groupId['ticket'];
        $group_id = $ticket_with_groupId['group_id'];

        // dd($ticket,$group_id);
        $signature = "group_ticket={$ticket}&noncestr={$nonce}&timestamp={$timestamp}&url={$url}";
        // dd($signature);
      	$signature = sha1($signature);
        $sign = [
                 // 'appId' => $this->getAccessToken()->getAppId(),
                 'groupId' => $group_id ,
                 'timestamp' => $timestamp ,
                 'nonceStr' => $nonce,
                 'signature' => $signature,
                 // 'url' => $url,
                ];

        return $sign;
    }


  

}
