<?php
/*
Template Name:Pjax
Description:模板移植WordPress模板 石乐博客
Version:1.2
Author:石乐
Author Url:http://www.shile.org
Sidebar Amount:1
*/
if(!defined('EMLOG_ROOT')) {exit('error!');}
global $CACHE;
define('AUTHORIZE','*************');
include View::getView('inc/functions');
require_once View::getView('module');
allow_doamin();
if( !$_POST && !$_POST['action'] ){
	if($_POST['action']=='ajax_post'){
		echo "";
	}else{
		include View::getView('modules/mo-header');
	}
}

