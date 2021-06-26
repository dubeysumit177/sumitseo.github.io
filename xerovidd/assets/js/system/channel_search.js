"use strict";
$("document").ready(function(){

  $(document).on('click', '#search_btn', function(event) {
    event.preventDefault();

    var form_data = new FormData($("#video_search_form_data")[0]);

    var keyword = $("#keyword").val();

    if(keyword == '')
    {
      swal(global_lang_error, channel_search_lang_enter_keyword, 'error');
      return false;
    }


    $('#middle_column_content').html("");
    $("#search_btn").addClass('btn-progress');


    $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');

    $.ajax({
      type:'POST' ,
      url:base_url+"search_engine/youtube_channel_search_action",
      data: form_data,
      contentType: false,
      cache:false,
      processData: false,
      success:function(response){

        $("#search_btn").removeClass('btn-progress');
        $("#custom_spinner").html("");
        $("#middle_column_content").html(response);

      }
    });

  });
});
