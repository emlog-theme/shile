<?php 
/**
 * 站点首页模板
 */
if(!defined('EMLOG_ROOT')) {exit('error!');}

doAction('index_loglist_top');
?>
<div id="content" class="post-index">
	<?php
		if(!empty($logs)):
		foreach($logs as $value):
		if(pic_thumb($value['content'])){
			$imgsrc = "<p><a href=\"{$value['log_url']}\" class=\"post-title\" title=\"{$value['log_title']}\" rel=\"bookmark\" ><img width=\"455\" height=\"341\" src=\"".pic_thumb($value['content'])."\" class=\"attachment-post-thumbnail wp-post-image\" /></a></p>";
		}else{
			$imgsrc = '';
		}
	?>
	<article id="post-<?php echo $value['logid']; ?>" class="post-<?php echo $value['logid']; ?> post type-post" >
		<div class="article-wrap">
			<header class="entry-header">
				<h1>
					<a href="<?php echo $value['log_url']; ?>" rel="bookmark" class="post-title" title = "<?php echo $value['log_title']; ?>"><?php echo $value['log_title']; ?></a>
					<span class="meta-time"><?php echo gmdate('h月i日', $value['date']); ?></span>
				</h1>
			</header>
			<div class="entry-content">
				<?php echo $imgsrc; ?>
				<p><?php echo clean(blog_tool_purecontent($value['content'],500)); ?></p>
			</div>
		</div>
	</article><!--post-index-->
	<?php 
endforeach;
else:
?>
<?php endif; ?>
</div><!--content-->
<?php echo static_page($lognum,$index_lognum,$page,$pageurl); ?>
<?php include View::getView('footer'); ?>