<?php
namespace CorpWechat\Foundation;

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Application.php.
 *
 * Part of Overtrue\WeChat.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015
 *
 * @link      https://github.com/overtrue/wechat
 * @link      http://overtrue.me
 */
// namespace EasyWeChat\Foundation;

use Doctrine\Common\Cache\Cache as CacheInterface;
use Doctrine\Common\Cache\FilesystemCache;
use CorpWechat\Core\AccessToken;
use EasyWeChat\Core\Http;
use EasyWeChat\Support\Log;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Symfony\Component\HttpFoundation\Request;

use EasyWeChat\Foundation\Config;


/**
 * Class Application.
 *
 * @property \EasyWeChat\Server\Guard                    $server
 * @property \EasyWeChat\User\User                       $user
 * @property \EasyWeChat\User\Tag                        $user_tag
 * @property \EasyWeChat\User\Group                      $user_group
 * @property \EasyWeChat\Js\Js                           $js
 * @property \Overtrue\Socialite\SocialiteManager        $oauth
 * @property \EasyWeChat\Menu\Menu                       $menu
 * @property \EasyWeChat\Notice\Notice                   $notice
 * @property \EasyWeChat\Material\Material               $material
 * @property \EasyWeChat\Material\Temporary              $material_temporary
 * @property \EasyWeChat\Staff\Staff                     $staff
 * @property \EasyWeChat\Url\Url                         $url
 * @property \EasyWeChat\QRCode\QRCode                   $qrcode
 * @property \EasyWeChat\Semantic\Semantic               $semantic
 * @property \EasyWeChat\Stats\Stats                     $stats
 * @property \EasyWeChat\Payment\Merchant                $merchant
 * @property \EasyWeChat\Payment\Payment                 $payment
 * @property \EasyWeChat\Payment\LuckyMoney\LuckyMoney   $lucky_money
 * @property \EasyWeChat\Payment\MerchantPay\MerchantPay $merchant_pay
 * @property \EasyWeChat\Reply\Reply                     $reply
 * @property \EasyWeChat\Broadcast\Broadcast             $broadcast
 * @property \EasyWeChat\Card\Card                       $card
 * @property \EasyWeChat\Device\Device                   $device
 */
class Application extends Container
{
    /**
     * Service Providers.
     *
     * @var array
     */
    protected $providers = [
        //oauth认证
        ServiceProviders\OAuthServiceProvider::class,

        //消息推送
        ServiceProviders\NoticeServiceProvider::class,

        // 用户模块
        ServiceProviders\UserServiceProvider::class,

        // h5 js jdk, 这个接口里面 openEnterpriseContact 由于前端没有任何错误提示, 搞得我死去活来啊~~~
        ServiceProviders\JsServiceProvider::class,

        // 媒体资源, 明天来开发这个保证 图片上传可以用
        ServiceProviders\MaterialServiceProvider::class,

        //响应 被动消息
        ServiceProviders\ServerServiceProvider::class,

        // 这两个还比较重要
        ServiceProviders\MenuServiceProvider::class, //微信菜单

        // not supported
        // ServiceProviders\QRCodeServiceProvider::class, //制作带事件的二维码

        // \EasyWeChat\Foundation\ServiceProviders\UrlServiceProvider::class, //短链接
        // \EasyWeChat\Foundation\ServiceProviders\SemanticServiceProvider::class, //语义接口
        // \EasyWeChat\Foundation\ServiceProviders\StatsServiceProvider::class, //数据统计接口
        // \EasyWeChat\Foundation\ServiceProviders\PaymentServiceProvider::class, //微信支付
        // \EasyWeChat\Foundation\ServiceProviders\POIServiceProvider::class, // 门店模块
        // \EasyWeChat\Foundation\ServiceProviders\ReplyServiceProvider::class, //自动回复
        // \EasyWeChat\Foundation\ServiceProviders\BroadcastServiceProvider::class, //群发消息
        // \EasyWeChat\Foundation\ServiceProviders\CardServiceProvider::class, //会员卡
        // \EasyWeChat\Foundation\ServiceProviders\DeviceServiceProvider::class, //微信硬件
    ];

    /**
     * Application constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        parent::__construct();

        $this['config'] = function () use ($config) {
            return new Config($config);
        };

        if ($this['config']['debug']) {
            error_reporting(E_ALL);
        }

        $this->registerProviders();
        $this->registerBase();
        $this->initializeLogger();

        Http::setDefaultOptions($this['config']->get('guzzle', ['timeout' => 5.0]));

        foreach (['app_id', 'secret'] as $key) {
            !isset($config[$key]) || $config[$key] = '***'.substr($config[$key], -5);
        }

        Log::debug('Current config:', $config);
    }

    /**
     * Add a provider.
     *
     * @param string $provider
     *
     * @return Application
     */
    public function addProvider($provider)
    {
        array_push($this->providers, $provider);

        return $this;
    }

    /**
     * Set providers.
     *
     * @param array $providers
     */
    public function setProviders(array $providers)
    {
        $this->providers = [];

        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    /**
     * Return all providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * Register providers.
     */
    private function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());

        }
    }

    /**
     * Register basic providers.
     */
    private function registerBase()
    {
        $this['request'] = function () {
            return Request::createFromGlobals();
        };

        if (!empty($this['config']['cache']) && $this['config']['cache'] instanceof CacheInterface) {
            $this['cache'] = $this['config']['cache'];
        } else {
            $this['cache'] = function () {
                // return new FilesystemCache(sys_get_temp_dir());
                // 把cache的目录放到 laravel下面 方便管理
                return new FilesystemCache(storage_path() . "/cache");
            };
        }

        $this['access_token'] = function () {
            return new AccessToken(
                $this['config']['corp_id'],
                $this['config']['secret'],
                $this['cache']
            );
        };
    }

    /**
     * Initialize logger.
     */
    private function initializeLogger()
    {
        if (Log::hasLogger()) {
            return;
        }

        $logger = new Logger('easywechat');

        if (!$this['config']['debug'] || defined('PHPUNIT_RUNNING')) {
            $logger->pushHandler(new NullHandler());
        } elseif ($this['config']['log.handler'] instanceof HandlerInterface) {
            $logger->pushHandler($this['config']['log.handler']);
        } elseif ($logFile = $this['config']['log.file']) {
            $logger->pushHandler(new StreamHandler($logFile, $this['config']->get('log.level', Logger::WARNING)));
        }

        Log::setLogger($logger);
    }
}
