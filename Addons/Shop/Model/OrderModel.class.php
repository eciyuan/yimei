<?php

namespace Addons\Shop\Model;

use Think\Model;

/**
 * Shop模型
 */
class OrderModel extends Model {
	protected $tableName = 'shop_order';

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
	function getOrderList($map) {
		$list = ( array ) $this->where ( $map )->order ( 'id desc' )->select ();
		foreach ( $list as &$v ) {
			$v ['order_from_type'] = $v ['order_from_type'] == 0 ? '商城' : '电视购物';
			$goods = json_decode ( $v ['goods_datas'], true );
			$goods = $goods [0];
			$goods ['goods_id'] = $goods ['id'];
			unset ( $goods ['id'] );
			$v = array_merge ( $v, $goods );
			
			if ($v ['pay_status'] == 0) {
				if ($v ['pay_type'] == 10)
					$v ['order_status'] = '到店自提';
				else
					$v ['order_status'] = '等待买家付款';
			} else {
				$v ['order_status'] = '买家已付款';
			}
			$v ['status_code_name'] = $this->_status_code_name ( $v ['status_code'] );
			$v ['goods'] = json_decode ( $v ['goods_datas'], true );
		}

		return $list;
	}
	/*获取物流信息*/
	function getSendInfo($id) {
		$info = $this->getInfo($id);
        //电商ID
        defined('EBusinessID') or define('EBusinessID', 1267413);
        //电商加密私钥，快递鸟提供，注意保管，不要泄漏
        defined('AppKey') or define('AppKey', '969e140d-2d97-48f0-bae9-6501a3bcdf38');
        //请求url
        defined('ReqURL') or define('ReqURL', 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx');

        $requestData= "{'OrderCode':'".$id."','ShipperCode':'".$info['send_code']."','LogisticCode':'".$info['send_number']."'}";
        $datas = array(
            'EBusinessID' => EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, AppKey);
        $data = json_decode($this->sendPost(ReqURL, $datas));
		if ($data->Traces) {
			$save['order_id'] = $id;
			$save['status_code'] = $data->State;
			$save['extend'] = 1;
			M('shop_order_log')->where($save)->delete();
			foreach($data->Traces as $vo) {
				$save['cTime'] = strtotime($vo->AcceptTime);
				$save['remark'] = $vo->AcceptStation;
				M('shop_order_log')->add($save);
			}
		}

		return $data;
	}
	function update($id, $save) {
		$map ['id'] = $id;
		$this->where ( $map )->save ( $save );
		$this->getInfo ( $id, true );
	}
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
		
		M ( 'shop_order_log' )->add ( $data );
		
		return true;
	}
	function autoSetFinish() {
		$over_time = NOW_TIME - 15 * 24 * 3600; // 15天后自动设置为已收货
		
		$map ['status_code'] = $map2 ['status_code'] = 3;
		$map ['pay_status'] = 1;
		
		$order_ids = $this->where ( $map )->getFields ( 'id' );
		if (empty ( $order_ids ))
			return false;
		
		$map2 ['order_id'] = array (
				'in',
				$order_ids 
		);
		$map2 ['extend'] = '0';
		$map2 ['cTime'] = array (
				'lt',
				$over_time 
		);
		$order_ids = M ( 'shop_order_log' )->where ( $map2 )->getFields ( 'order_id' );
		if (empty ( $order_ids ))
			return false;
		
		foreach ( $order_ids as $id ) {
			$this->setStatusCode ( $id, 6 );
		}
	}
    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if($url_info['port']=='')
        {
            $url_info['port']=80;
        }

        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded;charset=utf-8\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
}
