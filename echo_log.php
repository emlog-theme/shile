<?php 
/**
 * 阅读文章页面
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>
<div id="content" class="post-single">
	<div id="singular-content">
		<article id="post-825">
			<header class="entry-header"><h1><?php echo $log_title; ?></h1></header>
			<div class="entry-content">
				<?php
					echo $log_content;
					doAction('log_related', $logData);
				?>
			</div>
			
			<footer class="entry-footer">
				<?php blog_tag($logid); ?>
				
				<div class="meta-author">文 / <?php echo blog_author_name($author); ?></div>
				<div class="share">
					<ul class="share-ul">
						<li><a href="http://twitter.com/share?url=<?php echo URL::log($logid); ?>&text=<?php echo $log_title; ?>" target="_blank" rel="nofollow" class="twitter-share" title="Twitter"></a></li>
						<li><a href="http://facebook.com/share.php?u=<?php echo URL::log($logid); ?>&t=<?php echo $log_title; ?>" target="_blank" rel="nofollow" class="facebook-share" title="facebook"></a></li>
						<li><a href="http://v.t.sina.com.cn/share/share.php?url=<?php echo URL::log($logid); ?>&title=<?php echo $log_title; ?>" target="_blank" rel="nofollow" class="sina-share" title="新浪微博"></a></li>
						<li><a href="http://v.t.qq.com/share/share.php?title=<?php echo $log_title; ?>&url=<?php echo URL::log($logid); ?>&site=<?php echo BLOG_URL; ?>" target="_blank" rel="nofollow" class="tencent-share" title="腾讯微博"></a></li>
						<li><a href="http://www.douban.com/recommend/?url=<?php echo URL::log($logid); ?>&title=<?php echo $log_title; ?>" target="_blank" rel="nofollow" class="douban-share" title="豆瓣网"></a></li>
						<li><a href="http://fanfou.com/sharer?u=<?php echo URL::log($logid); ?>&t=<?php echo $log_title; ?>" target="_blank" rel="nofollow" class="fanfou-share" title="饭否网"></a></li>
						<li><a href="http://share.renren.com/share/buttonshare?link=<?php echo URL::log($logid); ?>&title=<?php echo $log_title; ?>" target="_blank" rel="nofollow" class="renren-share" title="人人网"></a></li>
					</ul>
					<span class="share-c">分享到</span>
				</div>
			</footer>
			<div class="clear"></div>
		</article><!--post-->
		
		<div id="comments">
			<div class="comment-title"><span><?php if($comnum<=0){echo "当前暂无留言";}else{ echo "当前留言：{$comnum}";} ?></span></div>
			
			<?php
				blog_comments($comments);
				blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark);
			?>
			
		</div>

	</div>
</div><!--content-->

<?php include View::getView('footer');?>