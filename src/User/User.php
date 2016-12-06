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
 * User.php.
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
 * Class User.
 */
class User extends AbstractAPI
{
    // const API_GET = 'https://api.weixin.qq.com/cgi-bin/user/info';
    const API_GET = 'https://qyapi.weixin.qq.com/cgi-bin/user/get';
    const API_CREATE = 'https://qyapi.weixin.qq.com/cgi-bin/user/create';
    const API_UPDATE = 'https://qyapi.weixin.qq.com/cgi-bin/user/update';
    const API_DELETE = 'https://qyapi.weixin.qq.com/cgi-bin/user/delete';
    const API_BATCH_DELETE = 'https://qyapi.weixin.qq.com/cgi-bin/user/batchdelete';

    const API_DEPARTMENT_LIST = 'https://qyapi.weixin.qq.com/cgi-bin/user/list';
    const API_TAG_LIST= 'https://qyapi.weixin.qq.com/cgi-bin/tag/get';
  

    const FETCH_CHILD = 1;
    const NOT_FETCH_CHILD = 0;

    /**
     * Fetch a user by open id.
     *
     * @param string $openId
     * @param string $lang
     *
     * @return array
     */
    public function get($userid,$lang = 'zh_CN')
    {
        $params = [
          'userid' => $userid,
        ];

        return $this->parseJSON('get', [self::API_GET, $params]);
    }

    public function create($user){
       

        // dd($user);
        $response = $this->parseJSON('json', [self::API_CREATE, $user]);
        if(isset($response['errcode']) && $response['errcode'] == 0){
            return true;
        }else{
            return false;
        }
    }

    public function update($user){
      

        $response = $this->parseJSON('json', [self::API_UPDATE, $user]);
        if(isset($response['errcode']) && $response['errcode'] == 0){
            return true;
        }else{
            return false;
        }
    }

    public function delete($userid){
        $params = [
          'userid'=>$userid,
        ];
        $response = $this->parseJSON('get', [self::API_DELETE, $params]);
        if(isset($response['errcode']) && $response['errcode'] == 0){
            return true;
        }else{
            return false;
        }
    }

    public function batch_delete($users){
        $params = [
             "useridlist"=> $users
        ];
        $response = $this->parseJSON('json', [self::API_BATCH_DELETE, $params]);
        if(isset($response['errcode']) && $response['errcode'] == 0){
            return true;
        }else{
            return false;
        }
    }


    // 根据 部门id 批量获取用户
    // ?access_token=ACCESS_TOKEN&department_id=DEPARTMENT_ID&fetch_child=FETCH_CHILD&status=STATUS
   public function getUsersByDepartment($department_id,$fetch_child = self::FETCH_CHILD){
        $params = [
                   'department_id' => $department_id,
                   //0获取全部成员，1获取已关注成员列表，2获取禁用成员列表，4获取未关注成员列表。status可叠加,未填写则默认为4
                   'status' => 0, 
                  ];

        $users = $this->parseJSON('get', [self::API_DEPARTMENT_LIST, $params]);
        return isset($users['userlist']) ? $users['userlist'] : [];
   }

    // 根据 部门id 批量获取用户
   public function getUsersByTag($tag_id){
        $params = [
                   'tagid' => $tag_id,
                   //0获取全部成员，1获取已关注成员列表，2获取禁用成员列表，4获取未关注成员列表。status可叠加,未填写则默认为4
                  ];

        $users = $this->parseJSON('get', [self::API_TAG_LIST, $params]);
        return isset($users['userlist']) ? $users['userlist'] : [];
   }



}
