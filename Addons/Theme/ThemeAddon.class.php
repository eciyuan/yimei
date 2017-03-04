<?php

namespace Addons\Theme;
use Common\Controller\Addon;

/**
 * 伊媒之约插件
 * @author 马晓成
 */

    class ThemeAddon extends Addon{

        public $info = array(
            'name'=>'Theme',
            'title'=>'伊媒之约',
            'description'=>'伊媒之约的管理方式',
            'status'=>1,
            'author'=>'马晓成',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/Theme/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Theme/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }