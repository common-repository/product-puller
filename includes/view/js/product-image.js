jQuery("#thumbnail-container img").hover(function() {
    var src = jQuery(this).attr("src");
    jQuery("#preview-enlarged img").attr("src", src);
    jQuery('.thumbnail').removeClass('focused');
    jQuery(this).addClass('focused');

});