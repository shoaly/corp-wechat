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
 * OAuthServiceProvider.php.
 *
 * This file is part of the wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace CorpWechat\Foundation\ServiceProviders;

use Overtrue\Socialite\SocialiteManager as Socialite;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class OAuthServiceProvider.
 */
class OAuthServiceProvider extends \EasyWeChat\Foundation\ServiceProviders\OAuthServiceProvider
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
        $pimple['oauth'] = function ($pimple) {
            $callback = $this->prepareCallbackUrl($pimple);
            $scopes = $pimple['config']->get('oauth.scopes', []);

            

            $config =    [
                'wechat' => [
                    'open_platform' => $pimple['config']['open_platform'],
                    'client_id' => $pimple['config']['app_id'],
                    'client_secret' => $pimple['config']['secret'],
                    'redirect' => $callback,
                ],
            ];
            // dd($pimple['config']);
            $config = [
                'corp-wechat' => [
                    // 'open_platform' => $pimple['config']['open_platform'],
                    'client_id' => $pimple['config']['corp_id'],
                    'client_secret' => $pimple['config']['secret'],
                    'redirect' => $pimple['config']['oauth']['callback'],
                ],
                'longlive_access_token'=>$pimple['config']['longlive_access_token'],
            ];

            // dd($config);

            $socialite = (new Socialite($config))->driver('corp-wechat');

            // dd($socialite);
            if (!empty($scopes)) {
                $socialite->scopes($scopes);
            }

            return $socialite;
        };
    }

    /**
     * Prepare the OAuth callback url for wechat.
     *
     * @param Container $pimple
     *
     * @return string
     */
    private function prepareCallbackUrl($pimple)
    {
        $callback = $pimple['config']->get('oauth.callback');
        if (0 === stripos($callback, 'http')) {
            return $callback;
        }
        $baseUrl = $pimple['request']->getSchemeAndHttpHost();

        return $baseUrl.'/'.ltrim($callback, '/');
    }
}
