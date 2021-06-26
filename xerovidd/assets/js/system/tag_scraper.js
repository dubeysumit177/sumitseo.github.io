"use strict";
$("document").ready(function(){

  $(document).on('click', '#search_btn', function(event) {
    event.preventDefault();

    var form_data = new FormData($("#video_search_form_data")[0]);
    var video_id = $("#video_id").val();

    if(video_id == '')
    {
      swal(global_lang_error, tag_scraper_lang_enter_video_id, 'error');
      return false;
    }


    $('#middle_column_content').html("");
    $("#search_btn").addClass('btn-progress');

    $("#tag_download_div").html('');

    $("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');

    var video_error = '<div class="col-12 col-sm-6 col-md-6 col-lg-12" id="nodata"><div class="empty-state"><img class="img-fluid" style="height: 250px" src=" '+base_url +'/assets/img/drawkit/drawkit-nature-man-colour.svg" alt="image"><h2 class="mt-0">'+global_lang_no_video_found+'</h2><a href="" class="btn btn-outline-primary mt-4"><i class="fa fa-search"></i> '+global_lang_try_once_again+'</a></div></div>';
    $.ajax({
      type:'POST' ,
      url:base_url+"search_engine/tag_keyword_scraper_action",
      data: form_data,
      contentType: false,
      cache:false,
      processData: false,
      success:function(response){

        $("#search_btn").removeClass('btn-progress');
        $("#custom_spinner").html("");
        $("#middle_column_content").html(response);

        if(response=="0")
        {
          $("#middle_column_content").html(video_error);

        }
      }
    });

  });
});  


$("document").ready(function(){

  var video_id = $("#video_id").val();
  if(video_id !='')
    $("#search_btn").click();

});