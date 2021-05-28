       jQuery(document).ready(function(){
    	//do PHP function here
    	jQuery.ajax({
    		method: 'post',
    		url: script_params2.ajaxurl,
    		data: {
    			action: 'addReferal'
    		}
    	})
    	.success(function(data){
    		console.log(data);
    	});
    });
