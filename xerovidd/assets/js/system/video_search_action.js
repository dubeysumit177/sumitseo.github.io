$(function() {
"use strict";
var clipboard = new ClipboardJS(".copy_class");
clipboard.on("success", function(e) {
   iziToast.success({
       title: "",
       message: global_lang_url_copied_clipbloard,

   });
   console.info("Action:", e.action);
   console.info("Text:", e.text);
   e.clearSelection();
});

clipboard.on("error", function(e) {
   console.error("Action:", e.action);
});
$(".yscroll").mCustomScrollbar({
  autoHideScrollbar:true,
  theme:"rounded-dark"
});
ScrollReveal().reveal('.samsu',{ delay: 300});
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