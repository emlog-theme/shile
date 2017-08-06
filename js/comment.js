/**
 * WordPress jQuery-Ajax-Comments v1.3 by Willin Sealey.
 * 
*/
var edit_mode = '1', // 再編輯模式 ( '1'=開; '0'=不開 )
txt1 = '<div id="loading">Sending...</div>',
txt2 = '<div id="error">#</div>',
txt3 = '">留言成功',
edt1 = ', 刷新页面之前可以<a rel="nofollow" class="comment-reply-link" href="#edit" onclick=\'return addComment.moveForm("',
edt2 = ')\'>重新编辑</a>',
cancel_edit = '取消编辑',
edit,
re_edit,
num = 1,
comm_array=[],
$comments = $('#comments-title'), // 評論數的 ID
$cancel,
cancel_text,
$submit,
$body,
wait = 6,
submit_val;


function commentReply(a, b) {
    var c = document.getElementById("comment-post");
    document.getElementById("cancel-reply").style.display = "";
    document.getElementById("comment-pid").value = a;
    b.parentNode.parentNode.appendChild(c)
}
function cancelReply() {
    var a = document.getElementById("comment-place"),
        b = document.getElementById("comment-post");
    document.getElementById("comment-pid").value = 0;
    document.getElementById("cancel-reply").style.display = "none";
    a.appendChild(b)
}

function respond_ajax(){
	$("#commentform").submit(function() {
        var a = $("#commentform").serialize();
        $("#comment").attr("disabled", "disabled");
        $("#loading").show();
        $.post($("#commentform").attr("action"), a, function(a) {
            var c = /<div class=\"main\">[\r\n]*<p>(.*?)<\/p>/i;
            c.test(a) ? ($("#error").html(a.match(c)[1]).show().fadeOut(2500), $("#loading").hide(),$("#error").show()) : (c = $("input[name=pid]").val(), cancelReply(), $("[name=comment]").val(""), $(".comment-list").html($(a).find(".comment-list").html()), 0 != c ? (a = window.opera ? "CSS1Compat" == document.compatMode ? $("html") : $("body") : $("html,body"), a.animate({
                scrollTop: $("#comment-" + c).offset().top - 200
            }, "normal", function() {
                $("#loading").hide();
            })) : (a = window.opera ? "CSS1Compat" == document.compatMode ? $("html") : $("body") : $("html,body"), a.animate({
                scrollTop: $(".comment-list").offset().top - 200
            }, "normal", function() {
                $("#loading").hide();
            })));
            $("#comment").attr("disabled", !1)
        });
        return !1
    })
}

function exit_prev_edit() {
	$new_comm.show(); $new_sucs.show();
	$('textarea').each(function() {this.value = ''});
	edit = '';
}

function countdown() {
	if ( wait > 0 ) {
		$submit.val(wait); wait--; setTimeout(countdown, 1000);
	} else {
		$submit.val(submit_val).attr('disabled', false).fadeTo('slow', 1);
		wait = 6;
	}
}

function comment_page_ajax(){
	$('.comment-navi a').click(function(e){
		e.preventDefault();
        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            beforeSend: function() {
                $('.comment-navi').remove();
                $('.comment-list').remove();
                $('#loading-comments').slideDown()
            },
            dataType: "html",
            success: function(out) {
                result = $(out).find('.comment-list');
                nextlink = $(out).find('.comment-navi');
                $('#loading-comments').slideUp(550);
                $('#loading-comments').after(result.fadeIn(800));
                $('.comment-list').after(nextlink)
				
				comment_page_ajax();//自身重载一次
				comment_list();//重载评论列表相关
				comm_author_detail();//评论列表hover详细信息重载
				
            }
        })
	});
}

//评论列表
function comment_list(){

	//隐藏以前主题jQ添加的@
	$( '.children .text' ).find( 'a[rel=nofollow]' ).each(function(){
		var i = $(this).attr("href").match(/comment-/);
		var j = $(this).attr("href");
		if ( i!=null || j == '#undefined'){
			$(this).hide();
		}
	});
	
	//子列表左边距
	if ( $(window).width() < 780 ){
		if ( $( '.children' )[0] ){
			$( '.children' ).addClass('mobile');
		}
	}else{
		if ( $( '.children' )[0] ){
			$( '.children' ).removeClass('mobile');
		}
	}
	
	//hover name
	var hover_name = $('<div id=hover-name/>'),
		this_name = '',
		hover_width = 0,
		hover_left,
		hover_time;
	$( '#hover-name' )[0] || $('body').append(hover_name);
	$( '.children .comment-body' ).hover(function(){
		$(this).find('.reply').show();
		var _this = $(this);
		clearTimeout(hover_time);
		hover_time = setTimeout(function(){
			this_name = _this.find('.name').text();
			$( '#hover-name' ).html(this_name);
			hover_width = $( '#hover-name' ).width();
			hover_left = _this.offset().left - hover_width - 40;
			$( '#hover-name' ).show().css({'left':hover_left - 12,'top':_this.offset().top+4,'opacity':0}).stop().animate({left:hover_left,opacity:1},200);
		},20);
	},function(){
		$(this).find('.reply').hide();
		clearTimeout(hover_time);
		$( '#hover-name' ).stop().animate({left:hover_left - 12,opacity:0},200,function(){
			$( '#hover-name' ).hide();
		});
	});
}

//留言框
function comment_respond(){	
	$( '#cancel-comment-reply-link' ).click(function(){/////
		$('#comment').val('');
	});
	
	if ( $( '.welcome' )[0] ){/////
		$( '.author-info' ).hide();
		$( 'span.info-edit' ).click(function(){
			$( '.author-info' ).toggle();
		});
	}
	
	$( '#respond input[type=text]' ).focus(function(){/////
		$(this).css({'color':'rgba(0,0,0,0.6)','border-color':'rgba(0,0,0,0.3)'});
	});
	$( '#respond input[type=text]' ).blur(function(){
		$(this).css({'color':'rgba(0,0,0,0.3)','border-color':'rgba(0,0,0,0.1)'});
	});
	$( '#respond textarea' ).focus(function(){
		$(this).css({'color':'rgba(0,0,0,0.6)'});
		$(this).parent().css({'border-color':'rgba(0,0,0,0.3)'});
	});
	$( '#respond textarea' ).blur(function(){
		$(this).css({'color':'rgba(0,0,0,0.3)'});
		$(this).parent().css({'border-color':'rgba(0,0,0,0.1)'});
	});
}

//评论列表头像hover详细信息;;;和guestdetail大部分代码重复，暂且这样
function comm_author_detail(){
	if ( Sealey.is_mobile != 1 ){//移动端屏蔽
		var list_detail = $('<div id="list-detail" class="detail" />'),
			detail_left,//左边距
			detail_top,//顶距
			detail_time,//鼠标移开的缓冲
			li_hover = 0,
			detail_hover = 0;
		
		if ( !$( '#list-detail ')[0] ){
			$( '#wrapper' ).after(list_detail);
		}
		list_detail = $( '#list-detail ');
		//评论列表
		$( '.comment-list > li' ).each(function(){
			$(this).find('.avatar:first').hover(function(){
				var comment_id = $(this).parent().parent().attr('id').replace('comment-','');
				var _this = $(this),
					_window_width = $(window).width();
				li_hover = 1;
				clearTimeout(detail_time);
				detail_time = setTimeout(function(){
					$.ajax({
						url:Sealey.ajaxurl+'action/admin.php',
						type:'GET',
						data:{ action: 'ajax_author_detail', id: comment_id },
						beforeSend:function(){
							//左距离
							detail_left = _this.offset().left - 110;
							if ( detail_left < 0 ) detail_left = 0;
							if ( detail_left + 260 > _window_width ) detail_left = _window_width - 260;
							//顶距
							detail_top = _this.offset().top + 60;
							//向上显示detail框
							list_detail.show().css({'left':detail_left,'top':detail_top + 24,'opacity':0}).stop().animate({top:detail_top,opacity:1},300);
							//预插入显示三角箭头
							list_detail.html('<div class="list-detail"><div class="triangle"><div></div></div></div>');
							//显示loading
							if ( !$( '#list-detail .loading' )[0] ){
								list_detail.append(_loading);//css 3 loading
							}
							$( '#list-detail .loading' ).show();
						},
						error:function(){
							list_detail.html('ajax error!');
						},
						success:function(data){
							$( '#list-detail .loading' ).fadeOut(function(){
								list_detail.html(data);
								
								//评论地址ajax绑定
								$( '#list-detail a.earlist-comment' ).on('click',function(){//底部留言墙
									try{
										if ( typeof(eval(ajax_menu)) == 'function' ){
											audio_ready();
											var i = ajax_menu($(this));
											return i;
										}
									}catch(e){
									
									}
								});
								
							});
						}
					});
				},80);
				
			},function(){
				li_hover = 0;
				clearTimeout(detail_time);
				detail_time = setTimeout(function(){
					if ( detail_hover == 0 ){
						list_detail.stop().animate({top:detail_top + 24,opacity:0},300,function(){list_detail.hide()});
					}
				},100);
			});
			
			list_detail.hover(function(){
				detail_hover = 1;
			},function(){
				detail_hover = 0;
				clearTimeout(detail_time);
				detail_time = setTimeout(function(){
					if ( li_hover == 0 ){
						list_detail.stop().animate({top:detail_top + 24,opacity:0},300,function(){list_detail.hide()});
					}
				},100);
			});
			
		});
	}
}

function do_comment_js(){
	if ( $( '.comment-list' )[0] ){
		comment_list();
		comm_author_detail();
	}
	if ( $( '#respond' )[0] ){
		comment_respond();
		respond_ajax();
	}
	if ( $( '.comment-navi' )[0] ){
		comment_page_ajax();
	}
}

$(document).ready(function(){
	do_comment_js();
});