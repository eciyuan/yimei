<?php

namespace Addons\Donation\Controller;

use Addons\Donation\Controller\BaseController;

class WapController extends BaseController {
    public $model;
    public $modelCategory;
    public $slideModel;
 
    function _initialize() {
        $this->slideModel=M('weisite_slideshow');
         $this->model=M('Donation');
         $this->modelCategory=M('DonationCategory');
         $this->modelMoney=M('Moneygo');
        
       // $this->model = $this->getModel ( 'Donation_category' );
       // parent::_initialize ();
    }
	// 首页
	public function index() {
		$token = get_token ();
		$model =$this->model;
		$slideshow =$this->slideModel;
		$modelCategory =$this->modelCategory;
			$mapSlideshow1 ['is_show']  = $mapSlideshow ['is_show'] =1;
			$mapSlideshow ['cate_id'] = $this->getSlideshowCategory('donation');
			// 幻灯片
			$slideshowRe=$slideshow->where ( $mapSlideshow1 )->order ( 'sort asc, id desc' )->select ();
			foreach ( $slideshowRe as &$vo ) {
				$vo ['img'] = get_cover_url ( $vo ['img'] );
			}
			$this->assign ( 'slideshow', $slideshowRe );
			//dump($slideshowRe);
			//项目类别
			$categoryRe=$modelCategory->where ( $mapSlideshow )->order ( 'sort asc, id desc' )->select ();
			foreach ( $categoryRe as &$vo ) {
			    $vo ['thumbnail'] = get_cover_url ( $vo ['thumbnail'] );
			    /* //项目类别关注人数
			     * $whereClick['cateid']=$vo['id'];
			    $modelClick=$model->where ( $whereClick )->field('click')->find ();
			    foreach ( $modelClick as &$val ) {
			        $val['click']+=  $val['click'];
			    } */
			}
			//dump($categoryRe);exit();
			$this->assign ( 'categoryRe', $categoryRe );
			//项目信息
			$modelRe=$model->where ( $mapSlideshow )->order ( 'ctime asc, id desc' )->limit(20)->select ();
			foreach ( $modelRe as &$vo ) {
			    $vo ['thumbnail'] = get_cover_url ( $vo ['thumbnail'] );
			    $vo['count_money']=  $this->divisionToPercent($vo['donation_money'],$vo['summoney']);//进度条
			}
			$this->assign ( 'modelRe', $modelRe );
		$this->display ();
	}
	//public function 
	public function listCategory($id) {
		 if (!empty($id)){
		     $token = $map ['token'] = get_token ();
		     //项目信息
		     $modelWhere['cateid']=$_GET['id'];
		     $model =$this->model;
		     $modelRe=$model->where ( $modelWhere )->order ( 'ctime asc, id desc' )->select ();
		     foreach ( $modelRe as &$vo ) {
		         $vo ['thumbnail'] = get_cover_url ( $vo ['thumbnail'] );
		         $vo['count_money']=  $this->divisionToPercent($vo['donation_money'],$vo['summoney']);
		     }
		     $this->assign ( 'modelRe', $modelRe );
		     //dump($modelRe);
		     $this->display ();
		 }else {
		     $this->error('参数错误！');
		 }
	
	}
	public function detail($id) {
		 if (!empty($id)){
		     //$token = $map ['token'] = get_token ();
		     //项目信息
		     $id = I('id', '', 'intval');
		     $modelWhere['id']=$id;
		     $model =$this->model;
		     $modelRe=$model->where ( $modelWhere )->find();
		     
		     $mapSlideshow ['is_show']  = 1;
		     // 幻灯片
		     $slideshowRe=$model->where ( $modelWhere )->field('photo')->find ();
		     foreach ( $slideshowRe as &$vo ) {
		         $vo ['photo'] = get_cover_url ( $vo ['photo'] );
		     }
		   
		     //善款去向
		     $modelMoney =$this->modelMoney;
		     $whereMoney['cateid']=$id;
		     $modelMoneyRe=$model->where ( $modelWhere )->select ();
		     
		     $this->click($id,$model);
		     $this->assign ( 'moneygo', $modelMoneyRe );
		     $this->assign ( 'slideshow', $slideshowRe );
		     $this->assign ( 'modelRe', $modelRe );
		     $this->display ();
		 }else {
		     $this->error('参数错误！');
		 }
	
	}
	// 合计收到的红包数目
	public function sumLove() {
	    $model =$this->model;
	   /*  $modelRe=$model->field('count_love')->select ();
	   // dump($modelRe);exit();
	    foreach ( $modelRe as &$dates) {
	        $dates['count_love']+=  $dates['count_love'];
	    } */
	    $modelRe=$model->field('click')->select ();
	    // dump($modelRe);exit();
	    foreach ( $modelRe as &$dates) {
	        $dates['click']+=  $dates['click'];
	    }
	    echo json_encode ( $dates['click'] );
	}
	public function search() {
		 if (!empty($id)){
		     //$token = $map ['token'] = get_token ();
		     //项目信息
		     $modelWhere['id']=$_GET['id'];
		     $model =$this->model;
		     $modelRe=$model->where ( $modelWhere )->find();
		     $this->assign ( 'modelRe', $modelRe );
		     $this->display ();
		 }else {
		     $this->error('参数错误！');
		 }
	
	}
/*  
 * 捐款操作
 * 
 * */
	public function  donationMoney(){
	    $this->display();
	}
/*  
 * 邀请大家
 * 一起捐款
 * 
 * */
	public function  publicDonation(){
	    $this->display();
	}
	/**
	 * 增加项目
	 * 马晓成
	 */
public function add($model = null) {
		is_array ( $model ) || $model = $this->model;
		if (IS_POST) {
			// 获取模型的字段信息
			if (empty($_POST['agreement'])){
			   $this->error ('必须同意本平台的协议方可发布信息！');
			}
			$model->uid = session('menmberId');
			$model->ctime = time();
			$model->token = get_token ();  
			$model->create ();
			dump($model->create ());
			    if ($model->add ()){
			        redirect ( addons_url ( 'Donation://Wap/index' ) );
			    }else {
				$this->error ( $model->getError () );
			} 
		} else {
			// 要先填写appid
			if ($_GET['from']=="1"){
			    //首页第二个 显示cateid
			    $this->assign ( 'cateid', $_GET['id'] );
			}else {
			    //首页第一个
			    $map ['token'] = get_token ();
			    $modelCategory =$this->modelCategory;
			    $mapSlideshow1 ['is_show']  = $mapSlideshow ['is_show'] =1;
			    //项目类别
			    $categoryRe=$modelCategory->where ( $mapSlideshow1 )->order ( 'sort asc, id desc' )->field('id,name,intro')->select ();
			    $this->assign ( 'categoryRe', $categoryRe );
			}
			$this->display ();
		
		}
	}
	
	
	public function profile() {
		$uid = intval ( $_GET ['uid'] ) ? intval ( $_GET ['uid'] ) : $this->mid;
		// 判断隐私设置
		// $userPrivacy = $this->privacy ( $uid );
		// $isAllowed = 0;
		// $isMessage = 1;
		// ($userPrivacy ['space'] == 1) && $isMessage = 0;
		// $this->assign ( 'sendmsg', $isMessage );
		
		// if ($userPrivacy === true || $userPrivacy ['space'] == 0) {
		// $isAllowed = 1;
		// }
		// $this->assign ( 'isAllowed', $isAllowed );
		$this->assign ( 'uid', $uid );
		// 获取我的个人信息
		// $user = getUserInfo($uid);
		$data ['user_id'] = $uid;
		$data ['page'] = 1;
		$profile = getUserInfo ( $uid );
		// dump($profile);exit;
		// if(!$profile['uname']){
		// redirect(U('w3g/Public/home'), 3, '参数错误');
		// }
		$this->assign ( 'profile', $profile );
		if ($this->mid == $this->uid) {
			$this->assign ( 'datatitle', '用户资料' );
		} else {
			$this->assign ( 'datatitle', '用户资料' );
		}
		// 他的帖子
		$weiba_arr = getSubByKey ( D ( 'weiba' )->where ( 'is_del=0 and status=1' )->field ( 'id' )->select (), 'id' ); // 未删除且通过审核的版块
		$map ['weiba_id'] = array (
				'in',
				$weiba_arr 
		);
		$map ['is_del'] = 0;
		$map ['post_uid'] = $uid;
		$post_list = D ( 'weiba_post' )->where ( $map )->order ( 'post_time desc' )->selectPage ( 20 );
		$weiba_ids = getSubByKey ( $post_list ['data'], 'weiba_id' );
		$nameArr = $this->_getWeibaName ( $weiba_ids );
		foreach ( $post_list ['list_data'] as $k => $v ) {
			$post_list ['list_data'] [$k] ['weiba'] = $nameArr [$v ['weiba_id']];
			$post_list ['list_data'] [$k] ['user'] = getUserInfo ( $v ['post_uid'] );
			$post_list ['list_data'] [$k] ['replyuser'] = getUserInfo ( $v ['last_reply_uid'] );
			$images = matchImages ( $v ['content'] );
			if ($images) {
				foreach ( $images as $img ) {
					$imgInfo = getThumbImage ( $img, 200, 200, true, false, true );
					if (! strpos ( $imgInfo ['dirname'], 'um/dialogs/emotion' ) && $imgInfo ['width'] > 50) {
						$post_list ['list_data'] [$k] ['image'] [] = $imgInfo ['is_http'] ? $imgInfo ['src'] : UPLOAD_URL . $imgInfo ['src'];
					}
				}
			}
			if ($v ['tag_id']) {
				$tagInfo = D ( 'weiba_tag' )->find ( $v ['tag_id'] );
				$post_list ['list_data'] [$k] ['tag_name'] = $tagInfo ['name'];
			}
		}
		// 查找赞数
		$map2 ['uid'] = $uid;
		$map2 ['is_del'] = 0;
		$praiseCount = D ( 'weiba_post', 'weiba' )->where ( $map )->sum ( 'praise' );
		$praiseCommentCount = D ( 'weiba_reply', 'weiba' )->where ( $map2 )->sum ( 'digg_count' );
		$this->assign ( 'praiseCount', $praiseCount + $praiseCommentCount );
		
		$this->assign ( 'post_list', $post_list );
		
		$this->display ();
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
			
}