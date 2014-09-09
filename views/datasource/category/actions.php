<?php if($datasource->has_access('document.sort')):?>
<?php echo UI::button(UI::hidden(__('Reorder')), array(
	'id' => 'CategoriesReorderButton',
	'class' => 'btn btn-default',
	'icon' => UI::icon('sort'),
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
				
				Api.get('/datasource-category', {ds_id: DS_ID}, function(response) {
					if(! response.response ) return;
					
					$('#CategoriesHeadline').empty().html(response.response).show();
					$('#CategoriesHeadlineHeader').show();
					cms.ui.init('icon');
				});				
			} else {
				self.addClass('btn-inverse');
				$('#CategoriesHeadline').hide();
				$('#CategoriesHeadlineHeader').hide();

				Api.get('/datasource-category.sort', {ds_id: DS_ID}, function(response) {
					$('#CategoriesReorderContaier')
						.html(response.response)
						.show();

					$('.dd').nestable({
						parent_id: 0,
						listNodeName: 'ul',
						listClass: 'dd-list list-unstyled',
					}).on('change', function(e, el) {
						var list   = e.length ? e : $(e.target);
						Api.post('/datasource-category.sort', {ds_id: DS_ID, 'categories': list.nestable('serialize')});
					});
				});
			}
		});
	})
</script>