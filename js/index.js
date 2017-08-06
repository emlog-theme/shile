
var _loading = $( '#loading-wrap .loading' );//全局loading

function page_archive(){
	if ( !document.getElementById("archives") ) return false;
	var year = $( '.year:first' ).attr("id").replace("year-", "");
	var old_top = $( '#archives' ).offset().top;
	$( '.year:first, .month:first' ).addClass('selected');
	$( '.year:first' ).parent().addClass('current-year');
	
	$( '.month' ).click(function(){
		var id = "#" + $(this).attr("id").replace("m", "archive");
		var top = $(id).offset().top-40;
		$( '.month.selected' ).removeClass('selected');
		$(this).addClass('selected');
		$( 'body,html' ).scrollTop(top);
	});
	
	$('.year').click(function(){
		if ( !$(this).next().hasClass('selected')){
			$( '.year.selected' ).removeClass('selected');
			$( '.current-year' ).removeClass('current-year');
			$(this).parent().addClass('current-year');
			$(this).addClass('selected');
		}
		$(this).next().click();
	});
	if ( Sealey.is_mobile != 1 ){//移动端屏蔽
		$(window).scroll(function(){
			var top = $(this).scrollTop();
			$( '.archive-content' ).each(function(){
				var thistop = $(this).offset().top-40,
				thisbottom = thistop + $(this).height();
				var newyear = $(this).attr("id").replace(/archive-(\d*)-\d*/, "$1");
				if ( top >= thistop && top <= thisbottom){
					if ( newyear != year ){
						$( '#year-' + year ).parent().removeClass('current-year');
						$( '#year-' + newyear ).parent().addClass('current-year');
						$( '.year.selected' ).removeClass('selected');
						$( '#year-' + newyear ).addClass('selected');
						year = newyear;
					}
					var id = "#" + $(this).attr("id").replace("archive", "m");
					$( '.month.selected' ).removeClass('selected');
					$(id).addClass('selected');
				}
			});
			//下拉超出修复
			if ( top > ( $('#main-footer').offset().top-$( '#archive-nav' ).offset().top-$( '.archive-nav' ).height()) ){
				$( '.archive-nav' ).css({'bottom':$('#main-footer').height()+10});
			}else{
				$( '.archive-nav' ).css({'bottom':'auto'});
			}
		});
	}else{
		$( '#archive-nav' ).hide();
	}
}

//留言墙hover 详细信息;;;因overflow:hidden，故需js单独操控
function guestbook_detail(){
	if ( Sealey.is_mobile != 1 ){//移动端屏蔽
		var guest_detail = $('<div id="guestbook-detail" class="detail" />'),
			detail_left,//左边距
			detail_top,//顶距
			detail_time,//鼠标移开的缓冲
			li_hover = 0,
			detail_hover = 0;	

		//底部留言墙
		$( '.readwall li' ).hover(function(){
			var _this = $(this),
				_window_width = $(window).width();
			$( '#guestbook-detail ')[0] || $( '#wrapper' ).after(guest_detail);
			li_hover = 1;
			clearTimeout(detail_time);
			detail_time = setTimeout(function(){
				//存入详细内容
				guest_detail.html(_this.find('.detail').html());
				//左距离
				detail_left = _this.offset().left - 95;
				if ( detail_left < 0 ) detail_left = 0;
				if ( detail_left + 260 > _window_width ) detail_left = _window_width - 260;
				//顶距
				detail_top = _this.offset().top - guest_detail.height() - 40;
				//往下显示详细信息
				guest_detail.show().css({'left':detail_left,'top':detail_top - 24,'opacity':0}).stop().animate({top:detail_top,opacity:1},300);
				//最后评论ajax绑定
				$( '#guestbook-detail a.recent-comment' ).on('click',function(){//底部留言墙
					try{
						if ( typeof(eval(ajax_menu)) == 'function' ){
							audio_ready();
							var i = ajax_menu($(this));
							return i;
						}
					}catch(e){
					
					}
				});
				
			},80);
		},function(){
			li_hover = 0;
			clearTimeout(detail_time);
			detail_time = setTimeout(function(){
				if ( detail_hover == 0 ){
					guest_detail.stop().animate({top:detail_top - 24,opacity:0},300,function(){guest_detail.hide()});
				}
			},20);
		});
		
		guest_detail.hover(function(){
			detail_hover = 1;
		},function(){
			detail_hover = 0;
			clearTimeout(detail_time);
			detail_time = setTimeout(function(){
				if ( li_hover == 0 ){
					guest_detail.stop().animate({top:guest_detail.offset().top - 24,opacity:0},300,function(){guest_detail.hide()});
				}
			},20);
		});
	}
}

//文章主体一些js	
function article_js(){
	//文章主体宽度判断
	if ( $(window).width() < 780 ){
		$( '#singular-content' ).addClass('singular-narrow-content');
	}

	//h3标题格式化
	$( 'article h3' ).each(function(){
		var text = $(this).text(),
			line_width = 0,
			margin_top = 0;
		$(this).empty();
		$(this).append('<span class="h3-text">' + text +'</span>');
		line_width = $(this).width() - $(this).find( '.h3-text' ).width() - 12;
		margin_top = $(this).height() / 2 -1;
		$(this).append('<span class="h3-line" style="width:' + line_width + 'px;margin-top:' + margin_top + 'px;"></span>');
	});

	//share
	var share_time;
	$( '.share' ).hover(function(){
		clearTimeout(share_time);
		$( '.share ul' ).slideDown('slow');
	},function(){
		share_time=setTimeout(function(){$( '.share ul' ).slideUp('slow');},300);
	});
	
	//有图片的a标签hover修复
	$( '.entry-content a' ).has('img').addClass('has-img-a');
	
	//jPlayer长度设置
	setTimeout(function(){
		if ( $( '.length-bar' )[0] ){
			$( '.length-bar' ).each(function(){
				$(this).css({'width':$(this).parent().width() - 68});
			});
			
		}
	},10);
	
	//scrolltop
	if ( $( '.post-index' )[0] ){
		$('#scrolltop').addClass('hide');
	}else{
		$('#scrolltop').removeClass('hide');
	}
	
	
}

var narrow_menu_is_show = 0,
	narrow_menu_width;
function narrow_menu(){
	if ( $(window).width() < ( $( '#main-nav' ).width() + 100 ) ){
		$( '#main-nav' ).hide();
		$( '#openmenu' ).fadeIn();
	}else{
		$( '#main-nav' ).show();
		$( '#openmenu' ).fadeOut();
	}
}

$(document).ready(function(){
	
	//main-nav
	var nav_li_width,
		nav_ul_width;
	
	$( '#main-nav li:has(ul)' ).hover(function(){
		nav_li_width = $(this).width();
		nav_ul_width = $(this).find('ul:first').width();
		
		$(this).find('ul:first').stop().css({'left':(nav_li_width - nav_ul_width)/2,'top':'36px'}).show().animate({
			opacity:1,
			top:48
		},300);
	},function(){
		var _this = $(this).find('ul:first');
		_this.stop().animate({
			opacity:0,
			top:36
		},300,function(){
			_this.hide();
		});
	});
	$( '#main-nav li.menu-item-has-children ul li:has(ul)' ).hover(function(){
		$(this).find('ul:first').stop().css({'left':'80%','top':'0'}).show().animate({
			opacity:1,
			left:'100%'
		},300);
	},function(){
		var _this = $(this).find('ul:first');
		_this.stop().animate({
			opacity:0,
			left:'80%'
		},300,function(){
			_this.hide();
		});
	});
	
	//narrow menu,点击事件只绑定一次
	
	$( '#openmenu' ).on('click',function(){
		if ( narrow_menu_is_show == 0 ){
			narrow_menu_width = $(window).width() - 100;
			if ( $( '#wpadminbar' )[0] ){
				$( '#narrow-menu' ).css({'top':$( '#wpadminbar' ).height()});
			}
			$( '#narrow-menu' ).css({'width':narrow_menu_width,'height':$(window).height(),'left':-narrow_menu_width}).show().animate({left:0},400);
			narrow_menu_is_show = 1;
		}else{
			$( '#narrow-menu' ).animate({left:-narrow_menu_width},400,function(){
				$( '#narrow-menu' ).hide();
				narrow_menu_is_show = 0;
			});
			
		}
	});
	
	
	//page-navi
	if ( $(window).width() < 416 ){
		$( '#wide-page-navi' ).hide();
		$( '#narrow-page-navi' ).show();
	}
	$(window).resize(function(){
		if ( $(window).width() < 416 ){
			$( '#wide-page-navi' ).hide();
			$( '#narrow-page-navi' ).show();
		}
		else{
			$( '#wide-page-navi' ).show();
			$( '#narrow-page-navi' ).hide();
		}
		
	});
	
	//文章主体宽度
	$(window).resize(function(){
		if ( $(window).width() < 780 ){
			$( '#singular-content' ).addClass('singular-narrow-content');
		}else{
			$( '#singular-content' ).removeClass('singular-narrow-content');
		}
	});
	
	//jPlayer长度设置
	$(window).resize(function(){
		setTimeout(function(){
			if ( $( '.length-bar' )[0] ){
				$( '.length-bar' ).each(function(){
					$(this).css({'width':$(this).parent().width() - 68});
				});
				
			}
		},10);
	});
	
	//scrolltop
	if ( Sealey.is_mobile != 1 ){//移动端屏蔽
		if ( !$( '#scrolltop' )[0] ){$( '#wrapper' ).after('<div id=scrolltop/>');}
		
		$(window).scroll(function(){
			$( '#scrolltop' ).css({'right':( $(window).width() - 780 ) / 4 - 25});
			if ( $(window).scrollTop()>400 && $(window).width() > 900 ){
				$('#scrolltop').stop().show().animate({opacity:0.5,bottom:100},400);
			}else{
				$('#scrolltop').stop().animate({opacity:0,bottom:80},400,function(){$('#scrolltop').hide();});
			}
		});
		
		$( '#scrolltop' ).click(function(){
			$('html,body').stop().animate({scrollTop:0},400);
		});
			
		$( '#scrolltop' ).hover(function(){
			$(this).animate({opacity:0.8},200);
		},function(){
			$(this).animate({opacity:0.5},200);
		});
			
		$(window).resize(function(){
			$( '#scrolltop' ).css({'right':( $(window).width() - 780 ) / 4 - 25});
			if ( $(window).width() < 900 ){
				$( '#scrolltop' ).hide();
			}
		});
		
	}

	article_js();
	guestbook_detail();
	page_archive();
});