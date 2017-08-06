/*
 *Site Ajax by 石乐
 *version 1.2
 *Author URL:http://www.shile.org
 */

var home_logo_switch = 0;//用以判断顶部是否缩上去
var popbanded = 0;//前进后退按钮只绑定一次

function loading_show(){
	if ( !$( '#loading-wrap .loading' )[0] ){
		$('#loading-wrap').append(_loading);
	}
	$( '#loading-wrap' ).css({'height':$(window).height()}).addClass('show');
	$( '#loading-wrap .loading' ).show().css({'margin-top':( $(window).height() - 120 ) / 2});
}
function loading_hide(){
	$( '#loading-wrap' ).removeClass('show');
	setTimeout(function(){//等待css animation动画缓冲完
		$( '#loading-wrap' ).css({'height':0});
		$( '#loading-wrap .loading' ).css({'margin-top':0}).hide();
	},800);
}

//js重载
function reload_js(){
	//首页文章响应及相册页面响应
	try{
		if ( $( '.post-index' )[0] ){
			if ( typeof(eval(R_init)) == 'function' ){clearTimeout(responsive_time);R_init($( '#content' ),$( '.post-index article' ),297,12,0);}
		}else if ( $( '.album2' )[0] ){
			if ( typeof(eval(R_init)) == 'function' ){clearTimeout(responsive_time);R_init($( '#content' ),$( '.album2 article' ),220,12,0);}
		}
	}catch(e){
		
	}
	//文章播放器重载
	try{
		if ( typeof(eval(audio_ready)) == 'function' ){audio_ready();}
	}catch(e){
	
	}
	//评论js重载
	try{
		if ( typeof(eval(do_comment_js)) == 'function' ){do_comment_js();}
	}catch(e){
	
	}
	//相册重载
	try{
		if ( typeof(eval(gallery)) == 'function' ){gallery();}
	}catch(e){
	
	}
	//文章内一些js重载，必须放到R_init后面，不然计算的h3宽度会有问题
	try{
		if ( typeof(eval(article_js)) == 'function' ){article_js();}
	}catch(e){
	
	}
	//历史页面js
	try{
		if ( typeof(eval(page_archive)) == 'function' ){page_archive();}
	}catch(e){
	
	}
	
	//hermit
	if( window.hermitjs !== undefined ){
		hermitjs.reload();
	}
	
	//page-navi
	if ( $( '.page-navi' )[0] ){
		if ( $(window).width() < 416 ){
			$( '#wide-page-navi' ).hide();
			$( '#narrow-page-navi' ).show();
		}
	}
	
	ajax_post_bind();//重新绑定文章标题
}
	
//ajax文章
function ajax_get_post(title,href,push_switch){
	$.ajax({
		url:href,
		type:'POST',
		data:'action=ajax_post',
		dataType:'html',
		beforeSend:function(){
			$( '#main' ).fadeTo('normal',0.6);
			loading_show();
			$( '#narrow-menu' ).hide();narrow_menu_is_show = 0;
			
		},
		error:function(){
			$( '#main' ).html('Ajax Error!').fadeTo('normal',1);
		},
		success:function(data){
			loading_hide();
			$('html,body').animate({scrollTop:0},0);
			$( '#main' ).html(data).fadeTo('normal',1);
			if ( href != Sealey.www && !href.match(Sealey.www+'page')) {//非首页执行代码
				$( '#logo' ).animate({marginTop:-120,opacity:0},800);
				$( '#search-form' ).animate({marginBottom:-24,opacity:0},800,function(){
					$( '#logo' ).hide();
					$( '#search-form' ).hide();
					
					//显示播放列表按钮
					if ( Sealey.is_mobile != 1 ){
						$( '#openlist' ).fadeIn();
					}
					
					//判断是否隐藏导航
					try{
						if ( typeof(eval(narrow_menu)) == 'function' ){narrow_menu();}
					}catch(e){
					
					}
					//判断是否显示背景图片
					if ( $( '#bg' )[0] ){ $( '#bg img' ).stop().animate({opacity:1},1200);}
					
				});
				reload_js();//js重载
				home_logo_switch = 0;
				
			}else{//首页执行代码
				if ( home_logo_switch== 0 ){
					$( '#logo' ).show().animate({marginTop:12,opacity:1},800);
					$( '#search-form' ).show().animate({marginBottom:12,opacity:1},800,function(){
						reload_js();//首页当顶部动画过后再重载js
					});
					
					//隐藏播放列表
					if ( Sealey.is_mobile != 1 ){
						$( '#openlist' ).hide();
					}
					
					//显示原始导航菜单
					$( '#main-nav' ).fadeIn();
					$( '#openmenu' ).fadeOut();
					
					//判断是否隐藏背景图片
					if ( $( '#bg' )[0] ){ $( '#bg img' ).stop().animate({opacity:0},1200);}
					
					home_logo_switch = 1;
				}else{
					reload_js();
				}
			}
			
			window.document.title = title;
			if ( push_switch == 1 ) {
				var state = {
					title: title,
					url: href
				}
				window.history.pushState(state, title, href);
			}
			if ( popbanded == 0 ){
				window.addEventListener('popstate',function(e){
					if ( history.state ){
						ajax_get_post(history.state.title,history.state.url,0);
					}else{
						ajax_get_post(Sealey.ajax_site_title,window.location.href,0);//暂时这样解决退回不到第一次打开的页面的情况
					}
					//主菜单
					$( '.current-menu-item' ).removeClass('current-menu-item');
					$( '.current-menu-parent' ).removeClass('current-menu-parent');
					$( '.current-menu-ancestor' ).removeClass('current-menu-ancestor');
				},false);
				popbanded = 1;
			}
		}
	});
}

//获取文章前的一些处理
function ajax_start(_this){
	var title = ( _this.attr("title")?_this.attr("title"):_this.text() ) + " - " + Sealey.ajax_site_title,
		href = _this.attr("href"),
		this_href = window.location.href;
		i = href.replace(this_href,'');
	//if ( href == this_href ) return false;
	if ( i == '#' ) return false;
	if ( !href.match(Sealey.www ) ) return 0;
	ajax_get_post(title,href,1);
	return 1;
}
	
function ajax_menu(_this){
	var i = ajax_start(_this);
	if ( i == 1 ){
		//主菜单
		$( '.current-menu-item' ).removeClass('current-menu-item');
		$( '.current-menu-parent' ).removeClass('current-menu-parent');
		$( '.current-menu-ancestor' ).removeClass('current-menu-ancestor');
		return false;
	}
	return i;
}

function ajax_post_bind(){
	$( 'a.post-title' ).on('click',function(){//文章标题
		var i = ajax_menu($(this));
		return i;
	});
	$( '.format-image a' ).on('click',function(){//图像文章首页图片
		var i = ajax_menu($(this));
		return i;
	});
	
	$( '.entry-content a[target!=_blank]').each(function(){//文章内容中链接
		if ( !$(this).has('img')[0] ){
			$(this).on('click',function(){
				var i = ajax_menu($(this));
				return i;
			});
		}
	});
	$( '.meta-tag a' ).on('click',function(){//文章标签
		var i = ajax_menu($(this));
		return i;
	});
	$( '.page-navi a' ).on('click',function(){//文章分页
		var i = ajax_menu($(this));
		return i;
	});
}

$(document).ready(function(){
	//页面刷新，判断是否为首页
	if ( window.location.href != Sealey.www && !window.location.href.match(Sealey.www+'page') ) {
		$( '#logo' ).animate({marginTop:-120,opacity:0},800,function(){
			$( '#logo' ).hide();
			$( '#search-form' ).hide();
			$( '#openlist' ).fadeIn();
		});
		$( '#search-form' ).animate({marginBottom:-24,opacity:0},800);
		
		//判断是否显示导航
		try{
			if ( typeof(eval(narrow_menu)) == 'function' ){narrow_menu();}
		}catch(e){
		
		}
		$(window).resize(function(){
			narrow_menu();
		});
		
	}else{
		home_logo_switch = 1;
	}
	
	//依次绑定
	ajax_post_bind();
		
	//只需要绑定一次
	//主菜单
	$( '.nav-menu a' ).on('click',function(){
		var i = ajax_start($(this));
		if ( i == 1 ){
			//主菜单
			$( '.current-menu-item' ).removeClass('current-menu-item');
			$( '.current-menu-parent' ).removeClass('current-menu-parent');
			$( '.current-menu-ancestor' ).removeClass('current-menu-ancestor');
			$(this).parent().addClass('current-menu-item');
			return false;
		}
		return i;
	});
		
	//限宽最新评论
	if ( $( '#footer-comments' )[0] ) {
		$( '#footer-comments a' ).on('click',function(){
			var i = ajax_menu($(this));
			return i;
		});
	}
		
	//限宽热门标签
	if ( $( '#footer-tags' )[0] ) {
		$( '#footer-tags a' ).on('click',function(){
			var i = ajax_menu($(this));
			return i;
		});
	}
	
	//搜索框
	$('#search-form').submit(function() {
		var s = $(this).find( '#keyword' ).val();
		if( s == "" ){
			return false;
		}
		var href = $(this).attr('action') + '?keyword=' + encodeURIComponent(s),
			title = s + ' - 搜索结果';
		ajax_get_post(title,href,1);
		
		return false;
	});
	
	
	//如果是移动端就一直显示
	if ( Sealey.is_mobile == 1 ){
		$( '#openlist' ).fadeIn();
	}
	
});