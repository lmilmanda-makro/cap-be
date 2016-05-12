function ajax_search ( table, field, filter, callback ) 
{
	var serializedData = "table=" + encodeURI(table) + "&field=" +  encodeURI(field) + "&filter=" +  encodeURI(filter);
	//alert(serializedData);
	
	$("#process_img").show();
	
	$.ajax({
		url: "/desa/faq/index.php/ajax/search/",
		type: "get",
		data: serializedData,
		success: function (response, textStatus, jqXHR) {
			console.log( "ajax:success"); 
			if(callback == null){
				console.log("Callback is null. This is the response...");
				console.log(response);
				console.log(textStatus);
				console.log(jqXHR); 
			} else {
				callback(response, textStatus, jqXHR,false); 
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log( "ajax:error"); 
			console.log( "The following error occured: "+ textStatus, errorThrown);
			callback(errorThrown, textStatus, jqXHR,true); 
		},
		complete: function(){
			$('#process_img').hide();
		}
	});
}

function preloader(show)
{
  if(show) {
	$("#loader").fadeIn("slow");
  } else {
	$("#loader").fadeOut("slow");
  }
}

function ajax_json ( jsonurl, callback ) 
{
	preloader(true);
	$.ajax({
		url: jsonurl,
		type: "get",
		data: null,
		success: function (response, textStatus, jqXHR) {
			console.log( "ajax:success"); 
			if(callback == null){
				console.log("Callback is null. This is the response...");
				console.log(response);
				console.log(textStatus);
				console.log(jqXHR); 
			} else {
				callback(response, textStatus, jqXHR,false); 
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			console.log( "ajax:error"); 
			console.log( "The following error occured: "+ textStatus, errorThrown);
			callback(errorThrown, textStatus, jqXHR,true); 
		},
		complete: function(){
			preloader(false);
		}
	});
}




