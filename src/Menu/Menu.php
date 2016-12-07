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
 * Menu.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace CorpWechat\Menu;

use EasyWeChat\Core\AbstractAPI;

/**
 * Class Menu.
 */
class Menu extends AbstractAPI
{
    const API_CREATE = 'https://qyapi.weixin.qq.com/cgi-bin/menu/create';
    const API_GET = 'https://qyapi.weixin.qq.com/cgi-bin/menu/get';
    const API_DELETE = 'https://qyapi.weixin.qq.com/cgi-bin/menu/delete';
   
    /**
     * Get all menus.
     *
     * @return \EasyWeChat\Support\Collection
     */
    public function get($agent_id)
    {
        return $this->parseJSON('get', [self::API_GET,['agentid'=>$agent_id]]);
    }

   

    /**
     * Add menu.
     *
     * @param array $buttons
     * @param array $matchRule
     *
     * @return bool
     */
    public function create($agent_id,array $buttons)
    {
        $result = $this->parseJSON('json', [self::API_CREATE, ['button' => $buttons],['agentid'=>$agent_id]]);
        if(isset($result['errcode']) && $result['errcode'] ==0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Destroy menu.
     *
     * @param int $menuId
     *
     * @return bool
     */
    public function destroy($agent_id)
    {

        $result =  $this->parseJSON('get', [self::API_DELETE,['agentid'=>$agent_id]]);

        if(isset($result['errcode']) && $result['errcode'] ==0){
            return true;
        }else{
            return false;
        }

    }

}
