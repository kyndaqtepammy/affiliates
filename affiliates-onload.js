    /**DASHBOARD TABS */
function _class(name){
	return document.getElementsByClassName(name);
  }
   
  let tabPanes = _class("tab-header")[0].getElementsByTagName("div");
   
  for(let i=0;i<tabPanes.length;i++){
	tabPanes[i].addEventListener("click",function(){
	  _class("tab-header")[0].getElementsByClassName("active")[0].classList.remove("active");
	  tabPanes[i].classList.add("active");
	  
	  _class("tab-indicator")[0].style.top = `calc(80px + ${i*50}px)`;
	  
	  _class("tab-content")[0].getElementsByClassName("active")[0].classList.remove("active");
	  _class("tab-content")[0].getElementsByTagName("div")[i].classList.add("active");
	  
	});
  }
	 
	 jQuery(document).ready(function(){
		//get user ip address
		function ipLookUp () {
			fetch('https://ipinfo.io/json/')
				.then( res => res.json())
				.then(response => {
					console.log("Country: ", response.country);
				})
				.catch((data, status) => {
					console.log('Request failed');
				});
		  }


		  jQuery("#aff-register-btn").attr('disabled', true);
		  jQuery("#aff-user-agree").click(function() {
			 if( jQuery(this).is(':checked') ) {
				 //enable button
				 jQuery("#aff-register-btn").attr('disabled', false);
			 } else {
				jQuery("#aff-register-btn").attr('disabled', true);
			 }
		  });
		  
		  

    	//do PHP function here
    	jQuery.ajax({
    		type: 'POST',
    		url: script_params2.ajaxurl,
    		data: {
				"country": "lkljkjl",
    			action: 'addReferal'
    		}
    	})
    	.success(function(data){
    		console.log(data);
    	});
    });