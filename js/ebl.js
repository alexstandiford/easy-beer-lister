jQuery(document).ready(function(){
  jQuery('.beer-url').hover(function(){
    var bbfind = '#' + jQuery(this).attr('id') + '-popup';
    jQuery(bbfind).removeClass('hidden');
    jQuery(bbfind).css({ left: event.pageX - jQuery(bbfind).parent().offset().left });
    jQuery(bbfind).css({ top: event.pageY - jQuery(bbfind).parent().offset().top });
  },function(){
    var bbfind = '#' + jQuery(this).attr('id') + '-popup';
    jQuery(bbfind).addClass('hidden');
    });
});