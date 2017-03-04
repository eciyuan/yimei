<?php

namespace Addons\Love;
use Common\Controller\Addon;

/**
 * 表白墙插件
 * @author 艾逗笔
 */

    class LoveAddon extends Addon{

        public $info = array(
            'name'=>'Love',
            'title'=>'表白墙',
            'description'=>'微信表白墙插件',
            'status'=>1,
            'author'=>'艾逗笔',
            'version'=>'0.1',
            'has_adminlist'=>1,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/Love/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Love/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }