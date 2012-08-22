;(function ($) {

	$.fn.expander = function () {

		return $(this).each(function () {

			var $this = $(this),
				$target = $($this.attr('href'));

			$this.click(function (e) {
				e.preventDefault();
				$target.slideToggle('fast');
			});

			$target.hide();
	
		});
	};

	$(document).ready(function () {
		$('[role=expander]').expander();
	});
		
})(window.jQuery);
