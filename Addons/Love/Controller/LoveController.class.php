<?php

namespace Addons\Love\Controller;
use Home\Controller\AddonsController;

class LoveController extends AddonsController{
    //2014-7-31更新  用户点赞
    public function favourAjax(){
        $uid = I('uid');
        if($uid == ''){
            $this->error('您需要关注官方公众号才能进入');
        }
        $id = I('tid');
    
        $info = M('love')->field('favourid')->where("id = $id")->find();
    
        if($info['favourid'] == ''){
    
            $data['favourid'] = I('uid');
            $boo = M('love')->where("id = $id")->setField($data);
            	
    
        }else{
    
            $favourid = explode(',',$info['favourid']);
            if(in_array(I('uid'),$favourid)){
                	
                unset($favourid[array_search(I('uid'),$favourid)]);
                $data['favourid'] = implode(',',$favourid);
                $boo = M('love')->where("id = $id")->setField($data);
    
            }else{
                	
                $data['favourid'] = $info['favourid'].','.I('uid');
                $boo = M('love')->where("id = $id")->setField($data);
    
    
            }
        }
    
        if($boo){
            $jump_url=addons_url('Love://Love/show');
            redirect($jump_url);
        }else{
            echo 0;
        }
    }
    //2014-7-30更新  查看评论页面
    function showComment(){
        $map['id']=I('id');
        if(IS_POST){
            $param['id']=$data['loveId']=I('loveId');
            $data['content']=I('content');
            $data['cTime']=time();
            $data['token']=get_token();
            $data['uid']=get_mid();
            $res=M('love_comment')->add($data);
            $jump_url=addons_url('Love://Love/showComment',$param);
            redirect($jump_url);
        }
        $comment_map['loveId']=I('id');
        $comment_info=M('love_comment')->where($comment_map)->order('id desc')->select();
        $this->assign('comment_info',$comment_info);
    
        $info=M('love')->where($map)->find();
        //		$this->assign('oo',I('id').'kk');
        $this->assign('info',$info);
        $this->display();
    }
    
    //2014-7-30更新  前台3.0版本  show.html
    function show(){
        $param['token']=$map['token']=get_token();
        $param['openid']=$map['openid']=get_openid();
    
        if(IS_POST){
            //如果是发布表白
            if(I('post.type')=="say"){
                $nickname=I('nickname');
                if(!empty($nickname)){
                    $data['nickname']=$nickname;
                }else{
                    $data['nickname']='匿名';
                }
    
                $lover=I('lover');
                if(!empty($lover)){
                    $data['lover']=$lover;
                }else{
                    $data['lover']='我喜欢的人';
                }
    
                $data['content']=I('content')==''?'我们在一起吧':I('content');
    
                $data['phone']=I('phone');
                $data['cTime']=time();
                $data['token']=get_token();
    
    
                $rel=M('love')->add($data);
    
                if($rel){
                    $jump_url=addons_url('Love://Love/show');
                    redirect($jump_url);
                }else{
                    $this->error('表白失败');
                }
            }
            //如果是搜索表白
            $keyword=I('post.keyword');
            $where['lover']=array('like','%'.$keyword.'%');
            $where['content']=array('like','%'.$keyword.'%');
            $where['phone']=array('eq',$keyword);
            $where['_logic']='or';
            $map['_complex']=$where;
            	
        }
    
    
        $user=get_followinfo($this->mid);
        $this->assign('user',$user);
    
        $model=$this->getModel('love');
    
        $count=M('love')->where($map)->count();
        //显示表白总数
        if(!empty($count)){
            $sum_arr = array();
            (int)$number = $count;
            for($i=strlen($number),$j=0;$i>0;$i--){
                	
                $sum_arr[] = substr($number,$j,1);
                $j++;
            }
            $this->assign('sum_arr',$sum_arr);
        }
    
    
        $this->assign('count',$count);
        $page=new\Think\Page($count,6);
        $show=$page->show();
        $data=M('love')->where($map)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
    
        //统计评论总数
        foreach($data as $k=>$v){
            $id=$v['id'];
            $comment=M('love_comment')->field('id')->where('loveId='.$id)->select();
            $data[$k]["cnum"]=count($comment);
    
        }
    
        $list_data ['list_data'] = $data;
    
        // 分页
    
    
        $next_page=addons_url('Love://Love/showLove');
        $jump_url=addons_url('Love://Love/showComment',$param);
    
    
        $wecha_id=get_mid();
        $this->assign('wecha_id',$wecha_id);
    
        $this->assign('page',$show);
        $this->assign('jump_url',$jump_url);
        $this->assign ( $list_data );
        $this->display();
    }
    
    
    function sayLove(){
        $map['id']=$data['uid']=$this->mid;
        $user=get_followinfo($this->mid);
        $this->assign('user',$user);
    
        if(IS_POST){
            $nickname=I('nickname');
            if(!empty($nickname)){
                $data['nickname']=$nickname;
            }else{
                $data['nickname']='匿名';
            }
            	
            $lover=I('lover');
            if(!empty($lover)){
                $data['lover']=$lover;
            }else{
                $data['lover']='我喜欢的人';
            }
            	
            $data['content']=I('content')==''?'我们在一起吧':I('content');
            	
            $data['phone']=I('phone');
            $data['cTime']=time();
            $data['token']=get_token();
            	
            $rel=M('love')->add($data);
            	
            if($rel){
                $jump_url=addons_url('Love://Love/showLove');
                redirect($jump_url);
            }else{
                $this->error('表白失败');
            }
        }else{
            $this->assign('index',addons_url('Love://Love/t_show'),$param);
            $this->display();
        }
    }
    
    function showLove(){
        $param['token']=$map['token']=get_token();
        $param['openid']=$map['openid']=get_openid();
    
        if(IS_POST){
            $keyword=I('post.keyword');
            $map['lover']=array('like','%'.$keyword.'%');
            	
        }
    
        $model=$this->getModel('love');
        $count=M('love')->where($map)->count();
        $page=new\Think\Page($count,3);
        $show=$page->show();
        $data=M('love')->where($map)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
    
    
        $list_data ['list_data'] = $data;
    
        // 分页
    
    
        $next_page=addons_url('Love://Love/showLove');
        $jump_url=addons_url('Love://Love/sayLove',$param);
    
        $this->assign('page',$show);
        $this->assign('jump_url',$jump_url);
        $this->assign ( $list_data );
        $this->display();
    }
    
    
    function t_show(){
        $param['token']=$map['token']=get_token();
        $param['openid']=$map['openid']=get_openid();
    
        if(IS_POST){
            $keyword=I('post.keyword');
            $map['lover']=array('like','%'.$keyword.'%');
            	
        }
    
        $model=$this->getModel('love');
        $count=M('love')->where($map)->count();
        $page=new\Think\Page($count,3);
        $show=$page->show();
        $data=M('love')->where($map)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
    
    
        $list_data ['list_data'] = $data;
    
        // 分页
    
    
        $next_page=addons_url('Love://Love/t_show');
        $jump_url=addons_url('Love://Love/t_say',$param);
    
        $this->assign('page',$show);
        $this->assign('jump_url',$jump_url);
        $this->assign ( $list_data );
        $this->display();
    }
    
    function t_say(){
        $map['id']=$data['uid']=$this->mid;
        $user=get_followinfo($this->mid);
        $this->assign('user',$user);
    
        if(IS_POST){
            $nickname=I('nickname');
            if(!empty($nickname)){
                $data['nickname']=$nickname;
            }else{
                $data['nickname']='匿名';
            }
            	
            $lover=I('lover');
            if(!empty($lover)){
                $data['lover']=$lover;
            }else{
                $data['lover']='我喜欢的人';
            }
            	
            $data['content']=I('content')==''?'我们在一起吧':I('content');
            	
            $data['phone']=I('phone');
            $data['cTime']=time();
            $data['token']=get_token();
            	
            $rel=M('love')->add($data);
            	
            if($rel){
                $jump_url=addons_url('Love://Love/t_show');
                redirect($jump_url);
            }else{
                $this->error('表白失败');
            }
        }else{
            $this->assign('index',addons_url('Love://Love/t_show'),$param);
            $this->display();
        }
    }
}
