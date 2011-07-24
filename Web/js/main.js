window.$P = $(document);
window.updatePageHeight = function() {
	var newHeight = $("div.body").outerHeight(true) + $(".header").outerHeight(true) + 150;
	newHeight = (newHeight > 800) ? newHeight : 800;
	if ($('.container').height() < newHeight) {
		$('.container').height(newHeight);
	}
};

/**
 * Dynamically hide/show the default input value
 *
 * @param region
 *  a HTML region to apply
 */
window.blurInput = function(region) {
	this.inputs = $(':input', region);
	$(':input', region).each(function(index){
		$(this).attr('default', $(this).val());
	})
	inputs.focus(function(e) {
		if ($(this).val() == $(this).attr('default')) {
			$(this).val('');
		}
	});
	inputs.blur(function(e) {
		if ($(this).attr('default') && $(this).val() == '') {
			$(this).val($(this).attr('default'));
		}
	});
}


$P.ready(function() {
	window.body = $("div.body");
	window.header = $(".header");

});
