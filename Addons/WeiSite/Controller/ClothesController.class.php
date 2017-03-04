<?php
/**
*
* 版权所有：亿次元科技
* 作    者：马晓成
* 日    期：2016-06-12
* 版    本：1.0.0
* 功能说明：清真寺控制器
*
**/

namespace Addons\WeiSite\Controller;

use Addons\WeiSite\Controller\BaseController;

class ClothesController extends BaseController {
    var $model;
    function _initialize() {
        $this->model = $this->getModel ( 'weisiteClothes' );
        parent::_initialize ();
    }
    public function lists() {
        $this->assign('normal_tips','首页展示链接:'.addons_url('Weisite://Life/index',array('publicid'=>$public_id)).'&nbsp;&nbsp;&nbsp;<a class="btn" style="padding:2px 10px" id="copyLink" href="javascript:;" data-clipboard-text="'.addons_url('WeiSite://Life/index',array('publicid'=>$public_id)).'">复制链接</a><script>$.WeiPHP.initCopyBtn(\'copyLink\');
</script>');
        $cateArr=M('weisiteClothesCategory')->getFields('id,name');
        $list_data = $this->_get_model_list ( $this->model );
        foreach ($list_data['list_data']as&$data){
            $data ['cateid'] = $cateArr [$data ['cateid']];
        }
        $this->assign ( $list_data );

        $this->display ();
    }
    function get_data($list) {

        // 取一级菜单
        foreach ( $list as $k => $vo ) {
            // dump($vo);
            if ($vo ['pid'] != 0)
                continue;
                	
                $one_arr [$vo ['id']] = $vo;
                unset ( $list [$k] );
        }
        //dump($one_arr);
        foreach ( $one_arr as $p ) {
            $data [] = $p;
            	
            $two_arr = array ();
            foreach ( $list as $key => $l ) {
                if ($l ['pid'] != $p ['id'])
                    continue;

                    $l ['title'] = '├──' . $l ['title'];
                    $two_arr [] = $l;
                    unset ( $list [$key] );
            }
            //dump($two_arr);
            $data = array_merge ( $data, $two_arr );
        }
        // dump($data);exit;
        return $data;
    }
    public function edit($model = null, $id = 0) {
        is_array ( $model ) || $model = $this->model;
        $Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
        $id || $id = I ( 'id' );
        if (IS_POST) {
            //编辑提交
            if ($_POST['pid']==$id){
                $_POST['pid']=0;
            }
            // 获取模型的字段信息
            $Model = $this->checkAttr ( $Model, $model ['id'] );
            $res = false;
            $Model->create () && $res=$Model->save ();
            if ($res !== false) {
                $this->success ( '保存' . $model ['title'] . '成功！', U ( 'lists?model=' . $model ['name'], $this->get_param ) );
            } else {
                $this->error ( $Model->getError () );
            }
        } else {
            //编辑显示
            // 获取一级菜单
            $map ['token'] = get_token ();
            $map ['pid'] = 0;
            $map ['id'] = array (
                'not in',
                $id
            );
            $list = $Model->where ( $map )->select ();
            foreach ( $list as $v ) {
                $extra .= $v ['id'] . ':' . $v ['title'] . "\r\n";
            }
            //获取数据模型属性
            $fields = get_model_attribute ( $model ['id'] );
            if (! empty ( $extra )) {
                foreach ( $fields as &$vo ) {
                    if ($vo ['name'] == 'pid') {
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
            $tmpImg=ONETHINK_ADDON_PATH.'WeiSite/View/default/TemplateSubcate/'.$data['template'].'/icon.png';
            if (file_exists($tmpImg)){
                $this->assign('tmp_img',$tmpImg);
            }
            //dump($fields);
            $this->meta_title = '编辑' . $model ['title'];
            	
            $this->display ();
        }
    }
    public function add($model = null) {
        is_array ( $model ) || $model = $this->model;
        $Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );

        if (IS_POST) {
            // 获取模型的字段信息
            $Model = $this->checkAttr ( $Model, $model ['id'] );
            if ($Model->create () && $id = $Model->add ()) {
                $this->success ( '添加' . $model ['title'] . '成功！', U ( 'lists?model=' . $model ['name'], $this->get_param ) );
            } else {
                $this->error ( $Model->getError () );
            }
        } else {
            // 要先填写appid
            $map ['token'] = get_token ();
            	
            // 获取一级菜单
            $map ['pid'] = 0;
            $list = $Model->where ( $map )->select ();
            foreach ( $list as $v ) {
                $extra .= $v ['id'] . ':' . $v ['title'] . "\r\n";
            }
            	
            $fields = get_model_attribute ( $model ['id'] );
            if (! empty ( $extra )) {
                foreach ( $fields as &$vo ) {
                    if ($vo ['name'] == 'pid') {
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
    // 客户端提交增加信息
    function indexAdd($model = null) {
        $public_info = get_token_appinfo ();
        $url=addons_url ( 'WeiSite://WeiSite/index', array ('publicid' => $public_info ['id'] ) );
        is_array ( $model ) || $model = $this->model;
        $Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
        if (IS_POST) {
            // 获取模型的字段信息
            $Model = $this->checkAttr ( $Model, $model ['id'] );
            if ($Model->create () && $id = $Model->add ()) {

                $this->success ( '添加' . $model ['title'] . '成功！可以看看伊媒微时代提供其他服务', $url);
            } else {
                $this->error ( $Model->getError () );
            }
        } else {
             
            // add_credit ( 'weisite', 86400 );
            if (file_exists ( ONETHINK_ADDON_PATH . 'WeiSite/View/default/pigcms/Index_' . $this->config ['template_index'] . '.html' )) {
                $this->pigcms_index ();
                $this->display ( ONETHINK_ADDON_PATH . 'WeiSite/View/default/pigcms/Index_' . $this->config ['template_index'] . '.html' );
            } else {
                $map1 ['token'] = $map ['token'] = get_token ();
                $map1 ['is_show'] = $map ['is_show'] = 1;
                $map ['pid'] = 0; // 获取一级分类
                 
                // dump($category);
                // 幻灯片
                $slideshow = M ( 'weisite_slideshow' )->where ( $map1 )->order ( 'sort asc, id desc' )->select ();
                foreach ( $slideshow as &$vo ) {
                    $vo ['img'] = get_cover_url ( $vo ['img'] );
                }
                	
                $this->assign ( 'slideshow', $slideshow );
                // dump($slideshow);
                	
                $map2 ['token'] = $map ['token'];
                $public_info = get_token_appinfo ( $map2 ['token'] );
                $this->assign ( 'publicid', $public_info ['id'] );
                	
                $this->assign ( 'manager_id', $this->mid );
                	
                $this->_footer ();
                // $backgroundimg=ONETHINK_ADDON_PATH.'WeiSite/View/default/TemplateIndex/'.$this->config['template_index'].'/icon.png';
                if ($this->config ['show_background'] == 0) {
                    $this->config ['background'] = '';
                    $this->assign ( 'config', $this->config );
                }
                $this->display ();
            }
             
        }
    }

    // 3G页面底部导航
    function _footer($temp_type = 'weiphp') {
        if ($temp_type == 'pigcms') {
            $param ['token'] = $token = get_token ();
            $param ['temp'] = $this->config ['template_footer'];
            $url = U ( 'Home/Index/getFooterHtml', $param );
            $html = wp_file_get_contents ( $url );
            // dump ( $url );
            // dump ( $html );
            $file = RUNTIME_PATH . $token . '_' . $this->config ['template_footer'] . '.html';
            if (! file_exists ( $file ) || true) {
                file_put_contents ( $file, $html );
            }

            $this->assign ( 'cateMenuFileName', $file );
        } else {
            $list = D ( 'Addons://WeiSite/Footer' )->get_list ();

            foreach ( $list as $k => $vo ) {
                if ($vo ['pid'] != 0)
                    continue;

                    $one_arr [$vo ['id']] = $vo;
                    unset ( $list [$k] );
            }

            foreach ( $one_arr as &$p ) {
                $two_arr = array ();
                foreach ( $list as $key => $l ) {
                    if ($l ['pid'] != $p ['id'])
                        continue;

                        $two_arr [] = $l;
                        unset ( $list [$key] );
                }

                $p ['child'] = $two_arr;
            }
            $this->assign ( 'footer', $one_arr );
            if (empty ( $this->config ['template_footer'] )) {
                $this->config ['template_footer'] = 'V1';
            }
            $html = $this->fetch ( ONETHINK_ADDON_PATH . 'WeiSite/View/default/TemplateFooter/' . $this->config ['template_footer'] . '/footer.html' );
            $this->assign ( 'footer_html', $html );
        }
    }

}
