<?php

namespace Addons\HumanTranslation;
use Common\Controller\Addon;

/**
 * 人工翻译插件
 * @author 晓成
 */

    class HumanTranslationAddon extends Addon{

        public $info = array(
            'name'=>'HumanTranslation',
            'title'=>'人工翻译',
            'description'=>'人工翻译插件',
            'status'=>1,
            'author'=>'晓成',
            'version'=>'0.1',
            'has_adminlist'=>0
        );

	public function install() {
		$install_sql = './Addons/HumanTranslation/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/HumanTranslation/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }