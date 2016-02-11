$(function(){
$("#nav>li").hoverIntent(
    function(){$("ul",this).fadeIn("fast");},
    function(){$("ul",this).css({left:"-1px"}).fadeOut("fast");});
});