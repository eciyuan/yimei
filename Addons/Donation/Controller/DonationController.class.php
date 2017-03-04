<?php

namespace Addons\Donation\Controller;

use Addons\Donation\Controller\BaseController;

class DonationController extends BaseController{
    var $model;
    function _initialize() {
        $this->model = $this->getModel ( 'Donation' );
        parent::_initialize ();
    }
    public function lists() {
        $this->assign('normal_tips','首页展示链接:'.addons_url('Donation://Wap/index',array('publicid'=>$public_id)).'&nbsp;&nbsp;&nbsp;<a class="btn" style="padding:2px 10px" id="copyLink" href="javascript:;" data-clipboard-text="'.addons_url('Donation://Wap/index',array('publicid'=>$public_id)).'">复制链接</a><script>$.WeiPHP.initCopyBtn(\'copyLink\');
</script>');
        $this->assign('normal_tips1','入驻链接:'.addons_url('Donation://Wap/add',array('publicid'=>$public_id)).'&nbsp;&nbsp;&nbsp;<a class="btn" style="padding:2px 10px" id="copyLink" href="javascript:;" data-clipboard-text="'.addons_url('Donation://Wap/add',array('publicid'=>$public_id)).'">复制链接</a><script>$.WeiPHP.initCopyBtn(\'copyLink\');
</script>');
        $cateArr=M('DonationCategory')->getFields('id,name');
        $list_data = $this->_get_model_list ( $this->model );
        foreach ($list_data['list_data']as&$data){
            $data ['cateid'] = $cateArr [$data ['cateid']];
        }
        $this->assign ( $list_data );
        $this->display ();
    }
}
