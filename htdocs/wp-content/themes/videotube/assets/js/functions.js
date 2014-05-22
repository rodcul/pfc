(function($) {
  "use strict";
  jQuery(document).ready(function($){
	$(".dropdown-toggle").dropdown();
	// Stop carousel
	jQuery('.carousel').carousel({
		interval: false
	});
	// Fix placeholder
	jQuery('input, textarea').placeholder();		
  }); 
})(jQuery);
