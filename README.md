# CorpWechat 用了80% overture/wechat的轮子, composer 也依赖 overtrue/wechat:3.1.*
- 目的: 再导入企业号的配置之后, 用 overtrue/wechat的文档和对微信的改进思路无痛使用企业号
- 更详细的文档等待所有模块构建完成之后再补充吧.


## 已经完成的模块: 

```
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
```
## 未完成模块:
```
// 这两个还比较重要
// \EasyWeChat\Foundation\ServiceProviders\QRCodeServiceProvider::class, //制作带事件的二维码
// \EasyWeChat\Foundation\ServiceProviders\MenuServiceProvider::class, //微信菜单


// \EasyWeChat\Foundation\ServiceProviders\UrlServiceProvider::class, //短链接
// \EasyWeChat\Foundation\ServiceProviders\SemanticServiceProvider::class, //语义接口
// \EasyWeChat\Foundation\ServiceProviders\StatsServiceProvider::class, //数据统计接口
// \EasyWeChat\Foundation\ServiceProviders\PaymentServiceProvider::class, //微信支付
// \EasyWeChat\Foundation\ServiceProviders\POIServiceProvider::class, // 门店模块
// \EasyWeChat\Foundation\ServiceProviders\ReplyServiceProvider::class, //自动回复
// \EasyWeChat\Foundation\ServiceProviders\BroadcastServiceProvider::class, //群发消息
// \EasyWeChat\Foundation\ServiceProviders\CardServiceProvider::class, //会员卡
// \EasyWeChat\Foundation\ServiceProviders\DeviceServiceProvider::class, //微信硬件

```
