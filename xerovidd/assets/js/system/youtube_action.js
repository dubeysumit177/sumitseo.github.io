$(function() {
"use strict";
  $(document).ready(function() {
    var width=$(window).width();
    var a;
    var b;

    if(width<400) a=90;
    else a= 55;

    b= 9*a/16;
    var iframe_width=width*a/100;
    var iframe_height=iframe_width*b/a;

    $(".youtube").colorbox({
      iframe:true, 
      innerWidth:iframe_width, 
      innerHeight:iframe_height,
      href: function () {
        return $(this).attr("video_url");
      }
    });
  });
});