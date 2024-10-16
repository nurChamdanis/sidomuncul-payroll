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
		
		if(next > 6){
			
			previous = 0;
			next 	 = 1;
			
			$("#p6").hide();
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
			
			previous = 5;
			next 	 = 6;
			
			$("#p6").fadeToggle("slow");
			
		}
		
    });
	
}(jQuery)