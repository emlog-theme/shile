<?php 
/**
 * 侧边栏组件、页面模块
 */
if(!defined('EMLOG_ROOT')) {exit('error!');}
//blog：导航
function blog_navi(){
	global $CACHE; 
	$navi_cache = $CACHE->readCache('navi');
	echo "<div class=\"menu-导航菜单-container\"><ul id=\"menu-导航菜单\" class=\"nav-menu\">";
	foreach($navi_cache as $value){
		
		if ($value['pid'] != 0) {
            continue;
        }
		
		$newtab = $value['newtab'] == 'y' ? ' target="_blank"' : '';
		$value['url'] = $value['isdefault'] == 'y' ? BLOG_URL . $value['url'] : trim($value['url'], '/');
		$current_tab = BLOG_URL . trim(Dispatcher::setPath(), '/') == $value['url'] ? 'current-menu-item' : '';
		
		if( !empty($value['children']) || !empty($value['childnavi']) ){
			$children = 'menu-item-has-children';
		}
		
		echo "<li id=\"menu-item-{$value['id']}\" class=\"{$current_tab}{$children}\"><a href=\"{$value['url']}\"{$newtab}>{$value['naviname']}</a>";
		
		if(!empty($value['children'])){
			echo "<ul class=\"sub-menu\">";
				foreach ($value['children'] as $row){
					echo '<li id="menu-item-'.$row['sid'].'" ><a href="'.Url::sort($row['sid']).'">'.$row['sortname'].'</a></li>';
                }
			echo "</ul>";
		}
		
		if(!empty($value['childnavi'])){
			echo "<ul class=\"sub-menu\">";
				foreach ($value['childnavi'] as $row){
					$newtab = $row['newtab'] == 'y' ? 'target="_blank"' : '';
					echo '<li id="menu-item-'.$row['sid'].'" ><a href="' . $row['url'] . "\" $newtab >" . $row['naviname'].'</a></li>';
                }
			echo "</ul>";
		}
		
		echo "</li>";
		
	}
	
	echo "</ul></div>";
	
}
//blog：文章标签
function blog_tag($blogid){
	global $CACHE;
	$log_cache_tags = $CACHE->readCache('logtags');
	if (!empty($log_cache_tags[$blogid])){
		$tag = '<div class="meta-tag">Tags:';
		foreach ($log_cache_tags[$blogid] as $value){
			$tag .= "<a href=\"".Url::tag($value['tagurl'])."\" rel=\"tag\">".$value['tagname'].'</a>';
		}
		$tag .= '</div>';
		echo $tag;
	}
}

//blog：评论列表
function blog_comments($comments){
    extract($comments);
?>
<a name="comments"></a>
<div id="loading-comments" style="display:none"><span>数据加载中......</span></div>
<ol class="comment-list">
<?php
	$isGravatar = Option::get('isgravatar');
	foreach($commentStacks as $cid):
    $comment = $comments[$cid];
?>
	<li id="li-comment-<?php echo $comment['cid']; ?>">
		<a name="<?php echo $comment['cid']; ?>"></a>
		<div id="comment-<?php echo $comment['cid']; ?>" class="comment-body">
			<div class="author"><img src="<?php echo cache_gravatar($comment['mail']); ?>" class="avatar avatar-38" height="38" width="38"></div>
			<span class="time"><?php echo gmdate('Y/n/j', $comment['date']); ?></span>
			<div class="commlist-middle">
				<span class="name"><?php echo $comment['url'] ? '<a href="'.$comment['url'].'" target="_blank">'.$comment['poster'].'</a>' : $comment['poster'] ; ?></span>
				<div class="reply"><a class='comment-reply-link' href='#comment-<?php echo $comment['cid']; ?>' onclick='commentReply(<?php echo $comment['cid']; ?>,this)' aria-label='回复给<?php echo $comment['poster']; ?>'>回复</a></div>
				<div class="text"><p><?php echo $comment['content']; ?></p></div>
			</div>
		</div>
		<ul class="children"><?php blog_comments_children($comments, $comment['children']);?></ul><!-- .children -->
	</li><!-- #comment-## -->
	<?php endforeach; ?>
</ol>
<div class="comment-navi" id="pagenavi"><?php echo $commentPageUrl;?></div>
<!--comentlist-->
<?php }?>



<?php
//blog：子评论列表
function blog_comments_children($comments, $children){
	$isGravatar = Option::get('isgravatar');
	foreach($children as $child):
	$comment = $comments[$child];
	$comment['poster'] = $comment['url'] ? '<a href="'.$comment['url'].'" target="_blank">'.$comment['poster'].'</a>' : $comment['poster'];
	?>
<li class="depth-2" id="li-comment-<?php echo $comment['cid']; ?>">
	<a name="<?php echo $comment['cid']; ?>"></a>
	<div id="comment-<?php echo $comment['cid']; ?>" class="comment-body">
		<div class="author"><img src="<?php echo cache_gravatar($comment['mail']); ?>" class="avatar avatar-38" height="38" width="38"></div>
		<span class="time"><?php echo gmdate('Y/n/j', $comment['date']); ?></span>
		<div class="commlist-middle">
			<span class="name"><?php echo $comment['url'] ? '<a href="'.$comment['url'].'" target="_blank">'.$comment['poster'].'</a>' : $comment['poster'] ; ?></span>
			<div class="reply"><a class='comment-reply-link' href='#comment-<?php echo $comment['cid']; ?>' onclick='commentReply(<?php echo $comment['cid']; ?>,this)' aria-label='回复给<?php echo $comment['poster']; ?>'>回复</a></div>
			<div class="text"><p><?php echo $comment['content']; ?></p></div>
		</div>
	</div>
	<ul class="children"><?php blog_comments_children($comments, $comment['children']);?></ul>
</li><!-- #comment-## -->
	<?php endforeach; ?>
<?php }?>


<?php
//blog：发表评论表单
function blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark){
	if($allow_remark == 'y'): ?>
<div class="comment-title"><span>我要留言</span></div>
<div id="comment-place">
	<div class="comment-post" id="comment-post">
		<div id="respond">
			<form method="post" name="commentform" action="<?php echo BLOG_URL; ?>index.php?action=addcom" id="commentform">
				<input type="hidden" name="gid" value="<?php echo $logid; ?>" />
				<span class="cancel_comment_reply cancel-reply" id="cancel-reply" style="display:none">
					<a rel="nofollow" id="cancel-comment-reply-link" href="javascript:void(0);" onclick="cancelReply()" >取消回复</a>
				</span>
				<div class="author-info">
					<div>
						<label>名字：</label>
						<input type="text" name="comname" id="comname" value="<?php echo $ckname; ?>" tabindex="1" aria-required='true' />
					</div>
					<div>
						<label>邮箱：</label>
						<input type="text" name="commail" id="commail" value="<?php echo $ckmail; ?>" tabindex="2" aria-required='true' />
					</div>
					<div>
						<label>网站：</label>
						<input type="text" name="comurl" id="comurl" value="<?php echo $ckurl; ?>" tabindex="3" />
					</div>
				</div>
				<div class="comment-textarea">
					<textarea name="comment" id="comment" tabindex="4" onkeydown="if(event.ctrlKey&&event.keyCode==13){document.getElementById('submit').click();return false};"></textarea>
				</div>
				<div>
					<input name="submit" type="submit" id="submit" tabindex="5" value="发布" />
					<div id="loading" style="display: none;">Sending...</div>
					<div id="error" style="display: none;"></div>
					<input type="hidden" name="pid" id="comment-pid" value="0" size="22" tabindex="1"/>
				</div>
			</form>
		</div>
	</div>
</div>
	<?php endif; ?>
<?php }?>
<?php
//blog-tool:判断是否是首页
function blog_tool_ishome(){
    if (BLOG_URL . trim(Dispatcher::setPath(), '/') == BLOG_URL){
        return true;
    } else {
        return FALSE;
    }
}
?>