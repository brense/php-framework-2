<h1>Content overview</h1>
<table>
	<thead>
		<tr><th>Parent/range:</th><th>Type:</th><th>Content:</th><th colspan="3">&nbsp;</th></tr>
	</thead>
	<tbody>
		<?php foreach($this->_vars->content as $content){ ?>
		<tr>
			<?php if($content->parent_id == 0){?>
			<td class="nowrap"><?php echo $content->range; ?></td>
			<?php } else if($content->parent_type == 'page') { ?>
			<td class="nowrap"><?php echo $content->parent_type; ?>: <a href=""><?php echo $this->_vars->pages[$content->parent_id]; ?></a></td>
			<?php } else { ?>
			<td class="nowrap"><?php echo $content->parent_type; ?>: <a href=""><?php echo $content->parent_id; ?></a></td>
			<?php } ?>
			<td><?php echo $content->type; ?></td>
			<td><?php echo str_replace('","', '", "', $content->content); ?></td>
			<td class="narrow"><a href=""><img src="public/themes/icons/page_edit.png" alt="edit content" title="edit content" /></a></td>
			<td class="narrow"><a href=""><img src="public/themes/icons/page_copy.png" alt="copy content" title="copy content" /></td>
			<td class="narrow"><a href=""><img src="public/themes/icons/page_delete.png" alt="delete content" title="delete content" /></td>
		</tr>
		<?php } ?>
	</tbody>
</table>