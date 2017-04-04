<?php

namespace Addons\HumanTranslation\Model;

use Think\Model;

/**
 * 订单模型
 */
class TranslationOrderModel extends Model {
	protected $tableName = 'translation_order';
	protected $fields = array(
		'id',
		'title',
		'uid',
		'remark',
		'order_number',
		'cTime',
		'total_price',
		'token',
		'pay_type',
		'order_status',
		'mobile',
		'select_select_language_id',
		'goal_language_id',
		'translation_type',
		'content',
		'img_ids',
		'after_content',
		'count_str',
		'click',
		'order_status',
		'price',
		'openid',
		'_pk'=>'id',
		'_autoinc'=>true
	);
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
	/*获取全部订单*/
	function getOrderList($map,$start,$limit) {
		$list = ( array ) $this->where ( $map )->limit($start,$limit)->order ( 'id desc' )->select ();
		foreach ( $list as &$v ) {
			//$v ['content'] = html_entity_decode ( $v ['content'], ENT_QUOTES, 'UTF-8' );
			$v ['countSecondContent'] =$this->get_task_count($v ['id']);
		}
		return $list;
	}
	/*获取全部译文*/
	function getTaskList($map,$start,$limit) {
		$list = M ( 'translation_task' )->where ( $map )->limit($start,$limit)->order ( 'id desc' )->select ();
		foreach ( $list as &$v ) {
			$v ['orderContent'] =$this->fromTaskGetOrderDetail($v ['order_id']);
		}
		return $list;
	}
	/*
	 * 订单
	 * 详细操作
	 * 用订单id附带关联译文
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
		$list ['SecondContent'] =$this->get_task_detail($list ['id']);
		return $list;
	}
	/*
	 * 订单
	 * 详细操作
	 * 用译文order_id关联订单详细
	 */
	function fromTaskGetOrderDetail($id) {
		$where['id']=$id;
		$list = M ( 'translation_order' )->where ( $where )->find ();
		if($list ['select_language_id']){
			$list ['select_language_id'] =$this->get_order_name($list ['select_language_id']);
		}
		if($list ['goal_language_id']){
			$list ['goal_language_id'] =$this->get_order_name($list ['goal_language_id']);
		}
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
	/*
	 * 译文详情
	 *
	 */
	function get_task_detail($id){
		$where['order_id']=$id;
		$detail = M ( 'translation_task' )->where ( $where )->select();
		return  $detail;
	}
	function update($id, $save) {
		$map ['id'] = $id;
		$this->where ( $map )->save ( $save );
		$this->getInfo ( $id, true );
	}
	function _status_code_name($code) {
		$status_code = array (
			0 => '待支付',
			1 => '正在申请竞标',
			2 => '竞标结束，请评价',
			3 => '评论完成'
		);
		return $status_code [$code];
	}
	/*
	支付订单日志记录
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
				$data ['remark'] = '您提交了订单，请等待翻译人员接单';
				break;
			case '2' :
				$data ['remark'] = '有人接单，正在申请竞标';
				break;
			case '3' :
				$data ['remark'] = '投标结束，请评价这次服务';
				$data ['extend'] = '0';
				break;
			case '4' :
				$data ['remark'] = '评论完成，欢迎下次再来';
				break;
		}
		M ( 'translation_order_log' )->add ( $data );
		return true;
	}
}
