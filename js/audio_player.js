/*
 *Audio Player by 石乐.
 *version 1.1
 *Author URL:http://www.shile.org
 */
 
//jPlayer -> logo-music

var logo_music_time,
	auto_play = 0,
	playing = 0,
	music_name_show = 0,
	logo_music_index = 0,
	logo_music_href = new Array(),
	logo_music_name = new Array(),
	play_list = $('<div id=play-list/>'),
	play_list_time,
	play_list_is_show = 0;

function logo_music_init(){
	$.ajax({//获取图片地址
		url:Sealey.ajaxurl+"action/music.php",
		type:'GET',
		beforeSend:function(){
		
		},
		error:function(){
		
		},
		success:function(data){
			var responses=data.split('<!--logo_music-->');
			auto_play = responses[0];
			logo_music_name =responses[1].split('<!--logo_music_name-->');
			logo_music_href =responses[2].split('<!--logo_music_src-->');
			//载入播放器播放
			if ( logo_music_href[0] ){
				logo_audio_load(logo_music_href[logo_music_index]);
				logo_play_list();
			}else{
				$( '#openlist' ).addClass('hide');
			}
		}
	});
}

function logo_audio_load(mp3_url){
	$( '#logo_jplayer' ).jPlayer({
		ready:function(){
			if ( auto_play !=0){//自动播放
				$(this).jPlayer("setMedia",{
					mp3:mp3_url
				}).jPlayer('play');
				$('.list-control').addClass('list-pause');
				logo_music_name_show();
				playing = 1;
			}else{//不自动播放
				$( '#logo-music-play' ).show();
				$('.list-control').addClass('list-play');
				playing = 0;
			}
			
			//logo音乐加载完后开始加载文章中的音乐
			if ( $( '.jp-audio' )[0] ){
				audio_ready();
			}
		},
		ended:function(){
			//停止
			$( '#logo-music .loading' ).stop().hide();
			$( '#logo-music-name' ).stop().hide();
			$( '#logo-music-play' ).show();
			//播放列表
			$('.list-control').removeClass('list-pause').addClass('list-play');
			playing = 0;
			
			if ( logo_music_index < logo_music_href.length-1 ){
				logo_music_index ++;
				//alert(logo_music_index);
			}else {
				logo_music_index = 0;
				//alert(logo_music_index);
			}
			$(this).jPlayer("setMedia",{
				mp3:logo_music_href[logo_music_index]
			}).jPlayer('play');
			playing = 1;
			logo_music_name_show();

		},
		swfPath:"http://jplayer.org/latest/js",
		supplied:"mp3"
	});
	
	logo_music_event();//绑定控制事件
}

function logo_music_name_show(){
	clearTimeout(logo_music_time);
	$( '#logo-music-play' ).stop().hide();
	$( '#logo-music-pause' ).stop().hide();
	$( '#logo-music .loading' ).hide();
	music_name_show = 1;
	$( '#logo-music-name' ).text(logo_music_name[logo_music_index]).show().stop().animate({opacity:1,top:50},800,function(){
		logo_music_time = setTimeout(function(){
			$( '#logo-music-name' ).animate({opacity:0,top:0},800,function(){
				$( '#logo-music .loading' ).fadeIn();
				music_name_show = 0;
			});
		},1000);
	});
	
	//播放列表
	$('.list-control').removeClass('list-play').addClass('list-pause');
	$( '#play-list span' ).each(function(){
		if ( $(this).text() == logo_music_name[logo_music_index] ){
			$(this).addClass('list-current');
		}else{
			$(this).removeClass('list-current');
		}
	});
}

function logo_music_name_hide(){//文章中播放音乐，暂停logo音乐
	clearTimeout(logo_music_time);
	$( '#logo-music-pause' ).trigger("click");
}

function logo_music_event(){

	//点击播放
	$( '#logo-music-play' ).click(function(){
		if ( auto_play == 0 ){//非自动播放并且第一次点击播放按钮
			$( '#logo_jplayer' ).jPlayer("setMedia",{
				mp3:logo_music_href[logo_music_index]
			});
			auto_play = 1;
		}
		
		$( '#logo_jplayer' ).jPlayer('play');
		//播放列表
		$('.list-control').removeClass('list-play').addClass('list-pause');
		logo_music_name_show();
		if ( $( '.jp-audio' )[0] ){
			audio_init();//停止文章中的播放器
		}
		playing = 1;
	});
	
	//点击暂停
	$( '#logo-music-pause' ).click(function(){
		clearTimeout(logo_music_time);
		$(this).hide();
		$( '#logo-music-play' ).show();
		$( '#logo-music .loading' ).stop().hide();
		$( '#logo-music-name' ).stop().hide();
		$( '#logo_jplayer' ).jPlayer('pause');
		//播放列表
		$('.list-control').removeClass('list-pause').addClass('list-play');
		playing = 0;
	});
	
	//上一曲
	$( '#logo-music-prev' ).click(function(){
		if ( logo_music_index > 0 ){
			logo_music_index --;
		}else {
			logo_music_index = logo_music_href.length - 1;
		}
		$( '#logo_jplayer' ).jPlayer("setMedia",{
			mp3:logo_music_href[logo_music_index]
		});
		$( '#logo-music-play' ).trigger("click");
	});
	
	//下一曲
	$( '#logo-music-next' ).click(function(){
		if ( logo_music_index < logo_music_href.length-1 ){
			logo_music_index ++;
		}else {
			logo_music_index = 0;
		}
		$( '#logo_jplayer' ).jPlayer("setMedia",{
			mp3:logo_music_href[logo_music_index]
		});
		$( '#logo-music-play' ).trigger("click");
	});
	
	//logo hover事件
	if ( Sealey.is_mobile != 1 ){//电脑端鼠标hover显示全部
		$( '#logo-music' ).hover(function(){
			$( '#logo-music-prev' ).stop().show().animate({left:-120,opacity:1},400);
			$( '#logo-music-next' ).stop().show().animate({right:-120,opacity:1},400);
			if ( playing == 1 && music_name_show == 0 ){
				$( '#logo-music .loading' ).hide();
				$( '#logo-music-pause' ).show();
			}
			
			//play-list
			play_list_show();
			
		},function(){
			$( '#logo-music-prev' ).stop().show().animate({left:-80,opacity:0},400);
			$( '#logo-music-next' ).stop().show().animate({right:-80,opacity:0},400);
			if ( playing == 1 && music_name_show == 0  ){
				$( '#logo-music .loading' ).show();
				$( '#logo-music-pause' ).hide();
			}
			
			//play-list
			play_list_hide();
			
		});
	}else{//移动端hover只显示暂停
		$( '#logo-music' ).hover(function(){
			if ( playing == 1 && music_name_show == 0 ){
				$( '#logo-music .loading' ).hide();
				$( '#logo-music-pause' ).show();
			}
		},function(){
			if ( playing == 1 && music_name_show == 0  ){
				$( '#logo-music .loading' ).show();
				$( '#logo-music-pause' ).hide();
			}
		});
	}
	
	//双击header暂停
	$( '#main-header' ).dblclick(function(){
		$( '#logo-music-pause' ).trigger("click");
	});
	
	//播放列表点击显示
	$( '#openlist' ).on('click',function(){
		if ( play_list_is_show == 0 ){
			play_list_show();
			play_list_time = setTimeout(function(){
				play_list_hide();
			},3000);
		}else{
			play_list.stop().animate({bottom:-play_list.height()},400,function(){
				play_list.hide();
				play_list_is_show = 0;
			});
		}
	});
	
}

function logo_play_list(){
	if ( !$( '#play-list' )[0] ){
		$( '#wrapper' ).after(play_list);
		play_list.append('<div class="list-title"><span class="list-control"></span></div><div class="list-wrap"></div>');
	}
	for ( var i = 0; i < logo_music_name.length; i++ ){
		$( '.list-wrap' ).append('<span rel="' + i +'">' + logo_music_name[i] + '</span>');
	}
	
	play_list.css({'bottom':-play_list.height()}).hide();
	
	
	$( '.list-title' ).on('click',function(){
		clearTimeout(logo_music_time);
		if ( playing == 1 ){
			$( '#logo-music-pause' ).trigger("click");
		}else{
			if ( auto_play == 0 ){//非自动播放并且第一次点击播放按钮
				$( '#logo_jplayer' ).jPlayer("setMedia",{
					mp3:logo_music_href[logo_music_index]
				});
				auto_play = 1;
			}
			$( '#logo-music-play' ).trigger("click");
		}
	});
	
	$( '.list-wrap span' ).on('click',function(){
		logo_music_index = $(this).attr('rel');
		
		$( '#logo_jplayer' ).jPlayer("setMedia",{
			mp3:logo_music_href[logo_music_index]
		});
		$( '#logo-music-play' ).trigger("click");
		
	});
	
	//play list hover显示
	play_list.hover(function(){
		play_list_show();
	},function(){
		play_list_hide();
	});
}

function play_list_show(){
	clearTimeout(play_list_time);
	play_list.stop().show().animate({bottom:0},400);
	play_list_is_show = 1;
}
function play_list_hide(){
	clearTimeout(play_list_time);
	play_list_time = setTimeout(function(){
		play_list.animate({bottom:-play_list.height()},400,function(){
			play_list.hide();
			play_list_is_show = 0;
		});
	},600);
}



/*
 *jPlayer -> post-music
 */
function audio_init(){
	//播放器界面初始化
	$( '#jquery_jplayer' ).jPlayer('destroy');//销毁播放器
	$(' .seek-bar' ).removeClass('jp-seek-bar');
	$(' .play-bar' ).removeClass('jp-play-bar');
	$(' .current-time' ).removeClass('jp-current-time');
	$( '.current-time' ).html('00:00');
	$( '.stop' ).hide();
	$( '.play' ).show();
}

//载入jPlayer
function audio_load(mp3_url){
	$( '#jquery_jplayer' ).jPlayer({
		ready:function(){
			$(this).jPlayer("setMedia",{
				mp3:mp3_url
			});
			$( '#jquery_jplayer' ).jPlayer('play');
		},
		ended:function(){
			audio_init();
		},
		swfPath:"http://jplayer.org/latest/js",
		supplied:"mp3"
	});
	
	//add,pause logo music
	logo_music_name_hide();
	
}

//绑定播放暂停控制事件
function audio_event(){
	$( '.play' ).click(function(){
		
		audio_init();
		
		var _this = $(this);
		_this.hide();
		_this.parent().find( '.stop' ).show();
		
		//jPlayer hook
		_this.parent().find( '.seek-bar' ).addClass('jp-seek-bar');
		_this.parent().find( '.play-bar' ).addClass('jp-play-bar');
		_this.parent().find( '.current-time' ).addClass('jp-current-time');
		
		//do it
		audio_load(_this.attr('rel'));
		
	});
	$( '.stop' ).click(function(){
		$(this).hide();
		$(this).parent().find( '.play' ).show();
		
		$( '#jquery_jplayer' ).jPlayer('stop');
		
		audio_init();
	});
}

//文章载入，遍历播放
function audio_ready(){
	audio_event();
	audio_init();//ajax载入文章销毁播放器
	if ( playing != 1 ){
		$( '.auto' ).each(function(){
			if($(this).attr('rel') == 1){
				$(this).parent().find('.play').trigger("click");
			}
		});
	}
}

$(document).ready(function(){
	
	if ( $( '#logo-music' )[0] ){
		logo_music_init();
	}
	
});

