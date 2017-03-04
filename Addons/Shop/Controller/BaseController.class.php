<?php

namespace Addons\Shop\Controller;

use Home\Controller\AddonsController;

class BaseController extends AddonsController {
	var $shop_id;
	function _initialize() {
		parent::_initialize ();
		// 获取当前登录的用户的商城
		$map ['token'] = get_token ();
		$map ['manamger_id'] = $this->mid;
		$this->shop_id = 0;
		
		$currentShopInfo = M ( 'shop' )->where ( $map )->find ();
		if ($currentShopInfo) {
			$this->shop_id = $currentShopInfo ['id'];
		} elseif (_ACTION != 'summary' && _ACTION != 'add') {
			redirect ( addons_url ( 'Shop://Shop/summary' ) );
		}
		
		$controller = strtolower ( _CONTROLLER );
		
		$res ['title'] = '商店管理';
		$res ['url'] = addons_url ( 'Shop://Shop/lists' );
		$res ['class'] = ($controller == 'shop' && _ACTION == "lists") ? 'current' : '';
		$nav [0] = $res;
		
		$nav = array ();
		$this->assign ( 'nav', $nav );
		
		define ( 'CUSTOM_TEMPLATE_PATH', ONETHINK_ADDON_PATH . 'Shop/View/default/Wap/Template' );
	}

    /**
     * 订单发货通知
     *
     * {{first.DATA}}
     * 订单内容：{{keyword1.DATA}}
     * 物流服务：{{keyword2.DATA}}
     * 快递单号：{{keyword3.DATA}}
     * 收货信息：{{keyword4.DATA}}
     * {{remark.DATA}}
     */
    function wxSendDeliver($touser, $url, $data) {
        $data = array(
            'first'=>array(
                'value'=>$data['first'],
                'color'=>'#173177'
            ),
            'keyword1'=>array(
                'value'=>$data['keyword1'],
                'color'=>'#173177'
            ),
            'keyword2'=>array(
                'value'=>$data['keyword2'],
                'color'=>'#173177'
            ),
            'keyword3'=>array(
                'value'=>$data['keyword3'],
                'color'=>'#173177'
            ),
            'keyword4'=>array(
                'value'=>$data['keyword4'],
                'color'=>'#173177'
            ),
            'remark'=>array(
                'value'=>$data['remark'],
                'color'=>'#173177'
            )
        );
        $template_id = 'GLsTM9JrdERdh39q-SBsWYwDnMjELx7H-eZbe_oca4s';
        $result = $this->wxSetSend($touser, $template_id, $url, $data, $topcolor = '#7B68EE');

        return $result;
    }

    /******************发送自定义的模板消息*******************************/
    function wxSetSend($touser, $template_id, $url, $data, $topcolor = '#7B68EE') {
        $template = array(
            'touser' => $touser,
            'template_id' => $template_id,
            'url' => $url,
            'topcolor' => $topcolor,
            'data' => $data
        );
        $jsonData       = urldecode(json_encode($template));
        $wxAccessToken  = get_access_token();
        $url            = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$wxAccessToken;
        $result         = $this->wxHttpsRequest($url, $jsonData);

        return $result;
    }

    /******************微信提交API方法，返回微信指定JSON********************/
    function wxHttpsRequest($url, $data = null) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}
