<?php
        	
namespace Addons\Love\Model;
use Home\Model\WeixinModel;
        	
/**
 * Love的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Love' ); // 获取后台插件的配置参数	
		//dump($config);
		$param['token']=get_token();
		$param['openid']=get_openid();
		$url=addons_url('Love://Love/show',$param);
		$articles[0]=array(
			'Title'=>'微信表白墙',
			'Description'=>'请勇敢说出你的爱',
			'PicUrl'=>'http://pic21.nipic.com/20120519/6589089_105229006397_2.jpg',
			'Url'=>$url
		);
		$res=$this->replyNews($articles);
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