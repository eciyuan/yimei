<?php
/**
*
* 版权所有：亿次元科技
* 作    者：马晓成
* 日    期：2016-06-12
* 版    本：1.0.0
* 功能说明：清真寺控制器
*token添加get_token(),uid添加get_mid(),cTime添加time()
**/

namespace Addons\WeiSite\Controller;
use Addons\WeiSite\Controller\BaseController;
class MosqueController extends BaseController {
    var $model;
     public function _initialize() {
        $this->model = $this->getModel ( 'weisite_mosque' );
        parent::_initialize ();
    }
    public function lists() {
        $this->assign('normal_tips','我要注册清真寺链接:'.addons_url('WeiSite://Mosque/indexAdd',array('publicid'=>$public_id)).'&nbsp;&nbsp;&nbsp;<a class="btn" style="padding:2px 10px" id="copyLink" href="javascript:;" data-clipboard-text="'.addons_url('WeiSite://Mosque/indexAdd',array('publicid'=>$public_id)).'">复制链接</a><script>$.WeiPHP.initCopyBtn(\'copyLink\');
</script>');
         $this->assign('normal_tips1','前台首页链接:'.addons_url('WeiSite://Mosque/index',array('publicid'=>$public_id)).'&nbsp;&nbsp;&nbsp;<a class="btn" style="padding:2px 10px" id="copyLink" href="javascript:;" data-clipboard-text="'.addons_url('WeiSite://Mosque/index',array('publicid'=>$public_id)).'">复制链接</a><script>$.WeiPHP.initCopyBtn(\'copyLink\');
</script>');
      
        $list_data = $this->_get_model_list ( $this->model );
        $this->assign ( $list_data );
        $this->display ();
    }
    /* 清真寺前端index */
 public function index(){
        // add_credit ( 'weisite', 86400 );
        if (file_exists ( ONETHINK_ADDON_PATH . 'WeiSite/View/default/pigcms/Index_' . $this->config ['template_index'] . '.html' )) {
            $this->pigcms_index ();
            $this->display ( ONETHINK_ADDON_PATH . 'WeiSite/View/default/pigcms/Index_' . $this->config ['template_index'] . '.html' );
        } else {
            $map1 ['token'] = $map ['token'] = get_token ();
            $map1 ['is_show'] = $map ['is_show'] = 1;
           $map1 ['cate_id'] = $this->getSlideshowCategory('mosque');
            $map ['pid'] = 0; // 获取一级分类
             
            // 分类
            $category = M ( 'weisite_category' )->where ( $map )->order ( 'sort asc, id desc' )->select ();
            foreach ( $category as &$vo ) {
                $vo ['icon'] = get_cover_url ( $vo ['icon'] );
                empty ( $vo ['url'] ) && $vo ['url'] = addons_url ( 'WeiSite://WeiSite/lists', array (
                    'cate_id' => $vo ['id']
                ) );
            }
            // 提取主要数据
            $model=M('WeisiteMosque');
            $where['status']="2";
            $where['province']=array('NEQ','省份');   //排除默认
            $parent=$model->where($where)->field("province")->distinct(true)->select();//提取城市
            $this->assign('city',$parent);
            
            // 幻灯片
            $slideshow = M ( 'weisite_slideshow' )->where ( $map1 )->order ( 'sort asc, id desc' )->select ();
            foreach ( $slideshow as &$vo ) {
                $vo ['img'] = get_cover_url ( $vo ['img'] );
            }
            	
            foreach ( $slideshow as &$data ) {
                foreach ( $category as $cate ) {
                    if ($data ['cate_id'] == $cate ['id'] && empty ( $data ['url'] )) {
                        $data ['url'] = $cate ['url'];
                    }
                }
            }
            $this->assign ( 'slideshow', $slideshow );
            // dump($slideshow);
            	
            // dump($category);
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
          //  $this->assign('mosque',$mosqueRe);
    }
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
	   // $url=addons_url ( 'WeiSite://WeiSite/index', array ('publicid' => $public_info ['id'] ) );
	    is_array ( $model ) || $model = $this->model;
	    $Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
	    if (IS_POST) {
	        // 获取模型的字段信息
	        $Model = $this->checkAttr ( $Model, $model ['id'] );
	        $result=$Model->create ();
	        $id = $Model->add ();
	        if ($result && $id) {
	            cookie('mosqueName', $result ['name']);
	            cookie('mosqueId',$id);
	            // $url='http://mp.weixin.qq.com/s?__biz=MzI2ODMwNzE5MA==&mid=100000108&idx=1&sn=b4b3a2b2dea06f219a9ea65deec6b7b7';
	            $urlCenter=addons_url ( 'WeiSite://Mosque/indexCenter', array ('publicid' => $public_info ['id'],'id'=>$id) );
	           // $this->qrcode( $model ['name'],$urlCenter,$id);
	            $this->success ( '添加' .$result ['name']. '成功！', $urlCenter);
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
		// add_credit ( 'weisite', 86400 );
		if (file_exists ( ONETHINK_ADDON_PATH . 'WeiSite/View/default/pigcms/Index_' . $this->config ['template_index'] . '.html' )) {
			$this->pigcms_index ();
			$this->display ( ONETHINK_ADDON_PATH . 'WeiSite/View/default/pigcms/Index_' . $this->config ['template_index'] . '.html' );
		} else {
			$map1 ['token'] = $map ['token'] = get_token ();
			// dump($category);
			
			$map2 ['token'] = $map ['token'];
			$public_info = get_token_appinfo ( $map2 ['token'] );
			$this->assign ( 'publicid', $public_info ['id'] );
			
			$this->assign ( 'manager_id', $this->mid );
			
			$this->_footer ();
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
	/* 主持个人中心 */
	public function indexCenter(){
	    // 幻灯片
	    $map1 ['token'] = $map ['token'] = get_token ();
	    $map1 ['is_show'] = $map ['is_show'] = 1;
	    $map1 ['cate_id'] = $this->getSlideshowCategory('mosque');
	    $slideshow = M ( 'weisite_slideshow' )->where ( $map1 )->order ( 'sort asc, id desc' )->select ();
	    foreach ( $slideshow as &$vo ) {
	        $vo ['img'] = get_cover_url ( $vo ['img'] );
	    }
	    $this->assign ( 'slideshow', $slideshow );
	    // dump($slideshow);
	    if (cookie('mosqueId')) {
	        //登录跳转
	        $whereArt['cateid']=cookie('mosqueId');//资讯ID
	        $where['id']=cookie('mosqueId');//基本信息ID
	        $whereNotice['authorID']=cookie('mosqueId');//通知ID
	    }
	    if (!empty($_GET['id'])){
	        $whereArt['cateid']=$_GET['id'];//资讯ID
	        $where['id']=$_GET['id'];//基本信息ID
	        $whereNotice['authorID']=$_GET['id'];//通知ID
	    }
	    //我的通知
	    $notice = M('WeisiteMosqueNotice')->where($whereNotice)->order('update_time asc')->select();
	    $this->assign('notice',$notice);
	    //相关资讯
	   /* select top等于1的 order等于1
	    hot等于1的 order等于2，其他的 order等于3，然后外面套一个select，按照order排序*/
	  //  $result1=M('WeisiteMosqueArticle')->where($whereArt)->order('ishot desc asc')
	    $article = M('WeisiteMosqueArticle')->where($whereArt)->order('update_time asc')->select();
	    $this->assign('article',$article);
	    //基本信息
	    $model=M('WeisiteMosque');
	    $result=$model->where($where)->find();
	    $result['intro']=html_entity_decode($result['intro']);
	    $this->assign(detail,$result);
	    $this->_footer ();
	    // $backgroundimg=ONETHINK_ADDON_PATH.'WeiSite/View/default/TemplateIndex/'.$this->config['template_index'].'/icon.png';
	    if ($this->config ['show_background'] == 0) {
	        $this->config ['background'] = '';
	        $this->assign ( 'config', $this->config );
	    }
	    $this->display ();
	}
	//按照城市列出清真寺
	public function indexCityDetail($model = null){
			// 幻灯片
	    $map1 ['token'] = $map ['token'] = get_token ();
	    $map1 ['is_show'] = $map ['is_show'] = 1;
	    $map1 ['cate_id'] = $this->getSlideshowCategory('mosque');
			$slideshow = M ( 'weisite_slideshow' )->where ( $map1 )->order ( 'sort asc, id desc' )->select ();
			foreach ( $slideshow as &$vo ) {
				$vo ['img'] = get_cover_url ( $vo ['img'] );
			}
			$this->assign ( 'slideshow', $slideshow );
			// dump($slideshow);
			$model=M('WeisiteMosque');
			$info=$_GET['city'];
			$map['province']= $info;
			$map['status']="2";
			$mosqueRe=$model->where($map)->select();//提取清真寺
			$this->assign('mosque',$mosqueRe);
			$this->assign('city',$info);
			
			$this->_footer ();
			// $backgroundimg=ONETHINK_ADDON_PATH.'WeiSite/View/default/TemplateIndex/'.$this->config['template_index'].'/icon.png';
			if ($this->config ['show_background'] == 0) {
				$this->config ['background'] = '';
				$this->assign ( 'config', $this->config );
			}
			$this->display ();
	 
	}
	/* 删除通知 */
	public function indexDeleteNotice($model = null){
	    $public_info = get_token_appinfo ();
	    $url=addons_url ( 'WeiSite://WeiSite/index', array ('publicid' => $public_info ['id'] ) );
	    is_array ( $model ) || $model = $this->model;
	    $Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
	    if($this->isGet()){
	        $userId = session("mosqueId");//用户登录
	        if(!empty($userId)){
	            $a = M('MosqueNotice');
	            $id = $this->_get('id');
	            $result=$a->where("id=$id")->delete();//删除
	            if($result > 0){
	                $arr = array("del"=>'1');
	            }else{
	                $arr = array("del"=>'0');
	            }
	            $json_str = json_encode($arr);
	            echo $json_str;//返回给js
	        }
	    }
	   
	}
	/*生成二维码 */
	//引用地址http://www.xcsoft.cn/public/qrcode
	//text:需要生成二维码的数据，默认:http://www.xcsoft.cn
	//size:图片每个黑点的像素,默认4
	//level:纠错等级,默认L
	//padding:图片外围空白大小，默认2
	//logo:全地址，默认为空
	//完整引用地址:http://www.xcsoft.cn/public/qrcode?text=http://www.xcsoft.cn&size=4&level=L&padding=2&logo=http://www.xcsoft.cn/Public//images/success.png
	public function indexQrcode($name="123",$url='http://mp.weixin.qq.com/s?__biz=MzI2ODMwNzE5MA==&mid=100000108&idx=1&sn=b4b3a2b2dea06f219a9ea65deec6b7b7',$id='1',$size='4',$level='L',$padding=2,$logo=true){
	    if ($_GET['from']=="1"){
	        //刷新生成二维码
	        $name=cookie('mosqueName');
	        $id=cookie('mosqueId');
	        $url=addons_url ( 'WeiSite://Mosque/indexCenter', array ('publicid' => $public_info ['id'] ,'id'=>$id) );
	    }
	    
	    $name=I('get.name')?I('get.name'):$name;
	    $url=I('get.url')?I('get.url'):$url;
	    $id=I('get.id')?I('get.id'):$id;
	    $size=I('get.size')?I('get.size'):$size;
	    $level=I('get.level')?I('get.level'):$level;
	    $logo=I('get.logo')?I('get.logo'):$logo;
	    $padding=I('get.padding')?I('get.padding'):$padding;
	
	    $path='./Uploads/Qrcode/';//图片输出路径
	    mkdir($path);
	    $tempTime=time().rand(1,10000000);
	    $filename=$path.$tempTime.'qrcode.png';//生成文件名
	
	    $errorCorrectionLevel =intval($level) ;//容错级别
	    $matrixPointSize = intval($size);//生成图片大小
	
	    Vendor('phpqrcode.phpqrcode');
	    $object = new \QRcode();
	    ob_clean();//清除缓冲区
	    $result=$object->png($url,$filename, $errorCorrectionLevel, $matrixPointSize, 2);
	    //将二维码信息保存到数据库
	    $model=M('WeisiteMosque');
	    $where['id']=cookie('mosqueId');
	    $data['qrcode']=$filename;
	    $saveQrcodeRe=$model->where($where)->save($data);
	    $this->assign('filename',$filename);
	    $this->assign('name',$name);
	    $this->display();
	}
}