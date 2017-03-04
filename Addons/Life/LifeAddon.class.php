<?php

namespace Addons\Life;
use Common\Controller\Addon;

/**
 * 伊媒生活插件
 * @author 马晓成
 */

    class LifeAddon extends Addon{

        public $info = array(
            'name'=>'Life',
            'title'=>'伊媒生活',
            'description'=>'这是伊媒生活管理',
            'status'=>1,
            'author'=>'马晓成',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/Life/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Life/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }