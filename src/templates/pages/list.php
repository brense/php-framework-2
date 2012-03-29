<h1>Pages overview</h1>
<table>
	<thead>
		<tr><th>Title:</th><th>Link:</th><th>Template:</th><th>Style:</th><th colspan="3">&nbsp;</th></tr>
	</thead>
	<tbody>
		<?php foreach($this->_vars->pages as $page){ ?>
		<tr>
			<td><?php echo $page->title; ?></td>
			<td><a href="<?php echo $page->uri; ?>"><?php echo $page->uri; ?></a></td>
			<td><?php echo $page->template; ?></td>
			<td><?php echo $page->style; ?></td>
			<td class="narrow"><a href=""><img src="public/themes/icons/page_edit.png" alt="edit page" title="edit page" /></a></td>
			<td class="narrow"><a href=""><img src="public/themes/icons/page_copy.png" alt="copy page" title="copy page" /></td>
			<td class="narrow"><a href=""><img src="public/themes/icons/page_delete.png" alt="delete page" title="delete page" /></td>
		</tr>
		<?php } ?>
	</tbody>
</table>