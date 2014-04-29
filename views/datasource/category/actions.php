<?php if(ACL::check($ds_type.$ds_id.'.document.edit')):?>
<?php echo UI::button(__('Create Document'), array(
	'href' => Route::url('datasources', array(
		'controller' => 'document',
		'directory' => $ds_type,
		'action' => 'create'
	)) . URL::query(array('ds_id' => $ds_id)),
	'icon' => UI::icon( 'plus' ),
	'hotkeys' => 'ctrl+a'
)); ?>
<?php endif; ?>

<?php if(ACL::check($ds_type.$ds_id.'.document.sort')):?>
<?php echo UI::button(__('Reorder'), array(
	'id' => 'CategoriesReorderButton',
	'class' => 'btn btn-primary',
	'icon' => UI::icon('move icon-white'),
	'hotkeys' => 'ctrl+s'
)); ?>
<?php endif; ?>

<script type="text/javascript">
	$(function() {
		$('#CategoriesReorderButton').on('click', function () {
			var self = $(this);

			if(self.hasClass('btn-inverse')) {
				$('#CategoriesReorderContaier').empty().hide();
				$('#CategoriesHeadlineHeader').show();
				self.removeClass('btn-inverse');
				
				Api.get('/datasource-category', {ds_id: <?php echo $ds_id; ?>}, function(response) {
					if(! response.response ) return;
					
					$('#CategoriesHeadline').empty().html(response.response).show();
					$('#CategoriesHeadlineHeader').show();
				});
				
			} else {
				self.addClass('btn-inverse');
				$('#CategoriesHeadline').hide();
				$('#CategoriesHeadlineHeader').hide();

				Api.get('/datasource-category.sort', {ds_id: <?php echo $ds_id; ?>}, function(response) {
					$('#CategoriesReorderContaier')
						.html(response.response)
						.show();

					$('#nestable').nestable({
						parent_id: 0,
						listNodeName: 'ul',
						listClass: 'dd-list unstyled',
					}).on('change', function(e, el) {
						var list   = e.length ? e : $(e.target);
						Api.post('/datasource-category.sort', {ds_id: <?php echo $ds_id; ?>, 'categories': list.nestable('serialize')});
					});
				});
			}
		});
	})
</script>