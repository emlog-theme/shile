/*
 *Site Background by 石乐.
 *version 1.0
 *Author URL:http://www.shile.org
 */
var bg = $('<div id=bg />'),
	bg_wrap = $('<div id=bg-wrap />'),
	bg_img = new Image();
function bg_image_pos(W,o,bg_img){
	var LH=bg_img.height/bg_img.width*W.width(),//对firefox，汗~~啊
		LW=bg_img.width/bg_img.height*W.height();
	o.css({'width':W.width(),'height':W.height()});
	if(LH>=W.height()){
		$(bg_img).css({
			'width':W.width(),
			'height':LH,
			'top':(W.height()-LH)/2,//垂直居中
			'left':0
		});
		
		
	}else{
		$(bg_img).css({
			'height':W.height(),
			'width':LW,
			'left':(W.width()-LW)/2,//水平居中
			'top':0
		});
	}
}
function ajax_bg(){
	//通过判断宽度来决定是否加载背景图片，实现移动设备
	if ( $(window).width() > 780 ){
		$.ajax({
			type:'GET',
			url: Sealey.ajaxurl+"action/bg.php",
			success:function(data){
				bg_img.src = data;
			}
		});
		bg_img.onload = function(){
			bg.append(bg_img).append(bg_wrap);
			bg.css({'position':'fixed','left':'0','top':'0','z-index':'-1','overflow':'hidden'});
			$(bg_img).css({'position':'absolute','opacity':'0'});
			$( '#wrapper' ).after(bg);
			bg_image_pos($(window),bg,bg_img);
			if ( window.location.href != Sealey.ajaxurl && !window.location.href.match(Sealey.ajaxurl+'page')) {
				$(bg_img).animate({opacity:1},800);
			}
			$(window).resize(function(){//改变窗口调整大小
				bg_image_pos($(window),bg,bg_img);
			});
		}
	}
}
$(document).ready(function(){
	ajax_bg();
	$(window).resize(function(){
		if ( !$( '#bg' )[0] ){
			ajax_bg();
		}
	});
});