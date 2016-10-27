<?php
return array(
    'URL_ROUTER_ON'   => true, //开启路由
	//'配置项'=>'配置值'
    'URL_ROUTE_RULES'=>array(
        'model/:act$'            => 'index/model',
        'model/:act/:id$'        => 'index/model',
        'option/:act$'           => 'config/option',
        'option/:act/:id$'       => 'config/option',
        'user/:act$'             => 'power/user',
        'user/:act/:id$'         => 'power/user',
        'check_log/:act$'        => 'member/check_log',
        'check_log/:act/:id$'    => 'member/check_log',
        'member/:act$'           => 'member/member',
        'member/:act/:id$'       => 'member/member',
        'income/:act$'           => 'finance/income',
        'income/:act/:id$'       => 'finance/income',
        'order/:act$'           => 'finance/order',
        'order/:act/:id$'       => 'finance/order',
        'subsidies/:act$'           => 'member/subsidies',
        'subsidies/:act/:id$'       => 'member/subsidies',
        'flash/:act$'            => 'index/flash',
        'flash/:act/:id$'        => 'index/flash',
        'clist/:act$'            => 'index/clist',
        'clist/:act/:id$'        => 'index/clist',
        'agreement/:act$'            => 'index/agreement',
        'agreement/:act/:id$'        => 'index/agreement',
        'package/:act$'          => 'index/package',
        'package/:act/:id$'      => 'index/package',
        'goods/:act$'          => 'index/goods',
        'goods/:act/:id$'      => 'index/goods',
    ),
    'URL_ROLE' => array('power'),
);
