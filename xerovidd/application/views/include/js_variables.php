<script type="text/javascript">
	"use strict";
	var base_url="<?php echo base_url(); ?>";
	var user_id = "<?php echo $this->user_id; ?>";
	var selected_language="<?php echo $this->language; ?>";
	var is_demo = "<?php echo $this->is_demo; ?>";
	var global_lang_video_upload_limit = '<?php echo $this->config->item("video_upload_limit");?>';
	global_lang_video_upload_limit = parseInt(global_lang_video_upload_limit);

	var global_lang_report = '<?php echo $this->lang->line("Report"); ?>';
	var global_lang_view = '<?php echo $this->lang->line("View"); ?>';
	var global_lang_edit = '<?php echo $this->lang->line("Edit"); ?>';
	var global_lang_delete = '<?php echo $this->lang->line("Delete"); ?>';
	var global_lang_remove = '<?php echo $this->lang->line("Remove"); ?>';
	var global_lang_add_more = '<?php echo $this->lang->line("Add more");?>';
	var global_lang_play = '<?php echo $this->lang->line("Play");?>';
	var global_lang_visit_channel = '<?php echo $this->lang->line("Visit Channel");?>';
	var global_lang_watch_video = '<?php echo $this->lang->line("Watch Video");?>';
	var global_lang_not_applicable = '<?php echo $this->lang->line("N/A");?>';
	var global_lang_url_copied_clipbloard = '<?php echo $this->lang->line("Url Copied to clipboard");?>';

	var global_lang_active = '<?php echo $this->lang->line("Active"); ?>';
	var global_lang_inactive = '<?php echo $this->lang->line("Inactive"); ?>';
	var global_lang_processing = '<?php echo $this->lang->line("Processing"); ?>';
	var global_lang_completed = '<?php echo $this->lang->line("Completed"); ?>';
	var global_lang_pending = '<?php echo $this->lang->line("Pending"); ?>';

	var global_lang_success = '<?php echo $this->lang->line("Success"); ?>';
	var global_lang_error = '<?php echo $this->lang->line("Error"); ?>';
	var global_lang_warning = '<?php echo $this->lang->line("Warning"); ?>';

	var global_lang_last_30_days = '<?php echo $this->lang->line("Last 30 Days");?>';
	var global_lang_this_month = '<?php echo $this->lang->line("This Month");?>';
	var global_lang_last_month = '<?php echo $this->lang->line("Last Month");?>';
	var global_lang_select_from_date = '<?php echo $this->lang->line("Please select from date");?>';
	var global_lang_select_to_date = '<?php echo $this->lang->line("Please select to date");?>';

	var global_lang_try_once_again = '<?php echo $this->lang->line("try once again")?>';
	var global_lang_something_went_wrong = '<?php echo $this->lang->line("Something went wrong, please try again.")?>';
	var global_lang_no_data_found = '<?php echo $this->lang->line("No data found"); ?>';
	var global_lang_are_you_sure = '<?php echo $this->lang->line("Are you sure?");?>';
	var global_lang_saved_successfully = '<?php echo $this->lang->line("Your data has been successfully saved."); ?>';
	var global_lang_delete_confirmation = '<?php echo $this->lang->line("Do you really want to delete it?");?>';

	var global_lang_campaign_create = '<?php echo $this->lang->line("Create Campaign");?>';
	var global_lang_campaign_edit = '<?php echo $this->lang->line("Edit Campaign");?>';
	var global_lang_campaign_delete = '<?php echo $this->lang->line("Delete Campaign");?>';
	var global_lang_campaign_delete_confirmation = '<?php echo $this->lang->line("Do you really want to delete this campaign?");?>';
	var global_lang_campaign_campaign_state_confirmation = '<?php echo $this->lang->line("Do you really want to change this campaign state?");?>';
	var global_lang_campaign_name = '<?php echo $this->lang->line("Campaign Name");?>';
	var global_lang_campaign_created_successfully = '<?php echo $this->lang->line("Campaign has been created successfully.");?>';
	var global_lang_campaign_updated_successfully = '<?php echo $this->lang->line("Campaign has been updated successfully.");?>';
	var global_lang_campaign_deleted_successfully = '<?php echo $this->lang->line("Campaign has been deleted successfully.");?>';
	var global_lang_no_video_found = '<?php echo $this->lang->line("We cound not find any video");?>';

	var upload_lang_drag_drop_files = "<?php echo $this->lang->line('Drag & Drop Files');?>";
	var upload_lang_upload = "<?php echo $this->lang->line('Upload');?>";
	var upload_lang_abort = "<?php echo $this->lang->line('Abort');?>";
	var upload_lang_cancel = "<?php echo $this->lang->line('Cancel');?>";
	var upload_lang_delete = "<?php echo $this->lang->line('Delete');?>";
	var upload_lang_done = "<?php echo $this->lang->line('Done');?>";
	var upload_lang_multiple_file_drag_drop_is_not_allowed = "<?php echo $this->lang->line('Multiple File Drag & Drop is not allowed.');?>";
	var upload_lang_is_not_allowed_allowed_extensions  = "<?php echo $this->lang->line('is not allowed. Allowed extensions: ');?>";
	var upload_lang_is_not_allowed_file_already_exists = "<?php echo $this->lang->line('is not allowed. File already exists.');?>";
	var upload_lang_is_not_allowed_allowed_max_size  = "<?php echo $this->lang->line('is not allowed. Allowed Max size: ');?>";
	var upload_lang_upload_is_not_allowed = "<?php echo $this->lang->line('Upload is not allowed');?>";
	var upload_lang_is_not_allowed_maximum_allowed_files_are = "<?php echo $this->lang->line('is not allowed. Maximum allowed files are:');?>";
	var upload_lang_download = "<?php echo $this->lang->line('Download');?>";

	var support_lang_success = '<?php echo $this->lang->line("Success"); ?>';
	var support_lang_error = '<?php echo $this->lang->line("Error"); ?>';
	var support_lang_no_data_found = '<?php echo $this->lang->line("No data found"); ?>';
	var support_lang_ticket_delete_confirm = '<?php echo $this->lang->line("Do you really want to delete it?");?>';
	var support_lang_are_you_sure = '<?php echo $this->lang->line("Are you sure?");?>';


	var addon_manager_lang_alert = '<?php echo $this->lang->line("Alert");?>';
	var addon_manager_lang_deactive_addon = '<?php echo $this->lang->line("Deactive Add-on?");?>';
	var addon_manager_lang_deactive_addon_confirmation = '<?php echo $this->lang->line("Do you really want to deactive this add-on? Your add-on data will still remain.");?>';
	var addon_manager_lang_delete_addon = '<?php echo $this->lang->line("Delete Add-on?");?>';
	var addon_manager_lang_delete_addon_confirmation = '<?php echo $this->lang->line("Do you really want to delete this add-on? This process can not be undone.");?>';
	var addon_manager_lang_delete_url = '<?php echo base_url("addons/delete_uploaded_zip");?>';

	var announcement_lang_mark_seen_confirmation = '<?php echo $this->lang->line("Do you really want to mark all unseen notifications as seen?");?>';

	var user_manager_lang_not_selected = '<?php echo $this->lang->line("You have to select users to send email.");?>';
	var package_manager_lang_cannot_deleted = '<?php echo $this->lang->line("Default package can not be deleted.");?>';

	var language_manager_lang_alert1 = '<?php echo $this->lang->line("Please put a language name & then save.");?>';
	var language_manager_lang_alert2 = '<?php echo $this->lang->line("Please put a language name & save it first.");?>';
	var language_manager_lang_download = '<?php echo $this->lang->line("Download Language");?>';
	var language_manager_lang_delete = '<?php echo $this->lang->line("Delete Language");?>';
	var language_manager_lang_cannot_delete = '<?php echo $this->lang->line("Sorry, english language can not be deleted.");?>';
	var language_manager_lang_cannot_delete_default = '<?php echo $this->lang->line("This is your default language, it can not be deleted.");?>';
	var language_manager_lang_cannot_delete_confirmation = '<?php echo $this->lang->line("Delete Language?");?>';
	var language_manager_lang_cannot_delete_confirmation_msg = '<?php echo $this->lang->line("Do you really want to delete this language? It will delete all files of this language.");?>';
	var language_manager_lang_cannot_delete_success_msg = '<?php echo $this->lang->line("Your language file has been successfully deleted.");?>';
	var language_manager_lang_only_char_allowed = '<?php echo $this->lang->line("Only characters and underscores are allowed.");?>';
	var language_manager_lang_language_exist = '<?php echo $this->lang->line("Sorry, this language already exists, you can not add this again.");?>';
	var language_manager_lang_language_exist_try = '<?php echo $this->lang->line("This language is already exist, please try with different one.");?>';
	var language_manager_lang_language_exist_update = '<?php echo $this->lang->line("This language already exist, no need to update.");?>';
	var language_manager_lang_update_name_first = '<?php echo $this->lang->line("Your given name has not updated, please update the name first.");?>';
	var language_manager_lang_selected_lang = '<?php echo $this->session->userdata("selected_language");?>';
	var language_manager_lang_editable_language = '<?php echo $this->uri->segment(3);?>';

	var smtp_settings_lang_test_mail_sent = '<?php echo $this->lang->line("Test email has been sent successfully.");?>';

	var fb_settings_lang_make_active = '<?php echo $this->lang->line("Make this app active");?>';
	var fb_settings_lang_make_inactive = '<?php echo $this->lang->line("Make this app inactive");?>';
	var fb_settings_lang_add_app = '<?php echo $this->lang->line("Add App");?>';
	var fb_settings_lang_edit_app = '<?php echo $this->lang->line("Edit App");?>';
	var fb_settings_lang_change_app_state_confirmation = '<?php echo $this->lang->line("Do you really want to change this apps state?");?>';
	var fb_settings_lang_delete_app_confirmation = '<?php echo $this->lang->line("Do you really want to delete this app?");?>';
	var google_settings_lang_delete_app_confirmation = '<?php echo $this->lang->line("Do you really want to delete this app? Deleting app will delete all related channels and campaigns.");?>';

	var theme_manager_lang_activation = '<?php echo $this->lang->line("Theme Activation");?>';
	var theme_manager_lang_activation_confirmation = '<?php echo $this->lang->line("Do you really want to activate this Theme?");?>';
	var theme_manager_lang_deactivation = '<?php echo $this->lang->line("Theme Deactivation");?>';
	var theme_manager_lang_deactivation_confirmation = '<?php echo $this->lang->line("Do you really want to deactivate this Theme? Your theme data will still remain");?>';
	var theme_manager_lang_delete_confirmation = '<?php echo $this->lang->line("Do you really want to delete this Theme? This process can not be undone.");?>';


	var auto_comment_template_comment_message = '<?php echo $this->lang->line("Comment Message");?>';
	var auto_comment_template_message_for_comment = '<?php echo $this->lang->line("Message for comment");?>';
	var auto_comment_template_provide_one = '<?php echo $this->lang->line("Please provide atleast one comment message.");?>';
	var auto_comment_template_edit = '<?php echo $this->lang->line("Edit Template");?>';


	var auto_reply_campaign_waiting_gif = '<img src="'+base_url+'assets/pre-loader/full-screenshots.gif" class="width_90">';
	var auto_reply_campaign_comment_text = '<?php echo $this->lang->line("Comment Text");?>';
	var auto_reply_campaign_reply_text = '<?php echo $this->lang->line("Reply Text");?>';
	var auto_reply_campaign_filter_reply = '<?php echo $this->lang->line("Filter Reply");?>';
	var auto_reply_campaign_filter_word = '<?php echo $this->lang->line("Filter Word");?>';
	var auto_reply_campaign_message_for_filter = '<?php echo $this->lang->line("Message for filter");?>';
	var auto_reply_campaign_set_campaign_name = '<?php echo $this->lang->line("Please set the Campaign Name");?>';
	var auto_reply_campaign_provide_generic_reply = '<?php echo $this->lang->line("Please provide a message for generic reply.");?>';
	var auto_reply_campaign_provide_filter_reply= '<?php echo $this->lang->line("Please complete all the combination of filter message and response.");?>';
	var auto_reply_campaign_provide_no_match_reply = '<?php echo $this->lang->line("Please provide a message for not match found on filter message.");?>';
	var auto_reply_campaign_delete_confirmation = '<?php echo $this->lang->line("Once deleted, you will have to set reply again.");?>';
	var auto_reply_template_delete_confirmation = '<?php echo $this->lang->line("Once deleted, you will not be able to recover this template!");?>';
	var auto_reply_template_successfully_deleted = '<?php echo $this->lang->line("Successfully deleted the template!");?>';
	var auto_reply_template_delete_permission_denied = '<?php echo $this->lang->line("May be you don't have any permission to delete this template!");?>';
	var auto_reply_template_cancel_confirmation = '<?php echo $this->lang->line("Do you really want to cancel this template?");?>';
	var auto_subscription_unsubscribe_channel = '<?php echo $this->lang->line("Unsubscribe Channel");?>';
	var auto_subscription_unsubscribe_confirmation = '<?php echo $this->lang->line("Do you really want to unsubscribe this channel?");?>';
	var auto_subscription_subscribe = '<?php echo $this->lang->line("Subscribe");?>';
	var auto_subscription_subscribe_channel = '<?php echo $this->lang->line("Subscribe Channel");?>';
	var auto_subscription_subscribe_confirmation = '<?php echo $this->lang->line("Do you really want to subscribe this channel?");?>';
	var auto_subscription_unsubscribe = '<?php echo $this->lang->line("Unsubscribe");?>';

	var playlist_manager_lang_select_videos = '<?php echo $this->lang->line("Please select videos first.");?>';
	var playlist_manager_lang_create_playlist = '<?php echo $this->lang->line("Create Playlist");?>';
	var playlist_manager_lang_edit_playlist = '<?php echo $this->lang->line("Edit Playlist");?>';
	var playlist_manager_lang_we_dont_have_this = '<?php echo $this->lang->line("We do not have this information, change it or keep it as it is.");?>';
	var playlist_manager_lang_delete_confirmation = '<?php echo $this->lang->line("Do you really want to delete this playlist?");?>';
	var playlist_manager_lang_remove_confirmation = '<?php echo $this->lang->line("Do you really want to remove this video from this playlist?");?>';

	var link_wheel_lang_remove_wheel = '<?php echo $this->lang->line("Remove Link Wheel");?>';

	var channel_search_lang_enter_keyword = '<?php echo $this->lang->line("Please Enter Keyword or Channel ID");?>';
	var tag_scraper_lang_enter_video_id = '<?php echo $this->lang->line("Please Enter Video ID");?>';

	var account_list_delete_confirmation = '<?php echo $this->lang->line("Do you really want to delete this account?");?>';

	var keyword_rank_lang_didnt_set_rank = '<?php echo $this->lang->line("You did not set rank tracker for this video yet.");?>';
	var keyword_rank_lang_enter_keyword = '<?php echo $this->lang->line("Please Enter Keyword");?>';
	var keyword_rank_lang_enter_video_id = '<?php echo $this->lang->line("Please Enter Video ID");?>';
	var keyword_rank_lang_select_keyword = '<?php echo $this->lang->line("Please select keyword first");?>';

	var upload_lang_error_msg1 = '<?php echo $this->lang->line("Please provide video title");?>';
	var upload_lang_error_msg2 = '<?php echo $this->lang->line("Please select a youtube Channel");?>';
	var upload_lang_error_msg3 = '<?php echo $this->lang->line("Please select video category");?>';
	var upload_lang_error_msg4 = '<?php echo $this->lang->line("Please select video privacy type");?>';
	var upload_lang_error_msg5 = '<?php echo $this->lang->line("Please select time zone");?>';
	var upload_lang_error_msg6 = '<?php echo $this->lang->line("Please select schedule date time");?>';
	var upload_lang_error_msg7 = '<?php echo $this->lang->line("Please upload video");?>';
	var upload_lang_error_msg8 = '<?php echo $this->lang->line("This video has no title");?>';
	var upload_lang_error_msg9 = '<?php echo $this->lang->line("No title");?>';
	var upload_lang_success_msg = '<?php echo $this->lang->line("Video data has been stored successfully and will be processed at scheduled time.");?>';
	var upload_lang_update_video = '<?php echo $this->lang->line("Update Schedule Video");?>';

	

</script>