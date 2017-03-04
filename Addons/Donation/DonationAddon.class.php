<?php

namespace Addons\Donation;
use Common\Controller\Addon;

/**
 * 伊媒公益插件
 * @author 马晓成
 */

    class DonationAddon extends Addon{

        public $info = array(
            'name'=>'Donation',
            'title'=>'伊媒公益',
            'description'=>'伊媒公益',
            'status'=>1,
            'author'=>'马晓成',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/Donation/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Donation/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }