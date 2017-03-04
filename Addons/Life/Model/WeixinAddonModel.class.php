<?php
        	
namespace Addons\Life\Model;
use Home\Model\WeixinModel;
        	
/**
 * Life的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Life' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	