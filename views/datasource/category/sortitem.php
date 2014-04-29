<li class="dd-item" data-id="<?php echo $category['id']; ?>">
	<div class="dd-handle">
		<?php echo UI::icon('file'); ?>
		<span class="title"><?php echo $category['header']; ?></span>
	</div>
	
	<?php echo $childs; ?>
</li>