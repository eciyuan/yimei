<?php

namespace Addons\Shop\Controller;

use Home\Controller\AddonsController;

class WapController extends AddonsController {
	var $shop_id;
	function _initialize() {
		parent::_initialize ();
		
		if (! empty ( $_REQUEST ['shop_id'] )) {
			$this->shop_id = I ( 'shop_id' );
			session ( 'wap_shop_id', $this->shop_id );
		} else {
			$this->shop_id = session ( 'wap_shop_id' );
		}
		// dump ( $this->shop_id );
		
		if (empty ( $this->shop_id )) {
			$map ['token'] = get_token ();
			// $map ['manamger_id'] = session ( 'manamger_id' );
			
			$shop = M ( 'shop' )->where ( $map )->find ();
			$this->shop_id = $shop ['id'];
		} else {
			$shop = D ( 'Shop' )->getInfo ( $this->shop_id );
		}
		empty ( $shop ['template'] ) && $shop ['template'] = 'jd';
		
		define ( 'CUSTOM_TEMPLATE_PATH', ONETHINK_ADDON_PATH . '/Shop/View/default/Wap/Template/' . $shop ['template'] . '/' );
		
		$cart_count = M('shop_cart')->where('uid='.$this->mid)->count();
		//$cart_count == 0 && $cart_count = '';
		$this->assign ( 'cart_count', $cart_count );
		$this->assign ( 'shop_id', $this->shop_id );
		$this->assign ( 'shop', $shop );
		// dump ( $shop );
		$this->assign('token', get_token ());
	}
	// 首页
	function index() {
		$this->_getShopCategory ();
		// banner
		$slideshow_list = D ( 'Slideshow' )->getShopList ( $this->shop_id );
		// dump($slideshow_list);
		$this->assign ( 'slideshow_list', $slideshow_list );
		
		// recommend_cate
		$recommend_cate = D ( 'Category' )->getRecommendList ( $this->shop_id );
		$this->assign ( 'recommend_cate', $recommend_cate );
		$this->assign ( 'recommend_cate_count', count($recommend_cate));
		//dump($recommend_cate);exit();
		// 推荐商品
		$recommend_list = D ( 'Goods' )->getRecommendList ( $this->shop_id );
		// dump($recommend_list);
		$this->assign ( 'recommend_list', $recommend_list );
		
		// 所有商品
		$goods_list = D ( 'Goods' )->getNewsList ( $this->shop_id );
		// dump($goods_list);
		$this->assign ( 'goods_list', $goods_list );
		
		$this->display ( CUSTOM_TEMPLATE_PATH . 'index.html' );
	}
	// 产品列表
	function lists() {
		$this->_getShopCategory ();
		
		$search_key = I ( 'search_key' );
        $order_key = I ( 'order_key', 'id' );
		$order_type = I ( 'order_type', 'desc' );
		$order = $order_key . ' ' . $order_type;

        if ($order_key != 'id') {
            $order .= ',id desc';
        }

		$goods_list = D ( 'Goods' )->getList ( $this->shop_id, $search_key, $order );
		// dump($goods_list);exit();
		$this->assign ( 'goods_list', $goods_list );
        $this->assign ( 'order_key', $order_key );
        $this->assign ( 'order_type', $order_type );
        $this->assign ( 'search_key', $search_key );
		$this->display ( CUSTOM_TEMPLATE_PATH . 'lists.html' );
	}
	// 产品分类列表
	function goodsListsByCategory() {
        $this->_getShopCategory ();

        $cateId = I('cid0', 0, 'intval');
        $search_key = I ( 'search_key' );
        $order_key = I ( 'order_key', 'id' );
        $order_type = I ( 'order_type', 'desc' );
        $order = $order_key . ' ' . $order_type;

        if ($order_key != 'id') {
            $order .= ',id desc';
        }

        $goods_list = D ( 'Goods' )->getCList( $this->shop_id, $cateId, $search_key, $order );
        // dump($goods_list);
        $this->assign ( 'goods_list', $goods_list );
        $this->assign ( 'order_key', $order_key );
        $this->assign ( 'order_type', $order_type );
        $this->assign ( 'search_key', $search_key );
        $this->assign ( 'cateId', $cateId );
	    $this->display ( CUSTOM_TEMPLATE_PATH . 'clists.html' );
	}
	// 用于ajax加载
	function product_model() {
		$count = I ( 'count', 10, 'intval' );
        $pageIds = I ( 'pageIds' );
		//dump($pageIds);exit();
        $search_key = I ( 'search_key' );
        $order_key = I ( 'order_key', 'id' );
        $order_type = I ( 'order_type', 'desc' );
        $order = $order_key . ' ' . $order_type;
        $cateId = I('cateId', 0, 'intval');

        if ($order_key != 'id') {
            $order .= ',id desc';
        }

        if ($cateId > 0) {
            $goods_list = D ( 'Goods' )->getCList ( $this->shop_id, $cateId, $search_key, $order, $pageIds, $count );
        }else{
            $goods_list = D ( 'Goods' )->getList ( $this->shop_id, $search_key, $order, $pageIds, $count );
        }

		// dump($goods_list);
		$this->assign ( 'goods_list', $goods_list );
		
		$this->display ( CUSTOM_TEMPLATE_PATH . 'product_model.html' );
	}
	// 产品详情
	function detail() {
	    //dump($_GET);exit();
		$this->_getShopCategory ();
		//dump($this->_getShopCategory ());exit;
		$id = I ( 'id' );
		$goods = D ( 'Goods' )->getInfo ( $id );
		//dump($goods);exit();
		$this->assign ( 'goods', $goods );
		
		$this->display ( CUSTOM_TEMPLATE_PATH . 'detail.html' );
	}
	// 加入购物车
	function addToCart() {
		$goods ['goods_id'] = I ( 'goods_id' );
		$info = D ( 'goods' )->getInfo ( $goods ['goods_id'] );
		
		$goods ['price'] = $info ['price'];
		$goods ['shop_id'] = $info ['shop_id'];
        $goods ['bid'] = $info ['bid'];
		$goods ['uid'] = $this->mid;
		$goods ['num'] = I ( 'buyCount' );
		
		echo D ( 'Cart' )->addToCart ( $goods );
	}
	// 加入收藏
	function addToCollect() {
		$goods_id = I ( 'goods_id' );
		
		echo D ( 'Collect' )->addToCollect ( $this->mid, $goods_id );
	}
	// 用户中心
	function user_center() {
		$follow_id = $this->mid;
		$follow = get_followinfo ( $follow_id );
		$this->assign ( 'follow', $follow );
		// dump($follow);
		// 全部订单
		$orderUrl = addons_url ( 'Shop://Wap/myOrder', array (
				'shop_id' => $this->shop_id 
		) );
		$this->assign ( 'ordersUrl', $orderUrl );
		// 获取待付款
		$unPayUrl = addons_url ( 'Shop://Wap/unPayOrder', array (
				'shop_id' => $this->shop_id 
		) );
		$this->assign ( 'unPayUrl', $unPayUrl );
		// 我的购物车
		$cartUrl = addons_url ( 'Shop://Wap/cart', array (
				'shop_id' => $this->shop_id 
		) );
		$this->assign ( 'cartUrl', $cartUrl );
		// 我的收藏
		$collectUrl = addons_url ( 'Shop://Wap/myCollect', array (
				'shop_id' => $this->shop_id 
		) );
		$this->assign ( 'collectUrl', $collectUrl );
        // 我的证件
        $idcardUrl = addons_url ( 'Shop://Wap/idCard' , array (
			'shop_id' => $this->shop_id
		));
        $this->assign ( 'idcardUrl', $idcardUrl );
		// 我的收获地址
		$addressUrl = addons_url ( 'Shop://Wap/myAddress', array (
				'shop_id' => $this->shop_id 
		) );
		$this->assign ( 'addressUrl', $addressUrl );
		$this->display ();
	}
	// 全部订单
	function myOrder() {
		$map ['uid'] = $this->mid;
		$myorders = D ( 'Addons://Shop/Order' )->getOrderList ( $map );
	// print_r($myorders);
		
		$this->assign ( 'allClass', 'current' );
		$this->assign ( 'orderList', $myorders );
		
		D ( 'Addons://Shop/Order' )->autoSetFinish ();
		
		$this->display ( 'order_list' );
	}
	// 获取待付款
	function unPayOrder() {
		$map ['uid'] = $this->mid;
		$map ['pay_status'] = 0;
		$unPayOrders = D ( 'Addons://Shop/Order' )->getOrderList ( $map );
		// dump('--待付款--');
		// dump($unPayOrders);
		$this->assign ( 'unPayClass', 'current' );
		$this->assign ( 'orderList', $unPayOrders );
		$this->display ( 'order_list' );
	}
	// 配送中
	function shippingOrder() {
		$map ['uid'] = $this->mid;
		$map ['is_send'] = 1;
		$unPayOrders = D ( 'Addons://Shop/Order' )->getOrderList ( $map );
		//dump($unPayOrders);
		// dump('--配送中--');
		$this->assign ( 'shippingClass', 'current' );
		$this->assign ( 'orderList', $unPayOrders );
		$this->display ( 'order_list' );
	}
	//等待评价操作-tab
	function waitCommentOrder() {
		$map ['uid'] = $this->mid;
		//$map ['is_send'] = 2;
		$map ['status_code'] = '6';
		$unPayOrders = D ( 'Addons://Shop/Order' )->getOrderList ( $map );
		// dump($unPayOrders);
		$this->assign ( 'waitClass', 'current' );
		$this->assign ( 'orderList', $unPayOrders );
		$this->display ( 'order_list' );
	}
	//立即评价 页面显示
	function CommentOrder() {
		$where ['uid'] = $this->mid;
		$where ['id'] = I ( 'id' );//订单id
		$info = M ( 'shop_order' )->where($where)->find ();
		 //dump($info);
		// $array = $this->object_array($info['goods_datas']);
		$obj=json_decode($info['goods_datas'],true);
		// echo $obj[0]['title'];
		// dump( $arrGood[0]);
		$this->assign ( 'goodTitle', $obj[0]['title'] );
		$this->assign ( 'goodId', $obj[0]['id'] );
		$this->assign ( 'orderID', I ( 'id' ));
		$this->assign ( 'shop_id',$obj[0]['shop_id']);
		$this->display ();
	}
	//立即评价 提交操作
	function submitCommentOrder() {
		$data=$_POST;
		$data['add_time']=time();
		$data ['desc'] =safe ( $_POST ['desc'] );
		$data ['token'] = get_token();
		$data ['order_id'] =  $_POST ['order_id'] ;
		$imgIds = explode ( ',', $_POST ['imageIds'] );
		foreach ( $imgIds as $imgId ) {
			$imgId = intval ( $imgId );
			if ($imgId > 0) {
				$imgsrc = get_cover_url ( $imgId );
				if ($imgsrc) {
					$data ['desc'] .= '<p class="content-mobile-img"><img  src="' . $imgsrc . '" /></p>';
				}
			}
		}
		$data ['uid'] = $this->mid;
		$result = M ( 'shop_comment' )->add($data);
		$url = addons_url ( 'Shop://Wap/myOrder', array (
			'token' =>get_token(),
			'invite_uid' => $data ['uid']
		) );
		if($result){
			 D ( 'Addons://Shop/Order' )->setStatusCode ( $data ['order_id'], 7 );//设置评价成功
			   $this->success( '评价成功！', $url);

		}else{
			   $this->error( '评价失败！' );
		}
	}
	function orderDetail() {
		$id = $map ['order_id'] = I ( 'id', 0, intval );
		if (empty ( $id )) {
			$this->error ( '订单不存在!' );
		}
		$orderDao = D ( 'Addons://Shop/Order' );
		$orderInfo = $orderDao->getInfo ( $id );
		$address_id = $orderInfo ['address_id'];
		$addressInfo = D ( 'Addons://Shop/Address' )->getInfo ( $address_id );
		$orderInfo ['goods'] = json_decode ( $orderInfo ['goods_datas'], true );

		$this->assign ( 'info', $orderInfo );
		$this->assign ( 'addressInfo', $addressInfo );
		
		if ($orderInfo ['status_code'] == 3) { // 在配送中的订单自动从接口获取快递信息
			$res = $orderDao->getSendInfo($id);
		}

		$log = M ( 'shop_order_log' )->where ( $map )->order ( 'status_code desc,cTime desc' )->select ();
		$this->assign ( 'log', $log );
		
		$this->display ();
	}
	// 我的收藏
	function myCollect() {
		$follow_id = $this->mid;
		$myCollect = D ( 'Collect' )->getMyCollect ( $follow_id );
		// dump($myCollect);
		$this->assign ( 'myCollect', $myCollect );
		$this->display ();
	}
	// 我的证件
    function idCard() {
        $data ['uid'] = $this->mid;
        if (IS_POST) {
            $info['idcardfront'] = I ( 'post.idcardfront' );
            $info['idcardobverse'] = I ( 'post.idcardobverse' );

            $res = M( 'user' )->where( $data )->save( $info );
            $this->success( '保存成功！' );
        }else{
            $info = M( 'user' )->where( $data )->find();
            //dump($info);
            $this->assign ( 'info', $info );
        }

        $this->display ();
    }
	// 我的收获地址
	function myAddress() {
		$list = D ( 'Address' )->getUserList ( $this->mid );
		// dump ( $list );
		$this->assign ( 'lists', $list );
		
		$this->display ();
		
		/*
		 * $follow_id = $this->mid;
		 * $myadress = D('Addons://Shop/Address')->getMyAddress($follow_id);
		 * dump($myadress);
		 * $this -> assign('lists',$myadress);
		 * $this -> display();
		 */
	}
	// 购物车
	function cart() {
		$list = D ( 'Cart' )->getMyCart ( $this->mid, true );
	 /* 	echo "<pre>";
		print_r($list);
		echo "<pre/>";   */
		$this->assign ( 'lists', $list );
		$this->display ();
	}
	function delCart() {
		$ids = I ( 'ids' );
		echo D ( 'Cart' )->delCart ( $ids );
	}
	// 订单确认
	function confirm_order() {
		// 订单信息
		if (IS_POST) {
			$dao = D ( 'Goods' );
			if (isset ( $_POST ['goods_ids'] )) {
				$goods_ids = I ( 'post.goods_ids' );
				$numArr = I ( 'post.buyCount' );
				foreach ( $goods_ids as $id ) {
					$goods = $dao->getInfo ( $id );
					$goods ['num'] = $numArr [$id];
					$list [] = $goods;
					
					$total_price += $goods ['num'] * $goods ['price'];
				}
			} else {
				$id = I ( 'post.goods_id' );
				$goods = $dao->getInfo ( $id );
				$goods ['num'] = I ( 'post.buyCount' );
				$list [] = $goods;
				
				$total_price = $goods ['num'] * $goods ['price'];
			}
			
			$data ['lists'] = $list;
			$data ['total_price'] = $total_price;
			
			session ( 'confirm_order', $data );
		} else {
			$data = session ( 'confirm_order' );
		}
		// dump(session('confirm_order'));
		$this->assign ( $data );
		// 收货地址
		if (isset ( $_GET ['address_id'] )) {
			$address = D ( 'Address' )->getInfo ( I ( 'get.address_id' ) );
		} else {
			$address = D ( 'Address' )->getMyAddress ( $this->mid );
		}
		$this->assign ( 'address', $address );
		// dump($address);
		
		$this->display ();
	}
	// 生成订单
	function add_order() {
		$data ['address_id'] = I ( 'address_id' );
		$data ['remark'] = I ( 'remark' );
		$data ['uid'] = $this->mid;
		
		$data ['order_number'] = date ( 'YmdHis' ) . substr ( uniqid (), 4 );
		$data ['cTime'] = NOW_TIME;
		$data ['openid'] = get_openid ();
		$data ['pay_status'] = 0;
		$info = session ( 'confirm_order' );
		
		$data ['total_price'] = $info ['total_price'];
		$data ['goods_datas'] = json_encode ( $info ['lists'] );
		if ($info ['order_from_type']) {
			$data ['order_from_type'] = $info ['order_from_type'];
		}
		$data ['shop_id'] = $this->shop_id;
		$id = D ( 'Addons://Shop/Order' )->add ( $data );
		if ($id) {
			// 删除购物车消息
			$goods_ids = getSubByKey ( $info ['lists'], 'id' );
			D ( 'Cart' )->delUserCart ( $this->mid, $goods_ids );
			echo $id;
		} else {
			echo 0;
		}
	}
	// 选择支付方式
	function choose_pay() {
        $order_id = I ( 'order_id', 0, 'intval' );
		$config = getAddonConfig ( 'Payment' );

        $data ['status_code'] = 0;
        $map ['id'] = $order_id;
        D ( 'Order' )->where ( $map )->save ( $data );
        $orderinfo = D ( 'Order' )->where ( $map )->find ();
        $jgoodsdata = $orderinfo ['goods_datas'];
        $goodsdata = json_decode ( $jgoodsdata, true );
        $token = get_token ();
        // 微信用户ID
        $openid = $orderinfo ['openid'];
        // 订单名称 商品订单表里面没有订单名称字段
        $orderName = urlencode ( $goodsdata [0] ['title'] );
        // 订单编号
        $orderNumber = $orderinfo ['order_number'];
        // 支付金额
        $price = $orderinfo ['total_price'];

        $this->assign ( 'order_id', $order_id );
        $this->assign ( 'openid', $openid );
        $this->assign ( 'token', $token );
        $this->assign ( 'orderName', $orderName );
        $this->assign ( 'orderNumber', $orderNumber );
        $this->assign ( 'price', $price );
        $this->assign ( 'config', $config );
		$this->display ();
	}
	//删除订单
	public function delete_order(){
	    $where['id'] = $_GET['order_id'];
	    $res = D ( 'Order' )->where($where)->delete();
	    if ($res) {
	        $this->success ( '删除成功' );
	    } else {
	        $this->success ( '删除失败' );
	    }
	}
	//删除收藏
	public function delete_collect(){
	    $where['id'] = $_GET['id'];
	    $res = D ('Collect')->where($where)->delete();
	    if ($res) {
	        $this->success ( '删除成功' );
	    } else {
	        $this->success ( '删除失败' );
	    }
	}
	// 选择地址
	function choose_address() {
		$list = D ( 'Address' )->getUserList ( $this->mid );
		// dump ( $list );
		$this->assign ( 'lists', $list );
		
		$this->display ();
	}
	// 添加或编辑地址
	function add_address() {
		if (IS_POST) {
			$data = I ( 'post.' );
			$data ['uid'] = $this->mid;
			$res = D ( 'Address' )->deal ( $data );
			if ($data ['from'] == 0) {
				redirect ( U ( 'myAddress', array (
						'shop_id' => $this->shop_id 
				) ) );
			} else {
				redirect ( U ( 'choose_address', array (
						'shop_id' => $this->shop_id 
				) ) );
			}
		}
		
		$id = I ( 'id' );
		if ($id) {
			$info = D ( 'Address' )->getInfo ( $id );
			$this->assign ( 'info', $info );
		}
		
		$this->display ();
	}
	//删除地址
	public function delete_address(){
	    $where['id'] = $_GET['id'];
	    $res = D ( 'Address' )->where($where)->delete();
	    if ($res) {
	        $this->success ( '删除成功' );
	    } else {
	        $this->success ( '删除失败' );
	    }
	}
	// 商店介绍
	function shop_intro() {
		$this->display ( CUSTOM_TEMPLATE_PATH . 'shop_intro.html' );
	}
	// 联系方式
	function contact() {
		$this->display ( CUSTOM_TEMPLATE_PATH . 'contact.html' );
	}
	private function _getShopCategory() {
		$list = D ( 'Category' )->getShopCategory ( $this->shop_id );
		// dump ( $list );
		$this->assign ( 'category_list', $list );
		return $list;
	}
	// 确认收货
	function confirm_get() {
		$id = $_GET['id'];
		$res = D ( 'Addons://Shop/Order' )->setStatusCode ( $id, 4 );
		if ($res) {
			$this->success ( '设置成功' );
		} else {
			$this->success ( '设置失败' );
		}
	}
	
}
