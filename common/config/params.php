<?php
return [
    'agentRole' => 99999, // 移动端代理角色ID
    'supportEmail' => 'support@example.com',
	'user.passwordResetTokenExpire' => 3600,
	'reg_room_card' => 20,//注册赠送房卡数量
	'reg_agent_card' => 2200,//开通代理赠送房卡数量
	'bind_agent_card' => 10,//绑定代理赠送数量
	'scale' => ['0.2', '0.1'],// 代理对代理的反利
    'wx' => [
        // 公众号
        'appid'=>'wxb9675814f03e9f74',
        // APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
        'appsecret'=>'cbd3ac1fc10cfc87f2b8d5313b99a0d3',
        //MCHID：商户号（必须配置，开户邮件中可查看）
    	'mchid'=>'1489675182',
    	//KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
        'mchkey'=>'7899A29483A7730AD7D796996BB56827',
    	'sslcert_path'=>'',
    	'sslkey_path'=>'',
    	// 代理设置
    	'curl_proxy_host'=>'',
    	'curl_proxy_port' => '',
    	// 上报信息配置 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
    	'report_levenl' => 2,
    ],
    'wxapp' => [
    	//APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
    	'appid'=>'wx4a8393b092912678',
    	// APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
    	'appsecret'=>'1bcdfb02d2e20a5c8e65760e89578b48',
    	//MCHID：商户号（必须配置，开户邮件中可查看）
    	'mchid'=>'1489856302',
    	//KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
    	'mchkey'=>'9302014030574EB3B9174F4E88FF88FF',
    	//=======【证书路径设置】=====================================
        /**
         * TODO：设置商户证书路径
         * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
         * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
         * @var path
         */
    	'sslcert_path'=>'',
    	'sslkey_path'=>'',
    	// 代理设置
    	'curl_proxy_host'=>'',
    	'curl_proxy_port' => '',
    	// 上报信息配置 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
    	'report_levenl' => 2,
    ],
    'alipay' => [
        'appId' => '2017060107396329',
        'publicKey' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsfGjp8P9r+VBXSpWJcS/ocrEnZy5GhkuPyDFLFcWooHOI4bClOC3He+PNq6LPFr5M9wL2NvZE0G+K+yyappZw2oc3VNwe1YJ5HogsrdESHDjkZON7or9aUvqiArRGKREGj8cJuKXa0kMA8Li+8FrsPhXy0cbkLO8gEBOidBBBy/+byITxsXiUkSizz5T49uhvyqm7dKiyENeoRUxIItAq56H5WnJEZgX3KA9N91905Usna/h4fynXOD78w7BsqDk26Em061XHWnB+kC1fjRgt3VCOE+bjbgq5/h6yNQyCqzXnz9YR4btifZh9nGWJsOYjtfY0fFomml6oRV60k2UcwIDAQAB'
    ]
];
