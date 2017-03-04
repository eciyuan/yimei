<?php

namespace Addons\Shop\Model;

use Think\Model;

/**
 * Shop模型
 */
class CartModel extends Model {
	protected $tableName = 'shop_cart';

	function getMyCart($uid, $update = false) {
		$key = 'Cart_getMyCart_' . $uid;
		$info = S ( $key );
		if ($info === false || $update) {
			$map ['uid'] = $uid;
			$info = $this->where ( $map )->select ();
			$goodsDao = D ( 'Addons://Shop/Goods' );
			$shopDao = D ( 'Addons://Shop/Shop' );
			foreach ( $info as $k => $v ) {
                $goods = $goodsDao->getInfo( $v['goods_id'] );
                $business = M('shop_business')->where('id='.$goods['bid'])->find();
                $ninfo[$goods['bid']]['business'] = $business['uname'];
                $ninfo[$goods['bid']]['bid'] = $goods['bid'];
                $ninfo[$goods['bid']]['child'][$v['goods_id']]['id'] = $v['id'];
                $ninfo[$goods['bid']]['child'][$v['goods_id']]['num'] = $v['num'];
                $ninfo[$goods['bid']]['child'][$v['goods_id']]['goods'] = $goods;
                $ninfo[$goods['bid']]['child'][$v['goods_id']]['shop'] = $shopDao->getInfo ( $v['shop_id'] );
			}
			S ( $key, $ninfo );
            return $ninfo;
		}else{
            return $info;
        }
	}
	function addToCart($goods) {
		$myList = $this->getMyCart ( $goods['uid'] );
        $where['uid'] = $goods['uid'];
        $where['goods_id'] = $goods['goods_id'];
        $where['shop_id'] = $goods['shop_id'];
        $where['openid'] = get_openid();
        $info = $this->where($where)->find();

        if ($info) {
            $num = $info['num'] + $goods['num'];
            $map['id'] = $info['id'];
            $this->where($map)->setField('num', $num);
        } else {
            $goods['openid'] = get_openid();
            unset($goods['bid']);
            $this->add($goods);
        }

		return $this->where('uid='.$goods['uid'])->count();
	}
	function delCart($ids) {
		$ids = array_filter ( explode ( ',', $ids ) );
		if (empty ( $ids ))
			return 0;
		
		$map ['id'] = array (
				'in',
				$ids 
		);

		return $this->where ( $map )->delete ();
	}
	function delUserCart($uid, $goods_ids) {
		$map ['goods_id'] = array (
				'in',
				$goods_ids 
		);
		$map ['uid'] = $uid;
		$res = $this->where ( $map )->delete ();
		
		$this->getMyCart ( $goods ['uid'], true );
		return $res;
	}
}
