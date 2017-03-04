<?php
        	
namespace Addons\Weicj\Model;
use Home\Model\WeixinModel;
        	
/**
 * Weicj的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Weicj' ); // 获取后台插件的配置参数	
		//dump($config);
		
		$map ['token'] = get_token ();
		
		//获取关键词表中对应插件的对应ID
		if (! empty ( $keywordArr ['aim_id'] )) {
			$map ['id'] = $keywordArr ['aim_id'];
		}

		// 获取回复数据
		$info = M ( 'weicj' )->where ( $map )->order ( 'id desc' )->select ();

		if (! $info) {
			return false;
		}

		// 其中token和openid这两个参数一定要传，否则程序不知道是哪个微信用户进入了系统
		
		$param ['token'] = get_token ();
		$param ['openid'] = get_openid ();

		

		// 组装微信需要的图文数据，格式是固定的
		$param ['id'] = $info [0]['id'];
		$url = addons_url ( 'Weicj://Weicj/index', $param );

		$articles [0] = array (
				'Title' => $info [0]['title'],
				'Description' => $info [0]['intro'],
				'PicUrl' => get_cover_url ( $info [0]['cover'] ),
				'Url' => $url
		);


		$this->replyNews ( $articles );

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
        	