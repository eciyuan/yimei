<?php
/**
*
* 版权所有：亿次元科技(www.eciyuan.net)
* 作    者：马晓成(ma.running@foxmail.com)
* 日    期：2016-06-20
* 版    本：1.0.0
* 功能说明：文章控制器。
*
**/

namespace Mobile\Controller;
use Mobile\Controller\ComController;
use Vendor\Tree;

class LoveDetailController extends ComController {

	public function index(){
		$this -> display();
	}
	
	public function edit($id){
		
		$id = intval($id);
		$article = M('LoveDetail')->where('id='.$id)->find();
		if($article){
			$this->assign('member',$article);
		}else{
			$this->error('参数错误！');
		}
		$this -> display();
	}
	
	public function detail($id){
		
		$id = intval($id);
		$article = M('LoveDetail')->where('id='.$id)->find();
		if($article){
			$this->assign('detail',$article);
		}else{
			$this->error('参数错误！');
		}
		$this -> display();
	}
	public function label($id){
		  $m = M('LoveDetail');
	    $lovewhere['status']="2";
	    $lovewhere['label_id']=$id;
	    $field=array('name,id,head');
	    $count      = $m->where($lovewhere)->count();// 查询满足要求的总记录数
	    $Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
	    $show       = $Page->show();// 分页显示输出
	    $loveyard =$m->where($lovewhere)->field($field)->order('create_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
	    $this->assign('love',$loveyard);
	    //标签展示（置顶的永远显示）
	    $this->assign('label',M('Label')->where('status=1')->order('sort DESC')->select());
	    $this->assign('page',$show);// 赋值分页输出
	    $this -> display('more');
	}
	
	public function update($id=0){
		
		$id = intval($id);
		$data=D('LoveDetail')->create();
		if ($data){
		    $head = I('post.head','','strip_tags');
		    if($head<>'') {
		        $data['head'] = $head;
		    }
		    $photo= I('post.photo','','strip_tags');
		    if ($photo=="100"){
		    
		    }else{
		        if($photo<>'') {
		            $data['photo'] = $photo;
		        }
		    }
		    if($id){
		        M('LoveDetail')->data($data)->where('id='.$id)->save();
		        $this->success('恭喜！编辑成功！');
		    }else{
		        $id = M('LoveDetail')->data($data)->add();
		        if($id){
		            $this->success('恭喜！您的信息已经提交了，请耐心等候！',C(WEIXIN_LUNTAN));
		        }else{
		            $this->error('抱歉，未知错误！');
		        }
		        	
		    }
		}else {
		    $this->error(D('LoveDetail')->getError());
		}
	}
	//列表
	public function more(){
	    $m = M('LoveDetail');
	    $lovewhere['status']="2";
	    $field=array('name,id,head');
	    $count      = $m->where($lovewhere)->count();// 查询满足要求的总记录数
	    $Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
	    $show       = $Page->show();// 分页显示输出
	    $loveyard =$m->where($lovewhere)->field($field)->order('create_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
	    $this->assign('love',$loveyard);
	    //标签展示（置顶的永远显示）
	    $this->assign('label',M('Label')->where('status=1')->order('sort DESC')->select());
	    $this->assign('page',$show);// 赋值分页输出
	    $this -> display();
	}
}