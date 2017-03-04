<?php
        	
namespace Addons\HumanTranslation\Model;
use Home\Model\WeixinModel;
        	
/**
 * HumanTranslation的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'HumanTranslation' ); // 获取后台插件的配置参数
		//dump($config);
		if($config['picurl']) {
			$picurl = get_cover_url($config['picurl']);
		}
		$param ['token'] = get_token ();
		$param ['openid'] = get_openid ();
		$url = addons_url ( 'HumanTranslation://Wap/index', $param );

		//组装微信需要的图文数据，格式是固定的
		$articles [0] = array (
			'Title' => $config['translationName'],
			'Description' => $config['intro'],
			'PicUrl' => $picurl,
			'Url' => $url
		);
		$res = $this->replyNews ( $articles );
		return $res;
	}

	// 关注公众号事件
	public function subscribe() {
		return true;
	}

	// 取消关注公众号事件
	public function unsubscribe() {
		return true;
	}

	// 扫描带参数二维码事件
	public function scan() {
		return true;
	}

	// 上报地理位置事件
	public function location() {
		return true;
	}

	// 自定义菜单事件
	public function click() {
		return true;
	}
}
        	