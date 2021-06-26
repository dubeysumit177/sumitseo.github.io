<script>
	$(function() {
		"use strict";
		$(document).ready(function() {			
			/* extract the offensive words and then set the input field */
			$(".inputtags").tagsinput('items');
			<?php if (isset($template_data['offensive_words'])) : ?>

				<?php $offensive_keywords_arr = explode(',', $template_data['offensive_words']); ?>
				<?php foreach($offensive_keywords_arr as $keyword) : ?>
					$(".inputtags").tagsinput('add', '<?php echo $keyword; ?>');
				<?php endforeach; ?>

			<?php endif; ?>


			<?php if ($template_data['delete_offensive_comment'] == '0') : ?>
				$("#offensive_keywords_block").hide();
			<?php endif; ?>


			<?php if ($template_data['reply_type'] == 'generic') : ?>
				$(".filter_message_block").hide();
			<?php elseif ($template_data['reply_type'] == 'filter') : ?>
				$(".generic_message_block").hide();
			<?php endif; ?>			
		});
	});
</script>

<script src="<?php echo base_url('assets/js/system/auto_reply_template_edit.js');?>"></script>