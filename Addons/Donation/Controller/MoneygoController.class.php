<?php

namespace Addons\Donation\Controller;

use Addons\Donation\Controller\BaseController;

class MoneygoController extends BaseController{
    var $model;
    function _initialize() {
        $this->model = $this->getModel ( 'Moneygo' );
        parent::_initialize ();
    }
    public function lists() {
        $cateArr=M('Donation')->getFields('id,title');
        $list_data = $this->_get_model_list ( $this->model );
        foreach ($list_data['list_data']as&$data){
            $data ['cateid'] = $cateArr [$data ['cateid']];
        }
        $this->assign ( $list_data );
        $this->display ();
    }
}
