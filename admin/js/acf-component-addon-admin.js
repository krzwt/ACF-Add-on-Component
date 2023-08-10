(function ($) {
	'use strict';
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	document.addEventListener('DOMContentLoaded', function () {
		$('.acf-field-my-accordion .acf-accordion-title').on('click', function () {
			$(this).parents('.acf-row').siblings().find('.acf-accordion.-open .acf-accordion-title').trigger('click');
		});
		$(".acf-field-select-component .acf-checkbox-list").each(function (index, group) {
			const $group = $(group);
			const $selectAllCheckbox = $group.find("li:first-child input[type=checkbox]");
			const $checkboxes = $group.find("li input[type=checkbox]");
			const $counter = $group.parents('.acf-field-my-accordion').find('.acf-accordion-title label');
			const counterText = $counter.text();
			$selectAllCheckbox.on("change", function () {
				$checkboxes.prop("checked", $selectAllCheckbox.prop("checked"));
				updateCounter($group, $checkboxes, $counter);
			});

			$checkboxes.on("change", function () {
				updateCounter($group, $checkboxes, $counter);
			});

			function updateCounter(group, checkboxes, counter) {
				const selectedCount = checkboxes.filter(":checked").not($selectAllCheckbox).length;
				counter.text(counterText + " (" + selectedCount + ")");
			}
			updateCounter($group, $checkboxes, $counter);
		});
	});
})(jQuery);