<?php

namespace Addons\HumanTranslation\Model;

use Think\Model;

/**
 * Shop模型
 */
class TranslationOrderModel extends Model {
	protected $tableName = 'translation_order';

	function getInfo($id, $update = false, $data = array()) {
		$key = 'Order_getInfo_' . $id;
		$info = S ( $key );
		if ($info === false || $update) {
			$info = ( array ) (count ( $data ) == 0 ? $this->find ( $id ) : $data);
			if (count ( $info ) != 0) {
				$info ['order_from_type'] = $info ['order_from_type'] == 0 ? '商城' : '电视购物';
				switch ($info ['pay_type']) {
					case 0 : // 微信支付
						$info ['common'] = '微信支付';
						break;
					case 10 :
						$info ['common'] = '到店自提';
						break;
				}
				$code = array (
					"SF" => '顺丰快递',
					"STO" => '申通快递',
					"YD" => '韵达快递',
					"YTO" => '圆通速递',
					"ZTO" => '中通速递',
					"HHTT" => '天天快递',
					"EMS" => 'EMS',
					"DBL" => '德邦'
				);
				$info ['send_code_name'] = $code [$info ['send_code']];
				$info ['status_code_name'] = $this->_status_code_name ( $info ['status_code'] );
				$info ['status'] = $info ['pay_status'] == 0 ? '未支付' : '已支付';
				$goods = json_decode ( $info ['goods_datas'], true );
				$goods = $goods [0];
				$goods ['goods_id'] = $goods ['id'];
				unset ( $goods ['id'] );
				// $i['goodsinfo']=$goods;
				$info = array_merge ( $info, $goods );
			}

			S ( $key, $info );
		}
		return $info;
	}
	function getOrderList($map) {
		$list = ( array ) $this->where ( $map )->order ( 'id desc' )->select ();
		foreach ( $list as &$v ) {
			/*$v ['pay_status'] = $v ['pay_status'] == 0 ? '待付款' : '成功';
			$goods = $goods [0];
			$goods ['goods_id'] = $goods ['id'];
			unset ( $goods ['id'] );
			$v = array_merge ( $v, $goods );*/
			$v ['countSecondContent'] =$this->get_task_count($v ['id']);
			//$v ['img_url'] =$this->get_task_count($v ['id']); 取出图片
		}
		return $list;
	}
	/*
	 * 订单
	 * 详细操作
	 */
	function getOrderDetail($id) {
		$where['id']=$id;
		$list = M ( 'translation_order' )->where ( $where )->find ();
		if($list ['select_language_id']){
			$list ['select_language_id'] =$this->get_order_name($list ['select_language_id']);
		}
		if($list ['goal_language_id']){
			$list ['goal_language_id'] =$this->get_order_name($list ['goal_language_id']);
		}
		$list ['countSecondContent'] =$this->get_task_count($list ['id']);
		return $list;
	}
	/*
	 * 订单
	 * 获得每种语言名称
	 */
	function get_order_name($id){
		$cate = M ( 'translation_language' )->find ( $id );
		return  $cate;
	}
	/*
	 * 计算
	 * 任务计算
	 *
	 */
	function get_task_count($id){
		$where['order_id']=$id;
		//$cate = M ( 'translation_task' )->where ( $where )->select();
		$count = M ( 'translation_task' )->where ( $where )->count();
		return  $count;
	}
	function update($id, $save) {
		$map ['id'] = $id;
		$this->where ( $map )->save ( $save );
		$this->getInfo ( $id, true );
	}
	function _status_code_name($code) {
		$status_code = array (
			0 => '待支付',
			1 => '待商家确认',
			2 => '待发货',
			3 => '配送中',
			4 => '确认已收货',
			5 => '确认已收款',
			6 => '待评价',
			7 => '已评论'
		);
		return $status_code [$code];
	}
	/*
	设置支付状态
	1:已经支付
	2:已经评价
	0:未支付
	*/
	function setStatusCode($id, $code) {
		$save ['status_code'] = $code;
		$res = $this->update ( $id, $save );

		$data ['order_id'] = $id;
		$data ['status_code'] = $code;
		$data ['cTime'] = NOW_TIME;

		$info = $this->getInfo ( $id );
		switch ($code) {
			case '1' :
				$data ['remark'] = '您提交了订单，请等待商家确认';
				break;
			case '2' :
				$data ['remark'] = '商家已经确认订单，开始拣货';
				break;
			case '3' :
				$data ['remark'] = '您的订单已经发货，发货快递： ' . $info ['send_code_name'] . ', 快递单号： ' . $info ['send_number'];
				$data ['extend'] = '0';
				break;
			case '4' :
				$data ['remark'] = '确认已收货';
				break;
			case '5' :
				$data ['remark'] = '确认已收款';
				break;
			case '6' :
				$data ['remark'] = '订单已完成，请评价这次服务';
				break;
			case '7' :
				$data ['remark'] = '评论完成，欢迎下次再来';
				break;
		}
		M ( 'translation_order_log' )->add ( $data );
		return true;
	}
}
