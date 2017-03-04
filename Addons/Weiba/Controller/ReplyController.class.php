<?php

namespace Addons\Weiba\Controller;

use Home\Controller\AddonsController;
/* 评论管理 */
class ReplyController extends AddonsController {
	var $model;
	function _initialize() {
		$this->model = $this->getModel ( 'weiba_reply' );
		$config = getAddonConfig ( 'Weiba' ); // 获取后台插件的配置参数
		//dump($config);
	}
	public function lists() {
	    $this->assign ( 'checkTopics_button', true );
	    $this->assign ( 'add_button', false );
		$map ['token'] = get_token ();
		$cateArr = M ( 'weiba' )->where ( $map )->getFields ( 'id,weiba_name' );
		$list_data = $this->_get_model_list ( $this->model );
		//dump($cateArr);exit();
		foreach ( $list_data ['list_data'] as &$data ) {
			$data ['weiba_id'] = $cateArr [$data ['weiba_id']];
			$data ['post_uid'] = get_nickname ( $data ['post_uid'] );
		}
		$this->assign ( $list_data );
		
		$this->display ();
	}
	//首页帖子
	public function indexLists(){
		$add_url = U('edit_index',array('mdm'=>I('mdm')));
		$del_button = false;
		$this->assign('add_url',$add_url);
		$this->assign('del_button',$del_button);
		$map ['token'] = get_token ();
		
		$cateArr = M ( 'weiba' )->where ( $map )->getFields ( 'id,weiba_name' );
		session ( 'common_condition' ,array('is_index'=>1));
		$list_data = $this->_get_model_list ( $this->model );
		foreach ( $list_data ['list_data'] as &$data ) {
			$data ['weiba_id'] = $cateArr [$data ['weiba_id']];
			$data ['post_uid'] = get_nickname ( $data ['post_uid'] );
		}
		$list_data['list_grids']['ids']['href'] = "edit_index&id=[id]|编辑首页帖子,del_index&id=[id]|删除首页帖子,";
		//dump($list_data);
		$this->assign ( $list_data );
		$this->display (SITE_PATH.'/Application/Home/View/default/Addons/lists.html');
	}

	//评论管理
	public function comment(){
	    $this->assign ( 'checkTopics_button', true );
	    $this->assign ( 'add_button', false );
	    is_array ( $model ) || $model = $this->getModel ( 'forum_comment' );
	    $templateFile = $this->getAddonTemplate ( $model ['template_list'] );
	    parent::common_lists ( $model, $page, 'lists' );
	}
	
	//消息管理
	public function message(){
	    $this->assign ( 'checkTopics_button', false );
	    $this->assign ( 'add_button', false );
	    is_array ( $model ) || $model = $this->getModel ( 'forum_message' );
	    $templateFile = $this->getAddonTemplate ( $model ['template_list'] );
	    parent::common_lists ( $model, $page, 'lists' );
	}
	//审核帖子
	//审核评论
	
	//审核帖子
	public function checkTopics(){
	    $id = I('id');
	    $ids = I('ids');
	    if(empty($id) && empty($ids)){
	        $this->error('请勾选要通过审核的内容');
	    }
	    $token = get_token();
	    if(is_array($ids)){
	        $id = $ids;
	        $id = implode(',',$id);
	        $where = "token = '$token' AND id in($id)";
	    }else{
	        $where = "token = '$token' AND id = $id";
	    }
	    $mod = M('WeibaReply');
	    $result = $mod->where( $where )->setField('status',1);
	    if($result !== false){
	        $this->success('审核成功');
	    }else{
	        $this->error('审核失败');
	    }
	
	}

}

     