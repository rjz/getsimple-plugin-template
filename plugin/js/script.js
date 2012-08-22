;(function ($) {

	// build as jQuery-ui widget
	$.widget('z.expander', {
		_create: function () {
			var selector = this.element.attr('href');
			this.element.click(function (e) {
				e.preventDefault();
				$(selector).toggle();
			});
			$(selector).hide();
		}
	});

	$(document).ready(function () {
		$('[role=expander]').expander();
	});
		
})(window.jQuery);
