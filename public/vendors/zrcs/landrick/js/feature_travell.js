! function($) {
    "use strict"; 	
	
	//Flexslider	
	$('.flexslider').flexslider({
		animation: "slide",
		slideshow: false
	});
	
	var previous = 0;	
	var next 	 = 1;	

	$('.flex-next').click(function(){
    
		previous = previous + 1;
		next 	 = next + 1;
		
		if(next > 3){
			
			previous = 0;
			next 	 = 1;
			
			$("#p3").hide();
		}
		
		var next_paragraph 	= $("#p"+next);
		var previous_paragraph = $("#p"+previous);		
		
		if(next_paragraph != null){
			
			next_paragraph.fadeToggle("slow");
			
		}
		
		if(previous_paragraph != null){
			
			previous_paragraph.hide();
			
		}
		
    });
	
	$('.flex-prev').click(function(){    
		
		var next_paragraph 	= $("#p"+next);
		var previous_paragraph = $("#p"+previous);		
		
		if(next_paragraph != null){
			
			next_paragraph.hide();
			
		}
		
		if(previous_paragraph != null){
			
			previous_paragraph.fadeToggle("slow");
			
		}
		
		previous = previous - 1;
		next 	 = next - 1;
		
		if(next == 0){
			
			previous = 2;
			next 	 = 3;
			
			$("#p3").fadeToggle("slow");
			
		}
		
    });
	
}(jQuery)