<?php

namespace Addons\Company;
use Common\Controller\Addon;

/**
 * 第三方入驻管理插件
 * @author 马晓成
 */

    class CompanyAddon extends Addon{

        public $info = array(
            'name'=>'Company',
            'title'=>'第三方入驻管理',
            'description'=>'这是一个企业入驻管理',
            'status'=>1,
            'author'=>'马晓成',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/Company/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Company/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }