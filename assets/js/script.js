jQuery(document).ready(function($){
	// Copy details to clipboard
	var clipboard = new ClipboardJS('.clipboard-btn');

	clipboard.on('success', function(e) {
		$( '.clipboard-btn' ).toggleClass('hidden');
		$( '.clipboard-copied-btn' ).toggleClass('hidden');

		setTimeout(function () {
			$( '.clipboard-btn' ).toggleClass('hidden');
			$( '.clipboard-copied-btn' ).toggleClass('hidden');
		}, 1500);

		e.clearSelection();
	});
});
