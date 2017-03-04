<?php
/**
*
* 版权所有：亿次元科技<www.eciyuan.net>
* 作    者：马晓成<857773627@qq.com>
* 日    期：2016-04-21
* 版    本：1.0.0
* 功能说明：前台文章控制器。
*
**/
namespace Addons\WeiSite\Controller;
use Addons\WeiSite\Controller\BaseController;
class LifeController extends BaseController {
    var $model;
    function _initialize() {
        parent::_initialize ();
        // add_credit ( 'weisite', 86400 );
        if (file_exists ( ONETHINK_ADDON_PATH . 'WeiSite/View/default/pigcms/Index_' . $this->config ['template_index'] . '.html' )) {
            $this->pigcms_index ();
            $this->display ( ONETHINK_ADDON_PATH . 'WeiSite/View/default/pigcms/Index_' . $this->config ['template_index'] . '.html' );
        } else {
            $map1 ['token'] =$map2 ['token']= $map ['token'] = get_token () ;
            $map1 ['is_show'] = $map ['is_show'] = 1;
            $map1 ['cate_id'] = $this->getSlideshowCategory('life');
            $map ['pid'] = 0; // 获取一级分类
             
            // dump($category);
            // 幻灯片
            $slideshow = M ( 'weisite_slideshow' )->where ( $map1 )->order ( 'sort asc, id desc' )->select ();
            foreach ( $slideshow as &$vo ) {
                $vo ['img'] = get_cover_url ( $vo ['img'] );
            }
            $this->assign ( 'slideshow', $slideshow );
             
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
        }
    }
    //首页资讯详细
    public function index(){
        $where['status']="2"; 
        $where['s_province']=array('NEQ','省份');   //排除默认
        $parentFood=M('WeisiteFood')->where($where)->field("s_province")->distinct(true)->select();//提取城市
        $this->assign('FoodCity',$parentFood);
        
        $parentClothes=M('WeisiteClothes')->where($where)->field("s_province")->distinct(true)->select();//提取城市
        $this->assign('ClothesCity',$parentClothes);
   
        $parentHousing=M('WeisiteHousing')->where($where)->field("s_province")->distinct(true)->select();//提取城市
        $this->assign('HousingCity',$parentHousing);
        
        $parentCarry=M('WeisiteCarry')->where($where)->field("s_province")->distinct(true)->select();//提取城市
        $this->assign('CarryCity',$parentCarry);
        
        /* 查询详细资讯条件 */
      //  $map['s_province']=array('like',"%$info%");
        $map['status']="2";
        
        //提服装
        $resultClothes=M('WeisiteClothes')->where($map)->order('create_time desc')->limit(6)->select();
        $this->assign('clothes',$resultClothes);
        //美食
        $resultFood=M('WeisiteFood')->where($map)->order('create_time desc')->limit(6)->select();
        $this->assign('food',$resultFood);
        //房产
        $resultHousing=M('WeisiteHousing')->where($map)->order('create_time desc')->limit(6)->select();
        $this->assign('housing',$resultHousing);
        //行
        $resultCarry=M('WeisiteCarry')->where($map)->order('create_time desc')->limit(6)->select();
        $this->assign('carry',$resultCarry);
        
		$this -> display();
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
    
	//单页
    public function indexDetail(){
		$where1['id'] = $_GET['id'];
		$model=ucfirst($_GET['type']);
		$resultArt= M($model)->where($where1)->find();
		//上一篇
		$front=M($model)->where("id<".$_GET['id'])->order('id desc')->find();
		//下一篇
		$after=M($model)->where("id>".$_GET['id'])->order('id desc')->find();
		$this->assign('artDetail',$resultArt);
		$this->assign('front',$front);//上一条
		$this->assign('after',$after);//下一条
		
		$this->display();
    }
	//h5  生活种类过来  提取每个种类下面的资讯列表
    public function indexCateDetail(){
        if ($_GET['type']=="housing"){
            $m = M('WeisiteHousing');
            $cateModel=M('HousingCategory');
            //  $wheretype['tag']="housing";
            $title="房产";
            $type="housing";
            $titleIcon="fa-building";
        }elseif ($_GET['type']=="food"){
            $m = M('WeisiteFood');
            $cateModel=M('FoodCategory');
            $titleIcon="fa-inbox";
            $title="美食";
            $type="food";
        }elseif ($_GET['type']=="carry"){
            $m = M('WeisiteCarry');
            $cateModel=M('CarryCategory');
            $title="旅行";
            $type="carry";
            $titleIcon="fa-car";
        }elseif ($_GET['type']=="clothes"){
            $m = M('WeisiteClothes');
              $cateModel=M('ClothesCategory');
            $title="服饰";
            $type="clothes";
            $titleIcon="fa-sitemap";
        }else {
            $this->error('无法判别参数！');
        }
        $where['sid']=$_GET['id'];
		$count      = $m->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		$list  = $m->where($where)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);	
		$this->assign('cateName',$this->getCategoryName($_GET['id'],$cateModel,1));	
		$this->assign('title',$title);	
		$this->assign('type',$type);	
		$this->assign('titleIcon',$titleIcon);	
		$this->assign('page',$show);// 赋值分页输出
		$this -> display();
    }
	//列表
    public function more(){
        if ($_GET['type']=="housing"){
            $m = M('WeisiteHousing');
          //  $wheretype['tag']="housing";
            $title="房地产";
            $type="housing";
            $titleIcon="fa-building";
        }elseif ($_GET['type']=="food"){
            $m = M('WeisiteFood');
           // $wheretype['tag']="food";
            $titleIcon="fa-inbox";
            $title="美食";
            $type="food";
        }elseif ($_GET['type']=="carry"){
            $m = M('WeisiteCarry');
          //  $wheretype['tag']="carry";
            $title="旅行";
            $type="carry";
            $titleIcon="fa-car";
        }else{
            $m = M('WeisiteClothes');
           // $wheretype['tag']="clothes";
            $title="服饰";
            $type="clothes";
            $titleIcon="fa-sitemap";
        }
		$count      = $m->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$show       = $Page->show();// 分页显示输出
		$list  = $m->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);	
		$this->assign('title',$title);	
		$this->assign('type',$type);	
		$this->assign('titleIcon',$titleIcon);	
		$this->assign('page',$show);// 赋值分页输出
		$this -> display();
    }
    //搜索部分
    public function indexSearch(){
       
      $p = isset($_GET['p'])?intval($_GET['p']):'1';
		$field = isset($_GET['field'])?$_GET['field']:'';
		$keyword = isset($_GET['keyword'])?htmlentities($_GET['keyword']):'';
		$order = isset($_GET['order'])?$_GET['order']:'DESC';
		$where = '';
		
		$prefix = C('DB_PREFIX');
		if($order == 'asc'){
			$order = "{$prefix}member.t asc";
		}elseif(($order == 'desc')){
			$order = "{$prefix}member.t desc";
		}else{
			$order = "{$prefix}member.uid asc";
		}
		if($keyword <>''){
			if($field=='user'){
				$where = "{$prefix}member.user LIKE '%$keyword%'";
			}
			if($field=='phone'){
				$where = "{$prefix}member.phone LIKE '%$keyword%'";
			}
			if($field=='qq'){
				$where = "{$prefix}member.qq LIKE '%$keyword%'";
			}
			if($field=='email'){
				$where = "{$prefix}member.email LIKE '%$keyword%'";
			}
		}
		
		
		$user = M('member');
		$pagesize = 10;#每页数量
		$offset = $pagesize*($p-1);//计算记录偏移量
		$count = $user->count();
		
		$list  = $user->field("{$prefix}member.*,{$prefix}auth_group.id as gid,{$prefix}auth_group.title")->order($order)->join("{$prefix}auth_group_access ON {$prefix}member.uid = {$prefix}auth_group_access.uid")->join("{$prefix}auth_group ON {$prefix}auth_group.id = {$prefix}auth_group_access.group_id")->where($where)->limit($offset.','.$pagesize)->select();
		
		
		//$user->getLastSql();
		$page	=	new \Think\Page($count,$pagesize); 
		$page = $page->show();
        $this->assign('list',$list);	
        $this->assign('page',$page);
		$group = M('auth_group')->field('id,title')->select();
		$this->assign('group',$group);
		$this -> display();
    }
    //按照城市列出的资讯
    public function indexCityDetail(){
        $info = $_GET['city'];
    	$whereCity['status']="2";
    	$whereCity['s_province']=array('like',"%$info%");
    	if ($_GET['from']!=="h5") {
    		 //从微站上过来的 提取每个模块下的资讯
	    	if ($_GET['type']=="housing"){
	            $model = M('WeisiteHousing');
	            $title="房地产";
	            $type="housing";
	            $titleIcon="fa-building";
	        }elseif ($_GET['type']=="food"){
	            $model = M('WeisiteFood');
	            // $wheretype['tag']="food";
	            $titleIcon="fa-inbox";
	            $type="food";
	            $title="美食";
	        }elseif ($_GET['type']=="carry"){
	            $model = M('WeisiteCarry');
	            //  $wheretype['tag']="carry";
	            $title="旅行";
	            $type="carry";
	            $titleIcon="fa-car";
	        }else{
	            $model = M('WeisiteClothes');
	            // $wheretype['tag']="clothes";
	            $title="服饰";
	            $type="clothes";
	            $titleIcon="fa-sitemap";
	        }
	        $count      = $model->where($whereCity)->count();// 查询满足要求的总记录数
	        $Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
	        $show       = $Page->show();// 分页显示输出
	        $re=$model->where($whereCity)->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();//提取
	        $this->assign('detail',$re);
	        $this->assign('city',$_GET['city']);
	        $this->assign('titleIcon',$titleIcon);
	        $this->assign('type',$type);
	        $this->assign('modelTitle',$title);
	        $this->assign('page',$show);// 赋值分页输出
	        
	        $this -> display();
    	}else{
    		//从H5过来 提取全部的资讯
    		//提服装
    		$resultClothes=M('WeisiteClothes')->where($whereCity)->order('create_time desc')->limit(6)->select();
    		$this->assign('clothes',$resultClothes);
    		//美食
    		$resultFood=M('WeisiteFood')->where($whereCity)->order('create_time desc')->limit(6)->select();
    		$this->assign('food',$resultFood);
    		//房产
    		$resultHousing=M('WeisiteHousing')->where($whereCity)->order('create_time desc')->limit(6)->select();
    		$this->assign('housing',$resultHousing);
    		//行
    		$resultCarry=M('WeisiteCarry')->where($whereCity)->order('create_time desc')->limit(6)->select();
    		$this->assign('carry',$resultCarry);
    		$this->assign('city',$_GET['city']);
    		
    		
    		$this -> display('singelCityDetail');
    	}
     
    }	 
}