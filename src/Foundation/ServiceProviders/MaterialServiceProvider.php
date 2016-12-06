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
 * MaterialServiceProvider.php.
 *
 * This file is part of the wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace CorpWechat\Foundation\ServiceProviders;

use CorpWechat\Material\Material;
use CorpWechat\Material\Temporary;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class MaterialServiceProvider.
 */
class MaterialServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['material'] = function ($pimple) {
            throw new \Exception('not supported yet'); 
            // return new Material($pimple['access_token']);
        };

        $temporary = function ($pimple) {
            return new Temporary($pimple['access_token']);
        };
        // 这里相当有特性, 如果再访问一次 $pimple['material_temporary'], 上面的回调函数就会执行...所以只能写成下面的方式

        $pimple['material_temporary'] = $temporary;
        $pimple['material.temporary'] = $temporary;
    }
}
