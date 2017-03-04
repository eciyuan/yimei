<?php

namespace Addons\Weiba\Controller;

use Home\Controller\AddonsController;
/* 评论管理 */
class EventController extends AddonsController {
	var $model;
	function _initialize() {
		$this->model = $this->getModel ( 'weiba_event' );
	}
	public function lists() {
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
	//编辑首页帖子
	public function edit_index(){
		
		$id = intval($_GET['id']);
		if($id){
			$post = D('weiba_reply')->where(array('id'=>$id))->find();
			$data['id'] = $id;
			$data['title']=$post['title'];
			$data['index_img'] = $post['index_img'];
			$this->assign('data',$data);
		}
		if(IS_POST){
			$id = intval($_POST['id']);
			$data['is_index'] = 1;
			$data['index_img'] = $_POST['index_img'];
			$data['title']=$_POST['title'];
			
			$res = D('weiba_reply')->where(array('id'=>$id))->save($data);
			if($res){
				$this->success ( '编辑成功',addons_url('Weiba://Post/indexLists',array('mdm'=>I('mdm'))) );
			} else {
				$this->error ( '编辑失败！' );
			}
		}
		$this->display();
		
	}
	//编辑首页帖子
	public function del_index(){
		$id = intval($_GET['id']);
		$res = D('weiba_reply')->where(array('id'=>$id))->setField('is_index',0);
		if(res){
			$this->success ( '删除成功' );
		} else {
			$this->error ( '删除失败！' );
		}
	}
	public function post_data(){
	    $page = I ( 'p', 1, 'intval' );
	    //$wmodel =get_model('weiba');
	    $map ['token'] = get_token ();
	    $map['is_index'] =array('neq',1);
	    $cateArr = M ( 'weiba_category' )->where ( $map )->getFields ( 'id,name' );
	    //$list_data = $this->_get_model_list ( $wmodel );
	    $list_data =M('weiba_reply')->where($map)->order ( 'id DESC' )->page ( $page, 20 )->selectPage ( 20 );
	    foreach ( $list_data ['list_data'] as &$data ) {
	        $data ['cid'] = $cateArr [$data ['cid']];
	    }
	    $this->ajaxReturn( $list_data ,'JSON');
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
	
	
	//增加模型
	function add() {
	    is_array ( $model ) || $model = $this->getModel ( $model );
	    $templateFile = $this->getAddonTemplate ( $model ['template_add'] );
	
	    parent::common_add ( $model, $templateFile );
	}
	
	//编辑模型
	public function edit() {
	    is_array ( $model ) || $model = $this->getModel ( $model );
	    $templateFile = $this->getAddonTemplate ( $model ['template_edit'] );
	    parent::common_edit ( $model, $id, $templateFile );
	}
	
	//删除模型
	public function del($model = null, $ids = null) {
	    $mod =  I('get.model');
	    switch ($mod) {
	        case 'topics':
	            $model = 'forum_topics';
	            break;
	        case 'comment':
	            $model = 'forum_comment';
	            break;
	        case 'message':
	            $model = 'forum_message';
	            break;
	        case 'lists':
	            $model = 'forum_topics';
	            break;
	        default:
	    }
	    parent::common_del ( $model, $ids );
	}
	//审核帖子
	public function checkTopics(){
	
	    $id = I('id');
	    $ids = I('ids');
	
	    $token = get_token();
	
	    if(empty($id) && empty($ids)){
	        $this->error('请勾选要通过审核的内容');
	    }
	
	    if(is_array($ids)){
	        $id = $ids;
	
	        $id = implode(',',$id);
	        $where = "token = '$token' AND id in($id)";
	        	
	    }else{
	        $where = "token = '$token' AND id = $id";
	    }
	    $mod =  I('get.model');
	    switch ($mod) {
	        case 'topics':
	            $model = 'forum_topics';
	            break;
	        case 'comment':
	            $model = 'forum_comment';
	            break;
	        case 'lists':
	            $model = 'forum_topics';
	            break;
	        default:
	    }
	    $result = M( $model )->where( $where )->setField('status',1);
	    if($result !== false){
	        $this->success('审核成功');
	    }else{
	        $this->error('审核失败');
	    }
	
	}
}

     