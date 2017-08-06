<?php 
/**
 * 页面底部信息
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>
</div><!--main-->
	<footer id="main-footer">
		<div id="footer-widget">
			<aside id="footer-comments" class="foo-widget">
				<h3>最新评论</h3>
				<ul>
					<?php
						$com_cache = $CACHE->readCache('comment');
						foreach($com_cache as $value):
						$url = Url::comment($value['gid'], $value['page'], $value['cid']);
					?>
					<li>
						<span class="recent-comments-avatar"><img src="<?php echo cache_gravatar($value['mail']); ?>" class="avatar avatar-32" height="32" width="32"></span><a href="<?php echo $url; ?>" title="<?php echo $value['name']; ?>在发表的评论"><?php echo subString(strip_tags($value['content']),0,30); ?></a>
					</li>
					<?php endforeach; ?>
				</ul>
			</aside>
			<aside id="footer-tags" class="foo-widget">
				<h3>云标签</h3>
				<ul class='wp-tag-cloud'>
					<?php
						$tag_cache = $CACHE->readCache('tags');
						shuffle($tag_cache);
						$tag_cache = array_slice($tag_cache,0,20);
						foreach($tag_cache as $value):
					?>
					<li><a href='<?php echo Url::tag($value['tagurl']); ?>' class='tag-link-16' title='<?php echo $value['usenum']; ?>个话题' style='font-size: <?php echo $value['fontsize']; ?>px;'><?php echo $value['tagname']; ?></a></li>
					<?php endforeach; ?>
				</ul>
				<ul></ul>
			</aside>
			<aside id="footer-readwall" class="foo-widget readwall">
				<h3>留言墙</h3>
				<ul>
					<?php echo reader_wall_side(3); ?>
				</ul>
			</aside>
		</div>
		<div id="footer-copy">
				copyright &copy;&nbsp;2016&nbsp;<?php echo $blogname; ?>&nbsp;&nbsp;
				theme by <a href="http://www.shile.org/" target="_blank">石乐</a>
				主机空间：<a href="http://www.qcloud.com/" target="_blank">腾讯云</a>
				<?php if($icp):?><a href="http://www.miitbeian.gov.cn" target="_blank">ICP备案：<?php echo $icp; ?></a><?php endif;?>&nbsp;&nbsp;
				<span id="timeDate">载入天数...</span>
				<span id="times">载入时分秒...</span>
<script language="javascript">
var now = new Date();
function createtime(){
var grt= new Date("04/16/2016 08:00:00");
now.setTime(now.getTime()+250);
days = (now - grt ) / 1000 / 60 / 60 / 24;
dnum = Math.floor(days);
hours = (now - grt ) / 1000 / 60 / 60 - (24 * dnum);
hnum = Math.floor(hours);
if(String(hnum).length ==1 ){hnum = "0" + hnum;}
minutes = (now - grt ) / 1000 /60 - (24 * 60 * dnum) - (60 * hnum);
mnum = Math.floor(minutes);
if(String(mnum).length ==1 ){mnum = "0" + mnum;}
seconds = (now - grt ) / 1000 - (24 * 60 * 60 * dnum) - (60 * 60 * hnum) - (60 * mnum);
snum = Math.round(seconds);
if(String(snum).length ==1 ){snum = "0" + snum;}
document.getElementById("timeDate").innerHTML = "本站已安全运行："+dnum+"天";
document.getElementById("times").innerHTML = hnum + "小时" + mnum + "分" + snum + "秒";
}
setInterval("createtime()",250);
</script><?php echo $footer_info; ?>
		</div>			
		<div class="clear"></div>
	</footer><!--footer-->
</div>

<nav id="narrow-menu"><?php blog_navi(); ?></nav>

<div id="loading-wrap">
	<div class="loading">
		<div class="loading-bar">
			<div class="bar1"></div>
			<div class="bar2"></div>
			<div class="bar3"></div>
			<div class="bar4"></div>
		</div>
		<div class="loading-text">loading</div>
	</div>
</div><!--loading-->

<div id="jquery_jplayer" class="jp-jplayer"></div>

<script type="text/javascript">SyntaxHighlighter.all();</script>

<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/soundmanager2.js?ver=2.5.1'></script>

<script type='text/javascript'>
var wp_player_params = {
	"swf":"<?php echo TEMPLATE_URL; ?>js/",
	"url":"<?php echo TEMPLATE_URL; ?>",
	"nonce":"94bce7539d",
	"single":"false"
};
</script>

<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/wp-player.js?ver=2.5.1'></script>
<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/jquery-1.10.2.min.js?ver=1.10.2'></script>
<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/jquery.jplayer.min.js?ver=2.5.0'></script>
<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/jquery.mousewheel.js?ver=3.1.11'></script>

<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/responsive.js?ver=1.1'></script>
<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/comment.js?ver=1.2'></script>
<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/audio_player.js?ver=1.1'></script>
<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/bg.js?ver=1.0'></script>
<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/gallery.js?ver=1.0'></script>
<script type='text/javascript'>

var Sealey = {
	"is_mobile":"0",
	"www":"<?php echo BLOG_URL; ?>",
	"ajaxurl":"<?php echo TEMPLATE_URL; ?>",
	"ajax_site_title":"<?php echo option('site_title'); ?>"
};

</script>
<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/index.js?ver=1.2'></script>
<script type='text/javascript' src='<?php echo TEMPLATE_URL; ?>js/site-ajax.js?ver=1.2'></script>
<?php doAction('index_footer'); ?>
</body>
</html>
<?php

$html=ob_get_contents();
ob_get_clean();
echo em_compress_html_main($html);

?>