<div class="form-group form-inline">
	<label class="<?php echo Arr::get($form, 'label_class'); ?>">
		<?php echo $field->header; ?> <?php if($field->isreq): ?>*<?php endif; ?>
	</label>
	<div class="<?php echo Arr::get($form, 'input_container_class'); ?>">
		<?php echo Form::select($field->name, $field->options(), $value, array(
			'class' => 'form-control', 'style' => 'width:300px;'
		)); ?>

		<?php if($field->hint): ?>
		<p class="help-block"><?php echo $field->hint; ?></p>
		<?php endif; ?>
	</div>
</div>
