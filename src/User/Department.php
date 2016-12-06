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
 * Group.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace CorpWechat\User;
use EasyWeChat\Core\AbstractAPI;

/**
 * Class Group.
 */
class Department extends AbstractAPI
{
    const API_LIST = 'https://qyapi.weixin.qq.com/cgi-bin/department/list';
    const API_CREATE = 'https://qyapi.weixin.qq.com/cgi-bin/department/create';
    const API_UPDATE = 'https://qyapi.weixin.qq.com/cgi-bin/department/update';
    const API_DELETE = 'https://qyapi.weixin.qq.com/cgi-bin/department/delete';


    /**
     * Create group.
     *
     * @param string $dep
     *
     * @return int
     */

    public function create($dep)
    {
        $response = $this->parseJSON('json', [self::API_CREATE, $dep]);
        if(isset($response['errcode']) && $response['id'] && $response['errcode'] == 0){
            return $response['id'];
        }else{
            return false;
        }
    } 

  

    /**
     * List all groups.
     *
     * @return array
     */
    public function children($parent_id)
    {
        $data = $this->parseJSON('get', [self::API_LIST]);
        return isset($data['department']) ? $data['department'] : [];
    }

    /**
     * Update a group name.
     *
     * @param int    $groupId
     * @param string $name
     *
     * @return bool
     */
    public function update($dep, $not_used = false)
    {

        $response = $this->parseJSON('json', [self::API_UPDATE, $dep]);

        if(isset($response['errcode']) && $response['errcode'] == 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Delete group.
     *
     * @param int $groupId
     *
     * @return bool
     */
    public function delete($department_id)
    {
        $params = ['id' => $department_id];
        $response = $this->parseJSON('get', [self::API_DELETE, $params]);
        if(isset($response['errcode']) && $response['errcode'] == 0){
            return true;
        }else{
            return false;
        }

    }



}
