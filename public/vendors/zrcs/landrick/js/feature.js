! function($) {
    "use strict"; 	
	
	//Flexslider	
	$('.flexslider').flexslider({
		animation: "slide",
		slideshow: true,
		slideshowSpeed: 5000,
		smoothHeight: true,
		before: function(){
			
			var before = $(".flex-active").html();
			$("#p"+before).hide();
			
		},
		after: function(){
			
			var next = $(".flex-active").html();
			$("#p"+next).fadeToggle("slow");
			
		}
	});	
	
}(jQuery)