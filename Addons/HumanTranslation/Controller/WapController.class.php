<?php

namespace Addons\HumanTranslation\Controller;

use Home\Controller\AddonsController;

class WapController extends AddonsController {
	var $id;
	function _initialize() {
		parent::_initialize ();
		$this->assign('token', get_token ());
		$where['uid']=$this->mid;
		$orderList=M ( 'TranslationOrder')->where($where)->select();
		//dump($orderList);
		$count=M ( 'TranslationOrder')->where($where)->count();
	}
	// 添加订单  首页
	function index() {
		$this->assign('commonTopName','提交翻译内容');
		$uid= $this->mid;//判断是否关注需要
		if ($uid = 0 && $uid= '-1') {
			$this->error('请在微信中打开');
		}
			//添加显示页面与操作
			if(IS_POST){
				//添加和编辑
			/*	$checkContent = str_replace ( '&nbsp;', '', $_POST ['content'] );
				$checkContent = str_replace ( '<br />', '', $checkContent );
				$checkContent = str_replace ( '<p>', '', $checkContent );
				$checkContent = str_replace ( '</p>', '', $checkContent );
				$checkContents = preg_replace ( '/<img(.*?)src=/i', 'img', $checkContent );
				$checkContents = preg_replace ( '/<embed(.*?)src=/i', 'img', $checkContents );
				if (strlen ( $checkContents ) == 0){
					$this->error ( '翻译内容不能为空', true );
				}*/
				//$this->error(dump($_REQUEST));
				//$fields=M ( 'TranslationOrder')->getDbFields();dump($fields);exit();
				$data ['content'] =safe ( $_POST ['content'] );
				//$data=M ( 'TranslationOrder')->create();
				$data ['uid']  = $this->mid;
				$data ['cTime'] = NOW_TIME;
				$data ['title'] = $_POST['title'];
				$data ['price'] = $_POST['price'];
				$data ['goal_language_id'] = $_POST['goal_language_id'];
				$data ['count_str'] = $_POST['count_str'];
				$data ['select_language_id'] = $_POST['select_language_id'];
				$data ['mobile'] = $_POST['mobile'];
				$data ['translation_type'] = $_POST['translation_type'];
				$data ['order_number'] = get_order_number();
				$data ['openid'] = get_openid();
				$data ['token'] = get_token();
				$imgIds = explode ( ',', $_POST ['imageIds'] );
				foreach ( $imgIds as $imgId ) {
					$imgId = intval ( $imgId );
					if ($imgId > 0) {
						$imgsrc = get_cover_url ( $imgId );
						if ($imgsrc) {
							$data ['content'] .= '<p class="content-mobile-img"><img  src="' . $imgsrc . '" /></p>';
						}
					}
				}
				//dump($_POST ['img_ids']);exit();
				$id = M ( 'translation_order')->add ( $data );
				$url = addons_url ( 'HumanTranslation://Wap/choose_pay', array (
					'id' => $id,
					'price' => I('price'),
					'invite_uid' => $uid
				) );
				if($id){
					$this->success ( '添加成功', $url);
				}else{
					$this->error ( M ( 'translation_order')->getError () );
				}
			}else{
				// 当前用户是否关注公众号
				$map ['uid'] = $this->mid;
				$map ['token'] = get_token ();
				$has_subscribe = intval ( M ( 'public_follow' )->where ( $map )->getField ( 'has_subscribe' ) );
				$this->assign ( 'has_subscribe', $has_subscribe );
				if ($has_subscribe == 0) { // 获取需要关注的公众号二维码
					$res = D ( 'Home/QrCode' )->add_qr_code ( 'QR_SCENE', 'HumanTranslation', $id);//$invite_uid 可以获取增加邀请人的id
					$this->assign ( 'qrcode', $res );
				}
				$select_language_id=M('translation_language')->select();
				$this->assign ( 'select_language_id', $select_language_id );
				$this->display ();
			}

	}
	/*编辑订单*/
	function editIndex(){
		$uid= $this->mid;//判断是否关注需要
		if ($uid = 0 && $uid= '-1') {
			$this->error('请在微信中打开');
		}
		//编辑显示页面与操作
		if(IS_POST){
			//编辑
			$data ['cTime']= NOW_TIME;
			$price=I('price');
			$where['id']=I('id');
			$result = M ( 'translation_order')->where($where)->setInc('price',$price);
			//dump($result);exit();
			$url = addons_url ( 'HumanTranslation://Wap/translate', array (
				'id' => I('id'),
			) );
			if($result){
				$this->success ( '提交成功', $url);
			}else{
				$this->error ( '参数错误！');
			}
		}
	}
	/*翻译首页*/
	function translate(){
		$this->assign('commonTopName','翻译中心');
		$this->display ();
	}
	function translateAjax(){
		$where['pay_status']='1';//已经支付成功的
		$start=I('start');
		$limit=I('limit');
		$orderList=D('TranslationOrder')->getOrderList($where,$start,$limit);
		//dump($orderList);exit();
		$this->success($orderList);
	}
	function taskAjax(){
		$where['uid']=$this->mid;
		$start=I('start');
		$limit=I('limit');
		$orderList=D('TranslationOrder')->getTaskList($where,$start,$limit);
		//dump($orderList);exit();
		$this->success($orderList);
	}
	//增加翻译
	function addTranslate(){
		$this->assign('commonTopName','翻译中心');
		$where['uid']=$this->mid;
		$orderList=D('TranslationOrder')->getOrderList();
		$this->assign ( 'orderList', $orderList );
		$this->display ();
	}
	//取得每个语言下的单价
	function getSingelPrice($id){
		$where['id']=$id;
		$select_language_id=M('translation_language')->where($where)->find();
		$data['singel_price'] = $select_language_id['singel_price'];
		$this->ajaxReturn($data);
		if($select_language_id){
			$data['status']  = 1;
			$this->ajaxReturn($data);
		}else{
			$data['status']  = 0;
			$this->ajaxReturn($data);
		}
	}
	//普通用户我的订单中心
	function myOrder(){
		$this->assign('commonTopName','我的订单');
		$this->display();
	}
	//普通用户我的订单中心
	function myOrderAjax(){
		$this->assign('commonTopName','我的订单');
		$start=I('start');
		$limit=I('limit');
		$where['uid']=$this->mid;
		$orderList=M ( 'translation_order')->where($where)->limit($start,$limit)->order('cTime desc')->select();
		//dump($orderList);
		$this->assign ( 'orderList', $orderList);
		$this->assign ( 'uid', $this->mid);
		$this->success($orderList);
	}
	//查询用户订单
	function orderDetail($id){
		$this->assign('commonTopName','订单详情');
		$result=D('TranslationOrder')->getOrderDetail($id);
		//dump($result);exit();
		$this->assign('detail',$result);
		$this->display();
	}
	//删除订单
	public function del_order(){
		$where['id'] = I ( 'id', 0, 'intval' );
		$res = D ( 'TranslationOrder' )->where($where)->delete();
		if ($res) {
			$this->success ( '删除成功' );
		} else {
			$this->success ( '删除失败' );
		}
	}
	//服务说明
	function term(){
		$map ['name'] = _ADDONS;
		$addon = M ( 'addons' )->where ( $map )->find ();
		if (! $addon)
			$this->error ( '插件未安装' );
		$addon_class = get_addon_class ( $addon ['name'] );
		if (! class_exists ( $addon_class ))
			trace ( "插件{$addon['name']}无法实例化,", 'ADDONS', 'ERR' );
		$data = new $addon_class ();
		$addon ['addon_path'] = $data->addon_path;
		$addon ['custom_config'] = $data->custom_config;
		$this->meta_title = '设置插件-' . $data->info ['title'];
		$db_config = D ( 'Common/AddonConfig' )->get ( _ADDONS );
		if ($db_config) {
			foreach ( $addon ['config'] as $key => $value ) {
				if ($value ['type'] != 'group') {
					! isset ( $db_config [$key] ) || $addon ['config'] [$key] ['value'] = $db_config [$key];
				}else {
					foreach ( $value ['options'] as $gourp => $options ) {
						foreach ( $options ['options'] as $gkey => $value ) {
							! isset ( $db_config [$key] ) || $addon ['config'] [$key] ['options'] [$gourp] ['options'] [$gkey] ['value'] = $db_config [$gkey];
						}
					}
				}
			}
		}
		$this->assign ( 'data', $db_config );
		$this->assign('commonTopName','查看服务说明');
		$this->assign('detail',term);
		$this->display();
	}
	//翻译人员提交
	function addAuthor(){
		$this->assign('commonTopName','成为翻译');
		$uid= $this->mid;//判断是否关注需要
		if ($uid = 0 && $uid= '-1') {
			$this->error('请在微信中打开');
		}
		//添加显示页面与操作
		if(IS_POST){
			//添加和编辑
			$data  =$_POST;
			$data ['uid']  = $this->mid;
			$data ['cTime'] = NOW_TIME;
			$data ['openid'] = get_openid();
			$data ['token'] = get_token();
			$imgIds = explode ( ',', $_POST ['imageIds'] );
			foreach ( $imgIds as $imgId ) {
				$imgId = intval ( $imgId );
				if ($imgId > 0) {
					$imgsrc = get_cover_url ( $imgId );
					if ($imgsrc) {
						$data ['img_url'] = '<p class="content-mobile-img"><img  src="' . $imgsrc . '" /></p>';
					}
				}
			}
			$id = M ( 'translation_author')->add ( $data );
			$url = addons_url ( 'HumanTranslation://Wap/translate');
			if($id){
				$this->success ( '添加成功', $url);
			}else{
				$this->error ( M ( 'translation_order')->getError () );
			}
		}else {
			// 当前用户是否关注公众号
			$map ['uid'] = $this->mid;
			$map ['token'] = get_token();
			$has_subscribe = intval(M('public_follow')->where($map)->getField('has_subscribe'));
			$this->assign('has_subscribe', $has_subscribe);
			if ($has_subscribe == 0) {
				// 获取需要关注的公众号二维码
				$res = D('Home/QrCode')->add_qr_code('QR_SCENE', 'HumanTranslation', $id);//$invite_uid 可以获取增加邀请人的id
				$this->assign('qrcode', $res);
			}
			$select_language_id = M('translation_language')->select();
			$this->assign('select_language_id', $select_language_id);
			$this->display();
		}
	}
	//个人中心
	function ucenter(){
		$follow_id = $this->mid;
		$follow = get_followinfo ( $follow_id );
		$this->assign ( 'follow', $follow );
		// dump($follow);
		$this->assign('commonTopName','个人中心');
		$this->display();
	}
	function contact(){
		$map ['name'] = _ADDONS;
		$addon = M ( 'addons' )->where ( $map )->find ();
		if (! $addon)
			$this->error ( '插件未安装' );
		$addon_class = get_addon_class ( $addon ['name'] );
		if (! class_exists ( $addon_class ))
			trace ( "插件{$addon['name']}无法实例化,", 'ADDONS', 'ERR' );
		$data = new $addon_class ();
		$addon ['addon_path'] = $data->addon_path;
		$addon ['custom_config'] = $data->custom_config;
		$this->meta_title = '设置插件-' . $data->info ['title'];
		$db_config = D ( 'Common/AddonConfig' )->get ( _ADDONS );
		if ($db_config) {
			foreach ( $addon ['config'] as $key => $value ) {
				if ($value ['type'] != 'group') {
					! isset ( $db_config [$key] ) || $addon ['config'] [$key] ['value'] = $db_config [$key];
				}else {
					foreach ( $value ['options'] as $gourp => $options ) {
						foreach ( $options ['options'] as $gkey => $value ) {
							! isset ( $db_config [$key] ) || $addon ['config'] [$key] ['options'] [$gourp] ['options'] [$gkey] ['value'] = $db_config [$gkey];
						}
					}
				}
			}
		}
		//dump($db_config);exit();
		$this->assign ( 'data', $db_config );
		$this->assign('commonTopName','联系我们');
		$this->display();
	}
	//我的翻译
	function myTask(){
		$this->assign('commonTopName','我的翻译');
		$where['uid']=$this->mid;
		$orderList=M ( 'translation_task')->where($where)->select();
		//dump($orderList);
		$count=M ( 'translation_task')->where($where)->count();
		$this->assign('orderList',$orderList);
		$this->display();
	}
	//消息
	function myInfo(){
		$this->assign('commonTopName','我的消息');
		$where['uid']=$this->mid;
		$orderList=M ( 'translation_task')->where($where)->select();
		//dump($orderList);
		$count=M ( 'translation_task')->where($where)->count();
		$this->assign('orderList',$orderList);
		$this->display();
	}
	// 选择支付方式
	function choose_pay() {
		$this->assign('commonTopName','选择支付');
		$id = I ( 'id', 0, 'intval' );
		$price= I ( 'price');
		$config = getAddonConfig ( 'Payment' );
		$map ['id'] = $id;
		$orderinfo = D ( 'TranslationOrder' )->getOrderDetail($id);
		//dump($config);
		//dump($orderinfo);exit();
		$token = get_token ();
		// 微信用户ID
		$openid = $orderinfo ['openid'];
		// 订单名称 商品订单表里面没有订单名称字段
		$orderName = urlencode ( $orderinfo['title'] );
		// 订单编号
		$orderNumber = $orderinfo ['order_number'];
		// 支付金额
		$this->assign ( 'order_id', $id );//必须
		$this->assign ( 'openid', $openid );
		$this->assign ( 'token', $token );
		$this->assign ( 'orderName', $orderName );
		$this->assign ( 'orderNumber', $orderNumber);//必须
		$this->assign ( 'price', $price );//必须
		$this->assign ( 'config', $config );
		$this->display ();
	}
	function addTranslateTask(){
		$id = I ( 'id', 0, 'intval' );
		$where['uid']=$this->mid;
		//先判断是否注册和资料是否完整
		//完成度=已填写字段数/需填写字段总数*100%
		$result=M ( 'translation_author')->where($where)->find();
		if($result){
			//已经填写字段数
			count(array_filter($result));
			//完成度
			$finishResult=round(count(array_filter($result))/6*100).'%';
			echo $finishResult;exit();
			/*if($finishResult<30.'%'){
				$this->error ( '您的个人信息不完善哟！' ,$url);
			}*/
			$this->success ( '增加成功' );
		}else{
			$this->error ( '需要您提交一些翻译能力相关信息，才能翻译哟！',$url );
		}
		$orderList=M ( 'translation_task')->where()->select();
	}
	/*改变阅读信息的状态*/
	function seeMyInfo()
	{
		$id = I('id', 0, 'intval');
		$where['uid'] = $this->mid;
		$result = M('translation_task')->where()->setField('is_see', 1);
		return $result;
	}
	/*查看详情*/
	function detail(){
		$id = I('id', 0, 'intval');
		$this->assign('commonTopName','翻译详情');
		$result=D('TranslationOrder')->getOrderDetail($id);
		//dump($result);exit();
		$this->assign('detail',$result);
		$this->display();
	}
	/*增加*/
	function addTask(){
		$data=$_POST;
		$data['ctime']=time();
		$order_id=$_POST['order_id'];
		$data['token']=get_token();
		$id=M('translation_task')->add($data);
		if ($id) {
			//向需要翻译的人消息提示 13629502432
			$this->_sendWeixinMail ($order_id,$this->mid,1);
			$this->success ( '翻译已经提交' );
		} else {
			$this->error ( '提交失败，请重试' );
		}
	}
	/*
	id:新增返回id
	uid：用户id
	发送客服信息*/
	function _sendWeixinMail($order_id, $uid,$from) {
		if($from==1){
			//提交译文
			$url = U ( 'orderDetail', array (
				'id' => $order_id,
				'uid' => $uid
			) );
			$config=get_addon_config('HumanTranslation');
			//dump($config);
			//查询具体信息
			$Info=D('TranslationOrder')->getOrderDetail($order_id);
			$title=$Info['title'];
			$order_number=$Info ['order_number'];
			$cTime=time_format($Info ['cTime'],'Y-m-d');
			$first="您的翻译订单已经有人翻译了";
			$templateId=$config['translateResult'];
			$status="已经提交";//服务状态
			//dump($Info);
			$remark="您的翻译订单已经有人翻译了，点击查看译文";
			if ($config['is_close']==0){
				D ( 'Common/TemplateMessage' )->replyServerNotice($uid,$title,$order_number,$cTime,$remark,$first,$url,$templateId,$status);
			}
		}elseif($from==2){
			//译文选中通知
			$url = U ( 'orderDetail', array (
				'id' => $order_id,
				'uid' => $uid
			) );
			$config=get_addon_config('HumanTranslation');
			//dump($config);
			//查询具体信息
			$Info=D('TranslationOrder')->getOrderDetail($order_id);
			$title=$Info['title'];
			$order_number=$Info ['order_number'];
			$cTime=time_format($Info ['cTime'],'Y-m-d');
			$first="您的翻译订单已经被采纳了";
			$templateId=$config['translateResult'];
			$status="已经成交";//服务状态
			//dump($Info);
			$remark="您的翻译订单已经被采纳了，点击查看详情";
			if ($config['is_close']==0){
				D ( 'Common/TemplateMessage' )->replyServerNotice($uid,$title,$order_number,$cTime,$remark,$first,$url,$templateId,$status);
			}
		}

	}
	/*采用译文通知*/
	function changeAfterContent(){
		$where['id']=$_POST['after_content_id'];//译文自增长id
		$where1['id']=$_POST['content_id'];//原文订单id
		//给原来的订单增加译文
		$data1['order_status']=2;//结束翻译
		$data1['after_content']=$_POST['after_content_id'];
		$result1=M('translation_order')->where($where1)->save($data1);
		//设置推荐译文
		$data['is_recommend']=1;
		$result=M('translation_task')->where($where)->save($data);
		if($result1){
			//给翻译者消息提醒
			$this->_sendWeixinMail ($_POST['content_id'],$this->mid,2);
			//发送红包给翻译者

			$this->success ( '确认成功' );
		} else {
			$this->error ( '提交失败，请重试' );
		}

	}
}
