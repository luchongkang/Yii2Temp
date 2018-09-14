<?php 
namespace backend\components;

use yii;
use backend\models\TRole;
use backend\models\TMenu;
use common\Common;
use yii\base\Widget;
use yii\helpers\Html;
/**
* 
*/
class MenuWidget extends Widget
{
	// public $msg;
	// public function init(){
	// 	parent::init();
	// }
	public function run(){
		$session = Yii::$app->session;
		$cache = Yii::$app->cacheFile;
		$rid = $session->get('admin_roleId');
		$menuKey = 'Menu_'.$rid;
		$menu = $cache->get($menuKey);
		if($menu){
			return $menu;//菜单缓存
		}
		$li = "";
		$parent = TMenu::find()->where(['f_parentId'=>0])->all();//父级菜单
		if($rid == 0){
			//超级管理员显示所有的菜单
			foreach ($parent as $v) {
				$li .= '<li><a><i class="fa '.$v->f_icon.'"></i>'.$v->f_title.'<span class="fa fa-chevron-down"></span></a>';
				$child = TMenu::findAll(['f_parentId'=>$v->f_id]);//子级菜单
				$li .= '<ul class="nav child_menu">';
				foreach ($child as $c) {
					$li .= '<li><a href="'.$c->f_url.'">'.$c->f_title.'</a></li>';
				}
				$li .= '</ul></li>';
			}
		}else{
			//角色管理员的菜单
			$authCache = \backend\AdminBase::GetAuthByRoleId($rid);
			$auth = explode(',', $authCache);
			foreach ($parent as $v) {
				$child = TMenu::findAll(['f_parentId'=>$v->f_id]);//子级菜单
				if(in_array($v->f_id, $auth)){
					//如果存在主目录，输出全部目录
					$li .= '<li><a><i class="fa '.$v->f_icon.'"></i>'.$v->f_title.'<span class="fa fa-chevron-down"></span></a>';
					$li .= '<ul class="nav child_menu">';
					foreach ($child as $c) {
						$li .= '<li><a href="'.$c->f_url.'">'.$c->f_title.'</a></li>';
					}
					$li .= '</ul></li>';
				}else{
					$childIds = common::GetTableColumn($child,'f_id');
					$childAuth = array_intersect($childIds, $auth);//取子菜单和所有权限ID的交集
					if($childAuth){
						$li .= '<li><a><i class="fa '.$v->f_icon.'"></i>'.$v->f_title.'<span class="fa fa-chevron-down"></span></a>';
						$li .= '<ul class="nav child_menu">';
						foreach ($child as $c) {
							if(in_array($c->f_id, $childAuth)){
								$li .= '<li><a href="'.$c->f_url.'">'.$c->f_title.'</a></li>';
							}
						}
						$li .= '</ul></li>';
					}
					
				}
			}
			
		}
		$div = '<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">';
		$div .=  '<div class="menu_section">';
		$div .= '<h3>后台管理员</h3>';
		$div .= '<ul class="nav side-menu">';
		$div .= $li;
		$div .= '</ul></div></div>';
		$cache->set($menuKey, $div);
		return $div;
	}
}

 ?>