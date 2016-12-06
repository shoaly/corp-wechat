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
 * Class Tag.
 */
class Tag extends AbstractAPI 
{
    const API_CREATE = 'https://qyapi.weixin.qq.com/cgi-bin/tag/create';
    const API_UPDATE = 'https://qyapi.weixin.qq.com/cgi-bin/tag/update';
    const API_DELETE = 'https://qyapi.weixin.qq.com/cgi-bin/tag/delete';

    const API_ADD_TAG = 'https://qyapi.weixin.qq.com/cgi-bin/tag/addtagusers';
    const API_REMOVE_TAG = 'https://qyapi.weixin.qq.com/cgi-bin/tag/deltagusers';
    const API_TAGED = 'https://qyapi.weixin.qq.com/cgi-bin/tag/get';

    const API_LIST = 'https://qyapi.weixin.qq.com/cgi-bin/tag/list';


    // const API_USER_TAGS = 'https://api.weixin.qq.com/cgi-bin/tags/getidlist';
    // const API_MEMBER_BATCH_TAG = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging';
    // const API_MEMBER_BATCH_UNTAG = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging';
    // const API_USERS_OF_TAG = 'https://api.weixin.qq.com/cgi-bin/user/tag/get';

    /**
     * Create tag.
     *
     * @param string $name
     *
     * @return int
     */
    public function create($tag)
    {
        $response = $this->parseJSON('json', [self::API_CREATE, $tag]);
        if(isset($response['errcode']) && $response['errcode'] == 0 && isset($response['tagid'])){
            return $response['tagid'];
        }else{
            return false;
        }
    }

     /**
     * Update a tag name.
     *
     * @param int    $tagId
     * @param string $name
     *
     * @return bool
     */
    public function update($tag)
    {
        // dd($tag);
        $response = $this->parseJSON('json', [self::API_UPDATE,$tag]);
        if(isset($response['errcode']) && $response['errcode'] == 0){
            return true;
        }else{
            return false;
        }
    }


    /**
     * Delete tag.
     *
     * @param int $tagId
     *
     * @return bool
     */
    public function delete($tagId)
    {
        $params = [
           'tagid' => $tagId,
        ];

        $response = $this->parseJSON('get', [self::API_DELETE, $params]);
        if(isset($response['errcode']) && $response['errcode'] == 0){
            return true;
        }else{
            return false;
        }

    }



    /**
     * List all tags.
     *
     * @return array
     */
    public function lists()
    {
        $response = $this->parseJSON('get', [self::API_LIST]);
        if(isset($response['errcode']) && $response['errcode'] == 0 && isset($response['taglist'])){
            return $response['taglist'];
        }else{
            return false;
        }
    }

    // 这里返回的是 打了标签的 用户 和 部门, 不同于用户那边的getUsersByTag, 那个只返回用户
    public function all_taged($tagId){
        $params = [
                   'tagid' => $tagId,
                   //0获取全部成员，1获取已关注成员列表，2获取禁用成员列表，4获取未关注成员列表。status可叠加,未填写则默认为4
                  ];

        $users = $this->parseJSON('get', [self::API_TAGED, $params]);
        $data = [];
        $data['users'] = isset($users['userlist']) ? $users['userlist'] : [];
        $data['departments'] = isset($users['partylist']) ? $users['partylist'] : [];
        return $data;
    }

  
    public function add_tag_to_userids($tagid,$userIds)
    {
        $params = [
            'tagid' => $tagid,
            'userlist'=>$userIds,
        ];

        $response = $this->parseJSON('json', [self::API_ADD_TAG, $params]);
        if(isset($response['errcode']) && $response['errcode'] == 0){
            return true;
        }else{
            return false;
        }

    }


    public function add_tag_to_departmentids($tagid,$department_Ids)
    {
        $params = [
            'tagid' => $tagid,
            'partylist'=>$department_Ids,
        ];

        $response = $this->parseJSON('json', [self::API_ADD_TAG, $params]);
        if(isset($response['errcode']) && $response['errcode'] == 0){
            return true;
        }else{
            return false;
        }

    }

    public function remove_tag_from_userids($tagId,$userIds){
        $params = [
            'tagid' => $tagId,
            'userlist'=>$userIds,
        ];

        $response = $this->parseJSON('json', [self::API_REMOVE_TAG, $params]);
        if(isset($response['errcode']) && $response['errcode'] == 0){
            return true;
        }else{
            return false;
        }

    }

    public function remove_tag_from_departments($tagId,$department_ids){
         $params = [
            'tagid' => $tagId,
            'partylist'=>$department_ids,
        ];

        $response = $this->parseJSON('json', [self::API_REMOVE_TAG, $params]);
        if(isset($response['errcode']) && $response['errcode'] == 0){
            return true;
        }else{
            return false;
        }
    }
}
