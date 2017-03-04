<?php

namespace Addons\Weiba\Controller;

use Home\Controller\AddonsController;

class BaseController extends AddonsController {
	var $config;
	function _initialize() {
		parent::_initialize ();
		
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
	//取得该id下的全部评论数据
	function getReplyData($id){
		$map ['to_reply_id'] = $id;
		$postReplyComment = M ( 'weiba_reply' )->where ( $map )->order ( 'ctime desc' )->select();
		if(empty($postReplyComment)){
			$postReplyComment=0;
		}else{
			foreach($postReplyComment as $k => $val ){
				//dump($val);
				$postReplyComment  [$k] ['user_info'] = getUserInfo ( $val ['to_uid'] );
				//exit();
				//$postReplyComment=$val;
			}
		}
		//dump($postReplyComment);
		//exit();
		return $postReplyComment;
	}
	//增加浏览量
	function setVisitCount($id){
		D ( 'weiba' )->where ( 'id=' . $id )->setInc ( 'pv' );
	}
}
