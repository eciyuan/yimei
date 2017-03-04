<?php

namespace Addons\WeiSite\Controller;

use Home\Controller\AddonsController;

class BaseController extends AddonsController {
	var $config;
	function _initialize() {
		parent::_initialize ();
		$this->assign('nav',null);
		$config = getAddonConfig ( 'WeiSite' );
		$config ['cover_url'] = get_cover_url ( $config ['cover'] );
		$config['background_arr']=explode(',', $config['background']);
		$config ['background_id'] = $config ['background_arr'][0];
		$config ['background'] = get_cover_url ( $config ['background_id'] );
		$this->config = $config;
		$this->assign ( 'config', $config );
		//dump ( $config );
		// dump(get_token());
		
		// 定义模板常量
		$act = strtolower ( _ACTION );
		$temp = $config ['template_' . $act];
		$act = ucfirst ( $act );
		$this->assign ( 'page_title', $config ['title'] );
		define ( 'CUSTOM_TEMPLATE_PATH', ONETHINK_ADDON_PATH . 'WeiSite/View/default/Template' );
	}
	public function index(){
	    $this->display();
	}
	/*  
	 * 马晓成
	 * cateid:分类id
	 * 提取幻灯片分类的id
	 * */
	public function getSlideshowCategory($marking){
	    $map ['marking'] = $marking;
	    $slideshowCategory = M ('WeisiteSlideshowCategory')->where($map)->find();
	    return $slideshowCategory['id'];
	}
}
