<?php
/**
*
* 版权所有：亿次元科技<www.eciyuan.net>
* 作    者：马晓成<857773627@qq.com>
* 日    期：2016-04-20
* 版    本：1.0.0
* 功能说明：产品功能控制器。
*
**/

namespace Mobile\Controller;
use Mobile\Controller\ComController;
use Vendor\Tree;

class FeedbackController extends ComController {

//新增
	public function add(){
	if ($_POST['type']=="1"){
	    $data['content'] = I('post.content','','strip_tags');
	    $data['name']=$_POST['name'];
	    $data['email']=$_POST['email'];
	    $data['create_time']=time;
	    if($data['content']==''){
	        $this->error('反馈内容不能为空！');
	    }
	    $result=M('Feedback')->add($data);
	    if($result){
	        $this->success('感谢您的反馈！,您可以浏览其他的信息！',U('Life/index'));
	    }else{
	        $this->error('系统错误，请稍后再试！');
	    }
	}else{
	    $this -> display();
	}
			
	}
		
	public function index(){
		$Feedback = M('Feedback');
		$pagesize = 20;#每页数量
		$count = $Feedback->count();
		$list  = $Feedback->order("t desc")->select();
		$page	=	new \Think\Page($count,$pagesize); 
		$page = $page->show();
        $this->assign('list',$list);	
        $this->assign('page',$page);
		$this -> display();
	}
	
	
}