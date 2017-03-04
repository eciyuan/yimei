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
							$data ['content'] .= '<p><img src="' . $imgsrc . '" /></p>';
							$data ['content'] = htmlspecialchars($data ['content']);
							//$data ['content'] = html_entity_decode ( $data ['content'], ENT_QUOTES, 'UTF-8' );
						}
					}
				}
				//dump($_POST ['img_ids']);exit();
				$id = M ( 'translation_order')->add ( $data );
				$url = addons_url ( 'HumanTranslation://Wap/choose_pay', array (
					'id' => $id,
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
		$this->assign('commonTopName','提交翻译内容');
		$uid = $this->mid;//判断是否关注需要
		$id=I('id');
		if ($uid = 0 && $uid= '-1') {
			$this->error('请在微信中打开');
		}
		//编辑显示页面与操作
		if(IS_POST){
			//编辑
			$data=M ( 'translation_order')->create();
			$data ['uid']  = $this->mid;
			$data ['cTime']= NOW_TIME;
			$data ['token']=get_token();
			$data ['openid']=get_openid();
			/*echo $data ['openid'];
			echo $data ['token'];
			exit();*/
			$where['id']=$id;
			$result = M ( 'translation_order')->where($where)->save ($data);
			//dump($result);exit();
			$url = addons_url ( 'HumanTranslation://Wap/choose_pay', array (
				'id' => $id,
				'invite_uid' => $uid
			) );
			if($result){
				$this->success ( '编辑成功', $url);
			}else{
				$this->error ( '参数错误！');
			}
		}
			// 当前用户是否关注公众号
			$map ['uid'] = $this->mid;
			$map ['token'] = get_token ();
			$has_subscribe = intval ( M ( 'public_follow' )->where ( $map )->getField ( 'has_subscribe' ) );
			$this->assign ( 'has_subscribe', $has_subscribe );
			if ($has_subscribe == 0) { // 获取需要关注的公众号二维码
				$res = D ( 'Home/QrCode' )->add_qr_code ( 'QR_SCENE', 'HumanTranslation', $id);//$invite_uid 可以获取增加邀请人的id
				$this->assign ( 'qrcode', $res );
			}
			//查询具体信息
			$result=D('TranslationOrder')->getOrderDetail($id);
			//dump($result);
			$select_language_id=M('translation_language')->select();
			$this->assign ( 'select_language_id', $select_language_id );
			$this->assign ( 'detail', $result );
			$this->display ();

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
	//增加翻译
	function addTranslate(){
		$this->assign('commonTopName','翻译中心');
		$where['uid']=$this->mid;
		$orderList=D('TranslationOrder')->getOrderList();
		$this->assign ( 'orderList', $orderList );
		$this->display ();
	}
	function do_help() {
		$id = I ( 'id', 0, 'intval' );
		$invite_uid = I ( 'invite_uid', 0, 'intval' );
		if (empty ( $id ) || empty ( $invite_uid )) {
			$this->error ( '参数不能为空' );
		}
		if ($this->mid == $invite_uid) {
			$this->error ( '不能拆自己的礼包' );
		}
		$info = D ( 'HelpOpen' )->getInfo ( $id );
		if ($info ['status'] == 0) {
			$this->error ( '活动已经禁用' );
		}
		if ($info ['start_time'] > NOW_TIME) {
			$this->error ( '活动还没开始' );
		}
		if ($info ['end_time'] < NOW_TIME) {
			$this->error ( '活动已经结束' );
		}
		$data1 ['is_del']=0;
		$data1 ['help_id'] =$data ['help_id'] = $mapou ['help_id'] = $id;
		// 获取奖品列表
		$list = M ( 'help_open_prize' )->where ( $data1 )->order ( 'sort asc' )->select ();
		foreach ( $list as $k => $vv ) {
			$flat = true;
			if ($k == 0) {
				$big_prize = $vv;
			} else {
				$uid=$this->mid;
				if ($vv ['prize_type'] == 1) { // 优惠券 OPENTM200474379 优惠券领取成功通知
					$couponinfo = D ( 'Addons://Coupon/Coupon' )->getInfo ( $vv ['coupon_id'] );
					$snMap['target_id']=$vv ['coupon_id'];
					$snMap['addon']="Coupon";
					$snMap['can_use']=1;
					$couponinfo['collect_count']=D ( 'Common/SnCode' )->where($snMap)->count();
					if ($couponinfo ['collect_count'] >= $couponinfo ['num']) {
						$flat = false;
					} else if (! empty ( $couponinfo ['start_time'] ) && $couponinfo ['start_time'] > NOW_TIME) {
						$flat = false;
					} else if (! empty ( $couponinfo ['end_time'] ) && $couponinfo ['end_time'] < NOW_TIME) {
						$flat = false;
					}
					$list = D ( 'Common/SnCode')->getMyList ( $uid, $vv['coupon_id'], 'Coupon' );
					$my_count = count ( $list );
					if ($couponinfo ['max_num'] > 0 && $my_count >= $couponinfo ['max_num']) {
						$flat = false;
					}
				} elseif ($vv ['prize_type'] == 2 && is_install("ShopCoupon")) { // 代金券 TM00483 获得代金券通知
					// 金额
					$scouponinfo = D ( 'Addons://ShopCoupon/Coupon' )->getInfo ( $vv ['shop_coupon_id'] );
					$snMap['target_id']=$vv ['shop_coupon_id'];
					$snMap['addon']="ShopCoupon";
					$snMap['can_use']=1;
					$scouponinfo['collect_count']=D ( 'Common/SnCode' )->where($snMap)->count();
					if ($scouponinfo ['collect_count'] >= $scouponinfo ['num']) {
						$flat = false;
					} else if (! empty ( $scouponinfo ['start_time'] ) && $scouponinfo ['start_time'] > NOW_TIME) {
						$flat = false;
					} else if (! empty ( $scouponinfo ['end_time'] ) && $scouponinfo ['end_time'] < NOW_TIME) {
						$flat = false;
					}
					$list = D ( 'Common/SnCode')->getMyList ( $uid, $vv['shop_coupon_id'], 'ShopCoupon' );
					$my_count = count ( $list );
				
					if ($scouponinfo ['limit_num'] > 0 && $my_count >= $info ['limit_num']) {
						$flat = false;
					}
				}
				if ($flat){
					$prize_ids [$vv ['id']] = $vv ['id'];
					$small_prize [$vv ['id']] = $vv;
				}
			}
		}
		
		$data ['friend_uid'] = $this->mid;
		if (D ( 'HelpOpenUser' )->where ( $data )->find ()) {
			$this->error ( '您已经帮拆过一次' );
		}
		
		// 当前好友获得随机小礼品一份
		$prize_id = array_rand ( $prize_ids );
		if (empty($small_prize [$prize_id])){
			$this->error('礼包已被领取完！');
		}
		$sn = $this->_sendPrize ( $id, $this->mid, $small_prize [$prize_id] );
		$data ['sn_id'] = $sn ['id'];
		$data ['cTime'] = NOW_TIME;
		$data ['invite_uid'] = $invite_uid;
		D ( 'HelpOpenUser' )->add ( $data );
		
		$mapou ['invite_uid'] = $invite_uid;
		$mapou ['friend_uid'] = 0;
		D ( 'HelpOpenUser' )->where ( $mapou )->setInc ( 'join_count' );
		
		$uname = get_nickname ( $this->mid );
		
		$join = D ( 'HelpOpenUser' )->checkJoin ( $id, $this->mid, $invite_uid );

		$config=get_addon_config('HelpOpen');

		$invite_left = intval ( $info ['limit_num'] - $join ['invite_count'] );
		if ($invite_left > 0) { // 小礼包领取通知 OPENTM200977411
			if ($config['is_close']==0){
				$content = "您的好友{$uname}领取了您推荐的爱心分享，您还需要{$invite_left}位好友帮助领取才能获得大礼包，别忘了感谢他！";
				// 			D ( 'Common/Custom' )->replyText ( $invite_uid, $content );
				D ( 'Common/TemplateMessage' )->replyGiftNotice ( $invite_uid, $uname,$first='',$orderId='',$content,'',$config['libaoling']);
			}
		} else { // 小礼包领取通知 OPENTM200977411
// 			$content = "您的好友{$uname}领取了您推荐的爱心分享，您的人气指数直接爆表！";
// 			D ( 'Common/Custom' )->replyText ( $invite_uid, $content );
			if ($config['is_close']==0){
				D ( 'Common/TemplateMessage' )->replyGiftNotice ( $invite_uid, $uname,'','','','',$config['libaoling']);
				 
			}
			// 判断大礼品发放过没有，没有的话给他发放一份
			$sn_id = intval ( D ( 'HelpOpenUser' )->where ( $mapou )->getField ( 'sn_id' ) );
			if ($sn_id <= 0) {
				// 先判断大礼包是否被领取完
				$hgmap ['help_id'] = $info ['id'];
				$hgmap ['friend_uid'] = 0;
				$hgmap ['sn_id'] = array (
						'gt',
						0 
				);
				$has_get = D ( 'HelpOpenUser' )->where ( $hgmap )->count ();
				if ($info ['prize_num'] > 0 && $has_get >= $info ['prize_num']) {
					if ($sn_id == 0) { // 只提示一次 礼包发放失败通知 TM00384
// 						$content = "很抱歉，大礼包已被抢完!";
// 						D ( 'Common/Custom' )->replyText ( $invite_uid, $content );
						if ($config['is_close'] == 0){
							D ( 'Common/TemplateMessage' )->replyGiftFail ( $invite_uid, $info['title'],'大礼包已被抢完','大礼包','','','',$config['libaoshi'] );
						}
						D ( 'HelpOpenUser' )->where ( $mapou )->setField ( 'sn_id', - 1 );
					}
				} else {
					$sn = $this->_sendPrize ( $id, $invite_uid, $big_prize );
					D ( 'HelpOpenUser' )->where ( $mapou )->setField ( 'sn_id', $sn ['id'] );
				}
			}
		}
		
		$this->success ( '帮TA拆开成功' );
	}
	function _sendPrize($help_id, $uid, $prize) {
		if ($prize ['prize_type'] == 0)
			return false;
			// 通过客服接口发送礼包通知
		$url = U ( 'my_prize', array (
				'help_id' => $help_id,
				'uid' => $uid,
				'publicid' => get_token_appinfo ( '', 'id' ) 
		) );
		if ($prize ['prize_type'] == 1) { // 优惠券 OPENTM200474379 优惠券领取成功通知		
			$content = "恭喜您获得优惠券大礼包，已发到您会员账号，<a href='$url'>点击查看我的礼包</a>";
			$data ['target_id'] = $prize ['coupon_id'];
			$data ['addon'] = 'Coupon';
			$data ['prize_title'] = $prize ['name'];
			
		} elseif ($prize ['prize_type'] == 2 && is_install("ShopCoupon")) { // 代金券 TM00483 获得代金券通知
			$content = "恭喜您获得代金券大礼包，已发到您会员账号，<a href='$url'>点击查看我的礼包</a>";
			$data ['target_id'] = $prize ['shop_coupon_id'];
			$data ['addon'] = 'ShopCoupon';
			
			// 金额
			$info = D ( 'Addons://ShopCoupon/Coupon' )->getInfo ( $prize ['shop_coupon_id'] );
			$data ['prize_title'] = $info ['money'];
			if ($info ['is_money_rand']) {
				$data ['prize_title'] = rand ( $info ['money'] * 100, $info ['money_max'] * 100 ) / 100;
			}
			
			
		} else { // 返现 OPENTM205223929 返现到账通知
			$money = $prize ['money'];
			$content = "恭喜您获得{$money}元返现，已充值您会员卡余额中，<a href='$url'>点击查看我的礼包</a>";
			$data ['target_id'] = $help_id;
			$data ['addon'] = 'HelpOpen';
			$data ['prize_title'] = $prize ['name'];
			
			// 自动充值到账户
			$log ['type'] = 0; // 系统自动充值
			$log ['remark'] = "参加9+1红包获得{$money}元返现"; // 系统自动充值
			add_money ( $uid, $money, $log );
		}
		
		$data ['uid'] = $uid;
		$data ['sn'] = uniqid ();
		$data ['cTime'] = NOW_TIME;
		$data ['token'] = get_token ();
		$data ['prize_id'] = $prize ['id'];
		
		$data ['id'] = D ( 'Common/SnCode' )->add ( $data );
		$config=get_addon_config('HelpOpen');

		if ($prize ['prize_type'] == 1){
		    //优惠券
		    $couponInfo = D ( 'Addons://Coupon/Coupon' )->getInfo ( $prize ['coupon_id'] );
		    $remark="礼包已发到您的会员卡个人中心的优惠券处，点击详情查看我的礼包";
		    if ($config['is_close']==0){
		    	D ( 'Common/TemplateMessage' )->replyCouponSuccess($uid, $couponInfo['title'],$data ['sn'],time_format($couponInfo['over_time'],'Y-m-d'),$remark,$first='恭喜您获得优惠券大礼包！',$url,$config['youhuijuan']);
		    }
		   
		}else if($prize ['prize_type'] == 2){
		    
		    $remark = "礼包已发到您的会员卡个人中心的代金券处，点击详情查看我的礼包";
		    if ($config['is_close']==0 && is_install("ShopCoupon")){
		    	D ( 'Common/TemplateMessage' )->replyShopCouponSuccess($uid, $data ['prize_title'],time_format($info['end_time'],'Y-m-d'),$remark,$first='恭喜您获得代金券大礼包',$url,$config['daijinjuan']) ;
		    }
		   
		}else{
		    $remark = "已充值到您会员卡余额中，点击详情查看我的礼包";
		    $content="参加9+1红包获得{$money}元返现";
		    if ($config['is_close']==0){
		    	D ( 'Common/TemplateMessage' )->replyReturnMoney($uid, $money.'元',$content,$remark,$first="恭喜您获得{$money}元返现",$url,$config['fangxian']) ;
		    }
		}
		
		
// 		D ( 'Common/Custom' )->replyText ( $uid, $content );
		return $data;
	}
	function lists() {
		$model = $this->getModel ( 'help_open' );
		$page = I ( 'p', 1, 'intval' ); // 默认显示第一页数据
		                                
		// 解析列表规则
		$list_data = $this->_list_grid ( $model );
		
		// 搜索条件
		$map = $this->_search_map ( $model, $fields );
		$type = I ( 'type', 0, 'intval' );
		if ($type == 1) {
			$map ['start_time'] = array (
					'gt',
					NOW_TIME 
			);
		} elseif ($type == 2) {
			$map ['start_time'] = array (
					'lt',
					NOW_TIME 
			);
			$map ['end_time'] = array (
					'gt',
					NOW_TIME 
			);
		} elseif ($type == 3) {
			$map ['end_time'] = array (
					'lt',
					NOW_TIME 
			);
		}
		
		$row = empty ( $model ['list_row'] ) ? 20 : $model ['list_row'];
		
		// 读取模型数据列表
		$name = parse_name ( get_table_name ( $model ['id'] ), true );
		$data = M ( $name )->field ( true )->where ( $map )->order ( $order )->page ( $page, $row )->select ();
		foreach ( $data as &$vo ) {
			$vo ['start_time'] = time_format ( $vo ['start_time'] ) . ' 至<br/>' . time_format ( $vo ['end_time'] );
		}
		
		/* 查询记录总数 */
		$count = M ( $name )->where ( $map )->count ();
		
		$list_data ['list_data'] = $data;
		
		// 分页
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$list_data ['_page'] = $page->show ();
		}
		
		$this->assign ( $list_data );
		// dump($list_data);
		
		$this->display ();
	}
	function store_list() {
		$id = $param ['id'] = I ( 'id', 0, 'intval' );
		
		$info = D ( 'HelpOpen' )->getInfo ( $id );
		
		$shop_ids = wp_explode ( $info ['shop_ids'] );
		if (! empty ( $shop_ids )) {
			$map_shop ['id'] = array (
					'in',
					$shop_ids 
			);
			$shop_list = M ( 'coupon_shop' )->where ( $map_shop )->select ();
			$this->assign ( 'shop_list', $shop_list );
		}
		$this->display ();
	}
	function content() {
		$id = $param ['id'] = I ( 'id', 0, 'intval' );
		
		$info = D ( 'HelpOpen' )->getInfo ( $id );
		
		$this->assign ( 'info', $info );
		$this->display ();
	}
	
	// 我的奖品
	function my_prize() {
		$map ['help_id'] = I ( 'help_id' );
		$this->assign ( 'help_id', $map ['help_id'] );
		$info = D ( 'HelpOpen' )->getInfo ( $map ['help_id'] );
		$this->assign ( 'info', $info );
		
		$where = "sn_id>0 and ((invite_uid='{$this->mid}' and friend_uid=0) or friend_uid='{$this->mid}')";
		$list = D ( 'HelpOpenUser' )->where ( $map )->where ( $where )->select ();
		foreach ( $list as &$vo ) {
			$sn = D ( 'Common/SnCode' )->getInfoById ( $vo ['sn_id'] );
			$prize = D ( 'HelpOpenPrize' )->getInfo ( $sn ['prize_id'] );
			
			if ($prize ['prize_type'] == '1') {
				$info = M ( 'coupon' )->find ( $prize ['coupon_id'] );
				$vo ['url'] = addons_url ( 'Coupon://Wap/show', array (
						'id' => $prize ['coupon_id'],
						'sn_id' => $vo ['sn_id'] 
				) );
				$vo ['time'] = $this->_time ( $info ['use_start_time'], $info ['over_time'] );
				$vo ['is_use'] = $sn ['is_use'];
			} elseif ($prize ['prize_type'] == '2' && is_install("ShopCoupon")) {
				$info = M ( 'shop_coupon' )->find ( $prize ['shop_coupon_id'] );
				$vo ['url'] = addons_url ( 'ShopCoupon://Wap/show', array (
						'id' => $prize ['shop_coupon_id'],
						'sn_id' => $vo ['sn_id'] 
				) );
				$vo ['time'] = $this->_time ( $info ['start_time'], $info ['end_time'] );
				$vo ['is_use'] = $sn ['is_use'];
			} else {
				$vo ['url'] = addons_url ( 'Card://Wap/recharge' );
			}
			
			$vo ['prize'] = $prize;
			$vo ['sn'] = $sn;
			if ($vo ['friend_uid'] == 0) {
				$vo ['is_big'] = 1;
			} else {
				$vo ['is_big'] = 0;
			}
		}
		// dump ( $list );
		// exit ();
		$this->assign ( 'list', $list );
		
		// 获奖人数
		$where = "sn_id>0";
		$total = D ( 'HelpOpenUser' )->where ( $map )->where ( $where )->count ();
		$this->assign ( 'total', $total );
		
		$this->display ();
	}
	function _time($start_time = '', $end_time = '') {
		if (! empty ( $start_time ) && ! empty ( $end_time )) {
			return time_format ( $start_time ) . ' 至 ' . time_format ( $end_time );
		} elseif (! empty ( $start_time )) {
			return time_format ( $start_time ) . ' 开始';
		} elseif (! empty ( $end_time )) {
			return '至 ' . time_format ( $start_time );
		}
	}
	// 获奖名单
	function prize_log() {
		$page = I ( 'p', 1, 'intval' ); // 默认显示第一页数据
		                                
		// 搜索条件
		$map ['help_id'] = $hmap ['id'] = I ( 'help_id' );
		$where = 'sn_id>0';
		$info = D ( 'HelpOpen' )->getInfo ( $map ['help_id'] );
		$this->assign ( 'info', $info );
		// 读取模型数据列表
		$data = M ( 'help_open_user' )->field ( true )->where ( $map )->where ( $where )->order ( 'id desc' )->page ( $page, 20 )->select ();
		$sdao = M ( 'sn_code' );
		$pdao = D ( 'HelpOpenPrize' );
		foreach ( $data as &$vo ) {
			if ($vo ['friend_uid'] == 0) {
				$user = getUserInfo ( $vo ['invite_uid'] );
				$vo ['userface'] = $user ['headimgurl'];
				$vo ['nickname'] = $user ['nickname'];
				$vo ['type'] = '大礼包';
			} else {
				$user = getUserInfo ( $vo ['friend_uid'] );
				$vo ['userface'] = $user ['headimgurl'];
				$vo ['nickname'] = $user ['nickname'];
				$vo ['type'] = '小礼包';
			}
			$vo ['cTime'] = time_format ( $vo ['cTime'] );
			
			$smap ['id'] = $vo ['sn_id'];
			$pmap ['id'] = $sdao->where ( $smap )->getField ( 'prize_id' );
			$vo ['prize'] = $pdao->where ( $pmap )->getField ( 'name' );
		}
		
		/* 查询记录总数 */
		$count = M ( 'help_open_user' )->where ( $map )->where ( $where )->count ();
		$list_data ['list_data'] = $data;
		
		// 分页
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$list_data ['_page'] = $page->show ();
		}
		$this->assign ( $list_data );
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
		$orderList=M ( 'translation_order')->where($where)->limit($start,$limit)->select();
		//dump($orderList);
		$this->assign ( 'orderList', $orderList );
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
	/*//删除用户订单
	function delOrder($id){
		$where['id']=$id;
		if(empty($id)){
			$data['status']  = 0;
			$this->ajaxReturn($data);
		}
		$result=M('translation_order')->where($where)->delete();
		if($result){
			$data['status']  = 1;
			$this->ajaxReturn($data);
		}else{
			$data['status']  = 0;
			$this->ajaxReturn($data);
		}
	}*/
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
		$this->display();
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
		$price = $orderinfo ['price'];
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
		$url = addons_url ( 'HumanTranslation://Wap/choose_pay', array (
			'id' => $id,
			'invite_uid' => $this->mid
		) );
		if($result){
			//已经填写字段数
			count(array_filter($result));
			//完成度
			$finishResult=round(count(array_filter($result))/6*100).'%';
			echo $finishResult;exit();
			/*if($finishResult<30.'%'){
				$this->error ( '您的个人信息不完善哟！' ,$url);
			}*/
			$this->success ( '删除成功' );
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
	/*增加点击次数*/
}
