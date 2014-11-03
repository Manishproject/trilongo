(function($){

$.fn.suggested_provider=function(service_id,minPrice,maxPrice,search){

	if(typeof minPrice =='undefined' )minPrice =0;
	if(typeof maxPrice =='undefined' )maxPrice =0;
	if(typeof service_id =='undefined' )service_id =0;
	if(typeof search =='undefined' )search ='';
	var selector_id = this;
	$(selector_id).append('Please wait...');
	$.ajax({
	url:ajaxpath+'reservations/providers',
	type:'POST',
	data:{
		service_id:service_id,
		minPrice:minPrice,
		maxPrice:maxPrice,
		search:search
	},
	success:function($rs){
		$(selector_id).html($rs);
	},
	
	error:function(){
		alert('Error Occured on loading service Providers');
	}
		
	});
}	
	

$(document).ready(function(){

	if(typeof currently_in !='undefined'){ 
		var currently_in_selected = $('#ReservationCurrentlyInheader').val();
		if(currently_in_selected !=currently_in){
			 $('#ReservationCurrentlyInheader').val(currently_in).trigger('change');
		}
	}
$( "#ReservationCurrentlyInheader" ).change(function() {
 var currently_in_new = $(this).val();
 var country_name = $("#ReservationCurrentlyInheader option:selected").html()
 $.getJSON(SITE_URL+"reservations/setcurrentlocation",{'currently_in' : currently_in_new,'country_name':country_name}).done(function(res){
    
  });
  
});//.change();
 
});
})(jQuery)



