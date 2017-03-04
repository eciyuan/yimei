
jQuery(document).ready(function() {
	
    /*
        Fullscreen background
    */
	/*$.backstretch("img/backgrounds/1.jpg");*/
    /*
        Form
    */
    $('.registration-form fieldset:first-child').fadeIn('slow');
    $('.registration-form .required').on('focus', function() {
    	$(this).removeClass('input-error');
    });
    // next step
    $('.registration-form .btn-next').on('click', function() {
    	var parent_fieldset = $(this).parents('fieldset');
    	var next_step = true;
    	//var nav1 = "<span  id='left_tab" +  "</span>";
    	parent_fieldset.find('.required').each(function() {
    		if( $(this).val() == "" ) {
    			$(this).addClass('input-error');
    			// $("#top_1").html(nav1);
    			next_step = false;
    		}
    		else {
    			$(this).removeClass('input-error');
    		}
    		if( next_step ) {
	    		parent_fieldset.fadeOut(400, function() {
		    		$(this).next().fadeIn();
		    	});
	    	}
    	});
    	
    });
    
    // previous step
    $('.registration-form .btn-previous').on('click', function() {
    	$(this).parents('fieldset').fadeOut(400, function() {
    		$(this).prev().fadeIn();
    	});
    });
    
    // submit
    $('.registration-form').on('submit', function(e) {
    	$(this).find('input[type="checkbox"]').each(function() {
    		if( $(this).val() == "" ) {
    			e.preventDefault();
    			$(this).addClass('input-error');
    		}else {
    			$(this).removeClass('input-error');
    			//成功提交数据
    			if($('#agreement').val()==""){
    				alert('必须同意本站的协议！');
    				$(this).addClass('input-error');
    			}else{
    				$("#reg-form").submit();
    			}
    			
    		}
    	});
    	
    });
    
    
});
