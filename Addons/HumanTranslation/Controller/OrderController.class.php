<?php
namespace Addons\HumanTranslation\Controller;
use Home\Controller\AddonsController;
class OrderController extends AddonsController {
	var $model;
	function _initialize() {
		$this->model = $this->getModel ( 'translation_order' );
		parent::_initialize ();
		$type = I ( 'type', 0, 'intval' );
		if (_ACTION == 'Order') {
			$type = 10;
			$res ['title'] = '获奖查询';
			$res ['url'] = '###';
			$res ['class'] = 'current';
			$nav [] = $res;
		}
		if (_ACTION == 'share_lists') {
			$type = 10;
			$res ['title'] = '分享记录';
			$res ['url'] = '###';
			$res ['class'] = 'current';
			$nav [] = $res;
		}
		if (_ACTION == 'collect_lists') {
			$type = 10;
			$res ['title'] = '领取人列表';
			$res ['url'] = '###';
			$res ['class'] = 'current';
			$nav [] = $res;
		}
		if (_ACTION == 'sncode_lists') {
			$type = 10;
			$res ['title'] = '核销记录';
			$res ['url'] = '###';
			$res ['class'] = 'current';
			$nav [] = $res;
		}

		$res ['title'] = '翻译配置';
		$res ['url'] = addons_url ( 'HumanTranslation://HumanTranslation/config', array('mdm'=>$_GET['mdm']) );
		$res ['class'] = _ACTION == config ? 'current' : '';
		$nav [] = $res;

		$param['mdm']=$_GET['mdm'];
		$param ['type'] = 0;
		$res ['title'] = '订单管理';
		$res ['url'] = addons_url ( 'HumanTranslation://Order/lists', $param );
		$res ['class'] = $type == $param ['type'] && _ACTION == 'lists' ? 'current' : '';
		$nav [] = $res;

		$param ['type'] = 1;
		$res ['title'] = '翻译人员管理';
		$res ['url'] = addons_url ( 'HumanTranslation://Author/lists', $param );
		$res ['class'] = $type == $param ['type'] ? 'current' : '';
		$nav [] = $res;

		$param ['type'] = 2;
		$res ['title'] = '语言种类管理';
		$res ['url'] = addons_url ( 'HumanTranslation://language/lists', $param );
		$res ['class'] = $type == $param ['type'] ? 'current' : '';
		$nav [] = $res;

		$param ['type'] = 3;
		$res ['title'] = '翻译评价管理';
		$res ['url'] = addons_url ( 'HumanTranslation://comment/lists', $param );
		$res ['class'] = $type == $param ['type'] ? 'current' : '';
		$nav [] = $res;

		$param ['type'] = 4;
		$res ['title'] = '日常用语';
		$res ['url'] = addons_url ( 'HumanTranslation://commonSession/lists', $param );
		$res ['class'] = $type == $param ['type'] ? 'current' : '';
		$nav [] = $res;
		$this->assign ( 'nav', $nav );
	}
	// 通用插件的列表模型
	public function lists() {
		$model = $this->getModel ( 'translation_order' );//获取全部的模型数据
		//dump($model);exit();
		$page = I ( 'p', 1, 'intval' ); // 默认显示第一页数据

		// 解析列表规则
		$list_data = $this->_list_grid ( $model );
		$row = empty ( $model ['list_row'] ) ? 20 : $model ['list_row'];
		// 读取模型数据列表
		$name = parse_name ( get_table_name ( $model ['id'] ), true );
		$modelName=M($name);
		$data = $modelName->field ( true )->order ( 'id desc' )->page ( $page, $row )->select ();

		/* 查询记录总数 */
		$count =$modelName->count ();
		$list_data ['list_data'] = $data;
		foreach ( $list_data ['list_data'] as &$vo ) {
			$param ['id'] = $vo ['id'];
			//$order = $modelName->getInfo ( $vo ['id'] );
			 //dump($order);exit();
			//$vo = array_merge ( $vo, $order );
			$follow = get_followinfo ( $vo ['uid'] );
			$param2 ['uid'] = $follow ['uid'];
			$vo ['uid'] = '<a target="_blank" href="' . addons_url ( 'UserCenter://UserCenter/detail', $param2 ) . '">' . $follow ['nickname'] . '</a>';
			$vo ['order_number'] = '<a href="' . addons_url ( 'HumanTranslation://Order/detail', $param ) . '">' . $vo ['order_number'] . '</a>';
			$vo ['income'] = '<a href="' . addons_url ( 'HumanTranslation://Order/income', $param ) . '">查看收入</a>';
		}
		//dump($list_data ['list_data'] );
		$this->assign('search_url',U('lists',array('mdm'=>$_GET['mdm'])));
		// 分页
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$list_data ['_page'] = $page->show ();
		}

			$this->assign ( $list_data );
			//dump($list_data);

			$this->display ();
	}
	// 通用插件的编辑模型
	public function edit() {
		$model = $this->model;
		$id = I ( 'id' );
		if (IS_POST) {
			$Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $model ['id'] );
			if ($Model->create () && $Model->save ()) {
				D ( 'Common/Keyword' )->set ( $_POST ['keyword'], _ADDONS, $id, $_POST ['keyword_type'], 'custom_reply_news' );
				
				$this->success ( '保存' . $model ['title'] . '成功！', U ( 'lists?model=' . $model ['name'] ) );
			} else {
				$this->error ( $Model->getError () );
			}
		} else {
			$fields = get_model_attribute ( $model ['id'] );
			$extra = $this->getCateData ();
			if (! empty ( $extra )) {
				foreach ( $fields as &$vo ) {
					if ($vo ['name'] == 'cate_id') {
						$vo ['extra'] .= "\r\n" . $extra;
					}
				}
			}
			
			// 获取数据
			$data = M ( get_table_name ( $model ['id'] ) )->find ( $id );
			$data || $this->error ( '数据不存在！' );
			
			$token = get_token ();
			if (isset ( $data ['token'] ) && $token != $data ['token'] && defined ( 'ADDON_PUBLIC_PATH' )) {
				$this->error ( '非法访问！' );
			}
			
			$this->assign ( 'fields', $fields );
			$this->assign ( 'data', $data );
			$this->meta_title = '编辑' . $model ['title'];
			
			$this->display ();
		}
	}
	
	// 通用插件的增加模型
	public function add() {
		$model = $this->model;
		$Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
		
		if (IS_POST) {
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $model ['id'] );
			if ($Model->create () && $id = $Model->add ()) {
				D ( 'Common/Keyword' )->set ( $_POST ['keyword'], _ADDONS, $id, $_POST ['keyword_type'], 'custom_reply_news' );
				
				$this->success ( '添加' . $model ['title'] . '成功！', U ( 'lists?model=' . $model ['name'] ) );
			} else {
				$this->error ( $Model->getError () );
			}
		} else {
			$fields = get_model_attribute ( $model ['id'] );
			
			$extra = $this->getCateData ();
			if (! empty ( $extra )) {
				foreach ( $fields as &$vo ) {
					if ($vo ['name'] == 'cate_id') {
						$vo ['extra'] .= "\r\n" . $extra;
					}
				}
			}
			
			$this->assign ( 'fields', $fields );
			$this->meta_title = '新增' . $model ['title'];
			
			$this->display ();
		}
	}
	
	// 通用插件的删除模型
	public function del() {
		parent::common_del ( $this->model );
	}
	
	// 获取所属分类
	function getCateData() {
		$map ['is_show'] = 1;
		$map ['token'] = get_token ();
		$list = M ( 'shop_goods_category' )->where ( $map )->select ();
		foreach ( $list as $v ) {
			$extra .= $v ['id'] . ':' . $v ['title'] . "\r\n";
		}
		return $extra;
	}
	function set_show() {
		$save ['is_show'] = 1 - I ( 'is_show' );
		$map ['id'] = I ( 'id' );
		$res = M ( 'shop_goods' )->where ( $map )->save ( $save );
		$this->success ( '操作成功' );
	}
	function detail() {
		$id = I ( 'id' );
		$orderDao = D ( 'Addons://Shop/Order' );
		$orderInfo = $orderDao->getInfo ( $id );
		$address_id = $orderInfo ['address_id'];
		$addressInfo = D ( 'Addons://Shop/Address' )->getInfo ( $address_id );
		
		$orderInfo ['goods'] = json_decode ( $orderInfo ['goods_datas'], true );
		 //dump ( $orderInfo );
		$this->assign ( 'info', $orderInfo );
		$this->assign ( 'addressInfo', $addressInfo );
		$this->display ();
	}
	function get_send_info() {
		$id = I ( 'id' );
		$res = D ( 'Addons://Shop/Order' )->getSendInfo ( $id );
		
		$html = '';
		if ($res->Traces) {
            foreach ( $res->Traces as $vo ) {
                $html .= '<p>' . $vo->AcceptTime . ' ' . $vo->AcceptStation . '</p>';
            }
		} else {
            $html = '<p>' . $res->Reason . '</p>';
		}
		echo $html;
	}
	function set_pay_status() {
		$id = I ( 'id' );
		$save ['pay_status'] = 1;
		$res = D ( 'Addons://Shop/Order' )->update ( $id, $save );
		D ( 'Addons://Shop/Order' )->setStatusCode ( $id, 5 );
		
		echo 1;
	}
	function set_confirm() {
		$id = I ( 'id' );
		$res = D ( 'Addons://Shop/Order' )->setStatusCode ( $id, 2 );
		if ($res) {
			$this->success ( '设置成功' );
		} else {
			$this->success ( '设置失败' );
		}
	}
	// 追踪订单状态
	function confirmPayStatus() {
	    $res = D ( 'Addons://Shop/Order' )->setStatusCode($_GET['order_id'], $_GET['status_code']);
	    if ($res) {
	        $this->success ( '设置成功' );
	    } else {
	        $this->success ( '设置失败' );
	    }
	}
	/*
	 * 查看翻译人员
	收入记录
	*/
	public function income(){
	$this->display();
	}
}