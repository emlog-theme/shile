/*
 *Article Responsive by 石乐
 *version 1.2
 *Author URL:http://www.shile.org
 */
var _initial = 297,
	_margin = 12,
	_padding = 0;
var content_width,
	article_width,//纯width，不包含padding和margin
	mul_width,
	all_height = new Array(),
	r_time;
var line,//行数
	column,//列数
	pos_left,//absolute left
	pos_top,//absolute top
	max_pos_top,//记录最大高度，设置content的高度
	pos_index,
	pos_index_new,
	_this_height=0;
var responsive_time;
function R_init(r_container,r_unit,init_width,r_margin,r_padding){
	_initial = init_width;
	_margin = r_margin;
	_padding = r_padding;
	
	var _container = r_container,
		_unit = r_unit;
	if ( _unit[0] ){
		max_pos_top = _container.height();
		//alert(max_pos_top);
		_container.css({'height':max_pos_top});//初始化让容器高度等于原本高度，防止absolute过后滚动条消失候容器宽度不正常
		_unit.css({'opacity':'1','position':'absolute','left':'0','top':'0'});
		R_width(_container,_unit);
	}
	
	
}

//调整文章大小
function R_width(_container,_unit){
	//$( '#content' ).css({'height':'1000px'});//预先随意设置一个高度，防止当滚动条没出现时计算宽度错误
	
	content_width = _container.width();
	article_width = _initial+_padding*2+_margin;//加上2倍padding和margin
	if ( content_width < article_width ){
		mul_width = 1;//防止屏幕缩太小时计算不出article_width
	}else{
		mul_width = parseInt(content_width/article_width);//取整，每行可以放多少个文章
	}
	article_width = (content_width-_margin)/mul_width-_padding*2-_margin;//默认257大小，适当放大以适应屏幕,-12表示给左右两边预留6px的空白
	//alert(article_width);
	_unit.each(function(){
		$(this).css({'width':article_width});
	});
	R_position(_container,_unit);
	$(window).resize(function(){
		clearTimeout(r_time);
		r_time = setTimeout(function(){
			R_width(_container,_unit);
		},5)
	});
	
	if ( $( '#footer-widget' ) ){
		footer_width();
	}
	
}

//定位
function R_position(_container,_unit){
	clearTimeout(responsive_time);
	pos_left = 0;
	pos_index = 0;
	max_pos_top = 0;
	all_height.length = 0;
	_unit.each(function(){
		_this_height = $(this).height();
		all_height.push(_this_height+_padding*2+_margin);
		pos_top = 6;//顶部预留6px
		line = parseInt(pos_index/mul_width);
		column = pos_index-(parseInt(pos_index/mul_width)*mul_width);
		pos_left = Math.round(column*(article_width+_padding*2+_margin)+_margin/2);//加上padding和margin;如果是小数，translate后元素会模糊掉，所以用math.round四舍五入
		pos_index_new = pos_index;
		//计算顶距
		while(line>0){
			pos_top += all_height[pos_index_new-mul_width];
			line -= 1;
			pos_index_new -= mul_width;
		}
		if ( pos_top+_this_height+_padding*2+_margin+_margin/2 > max_pos_top ) {
			max_pos_top = pos_top+_this_height+_padding*2+_margin+_margin/2;//加上顶边距加上自身高度,6是底部预留6px
		}
		
		//$(this).stop().animate({left:pos_left,top:pos_top},800);
		$(this).css({'transform':'translate('+pos_left+'px,'+pos_top+'px)'});
		pos_index += 1;
	});
	//alert(max_pos_top);
	_container.css({'height':max_pos_top});
	
	responsive_time = setTimeout(function(){
		R_position(_container,_unit);
	},900);
	
}

function footer_width(){
	var footer_padd;
	if ( $( '#footer-widget' ).width() < 900 ){
		
		if ( $( '#footer-widget' ).width() > 720 ){
			footer_padd = parseInt( ( $( '#footer-widget' ).width() - 720 ) / 6 );
			//alert(padd);
		}else{
			footer_padd = parseInt( ( $( '#footer-widget' ).width()-240 )/ 2 );
			
		}
		
	}else{
		footer_padd = 30;
	}
	$( '.foo-widget' ).css({'padding-left':footer_padd,'padding-right':footer_padd});
}

$(document).ready(function(){
	if ( $( '.post-index' )[0] ){
		R_init($( '#content' ),$( '.post-index article' ),297,12,0);
	}else if ( $( '.album2' )[0] ){
		R_init($( '#content' ),$( '.album2 article' ),220,12,0);
	}
});

$(window).load(function(){
	clearTimeout(responsive_time);
});