! function($) {
    "use strict"; 	
	
	//Flexslider	
	$('#flexslider1').flexslider({
		animation: "slide",
		slideshow: true,
		slideshowSpeed: 4000,
		namespace :'flex-',
		smoothHeight: true
	});	
	
	$('#flexslider2').flexslider({
		animation: "slide",
		slideshow: true,
		slideshowSpeed: 5000,
		namespace :'flex2-',
		smoothHeight: true,
		before: function(){
			
			var before = $(".flex2-active").html();
			$("#p"+before).hide();
			
		},
		after: function(){
			
			var next = $(".flex2-active").html();
			$("#p"+next).fadeToggle("slow");
			
		}
	});	
	
	
}(jQuery)