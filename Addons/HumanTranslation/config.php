<?php
return array (
	'is_close'=>array(
		'title' => '开启消息模板',
		'type' => 'radio',
		'options'=>array(
				0=>'开启',
				1=>'关闭'
		),
		'value' => '0',
		'tip'=>''
	),
	'translateResult' => array (
		'title' => '翻译订单通知',
		'type' => 'text',
		'value' => '',
		'tip'=>'获得翻译通知 模板 编号 TM00483'
	),
	'cashTemplate' => array (
		'title' => '返现到账通知 模板ID',
		'type' => 'text',
		'value' => '',
		'tip'=>'返现到账通知 模板 编号 OPENTM205223929'
	),
	/**/
	'keywords'=>array(
		'title'=>'关键词:',
		'tip'=>'人工翻译 —— 当用户输入该关键词时，将会触发此回复。'
	),
	'index'=>array(
		'title'=>'首页地址:',
		'tip'=>'http://www.selanyimei.com/index.php?s=/addon/HumanTranslation/Wap/index/publicid/2.html'
	),
	'translationName'=>array(
		//配置在表单中的键名 ,这个会是config[forumname]
		'title'=>'人工翻译:',//表单的文字
		'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'亿次元科技官方人工翻译',		 //表单的默认值
		'tip'=>''
	),
	'picurl'=>array(
		'title'=>'回复图片:',//表单的文字
		'type'=>'picture',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'',			 //表单的默认值
		'tip'=>''
	),
	'intro'=>array(
		'title'=>'图文回复介绍:',//表单的文字
		'type'=>'textarea',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'人工翻译 - 点击进入',			 //表单的默认值
		'tip'=>''
	),
	'term'=>array(
		'title'=>'服务说明:',//表单的文字
		'type'=>'editor',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'',			 //表单的默认值
		'tip'=>''
	),

	'isopen'=>array(
		'title'=>'开启/关闭人工翻译:',//表单的文字
		'type'=>'radio',		 //表单的类型：text、textarea、checkbox、radio、select等
		'options'=>array(
			'1'=>'开启',
			'0'=>'关闭',
		),
		'value'=>'0',			 //表单的默认值
		'tip'=>''
	),
	'pv'=>array(
		'title'=>'访问量:',//表单的文字
		'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'0',			 //表单的默认值
		'tip'=>''
	),
	'mediatorFee'=>array(
		'title'=>'平台费用百分比:',//表单的文字
		'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'0.02',			 //表单的默认值
		'tip'=>'用2位数小数点表示'
	),
	'mobile'=>array(
		'title'=>'联系电话:',//表单的文字
		'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'15909581102',			 //表单的默认值
		'tip'=>''
	),
	'wechat'=>array(
		'title'=>'微信:',//表单的文字
		'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'xiaxue857773627',			 //表单的默认值
		'tip'=>''
	),
);