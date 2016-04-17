var submitData = function(){
	var urlBase =  'http://gateway-a.watsonplatform.net/calls/text/TextGetRankedKeywords?';
	var apiKey = 'e5448b915d9a6ceea36018b4afb17c00ff905552';
	urlBase = urlBase+"apikey="+apiKey;
	var titleText = $("#title").val();
	var descriptionText = $("#description").val()
	urlBase = urlBase+"&text="+encodeURIComponent(inputText)+encodeURIComponent(descriptionText);
	urlBase = urlBase+"&outputMode=json";
	console.log(urlBase);

	$.ajax({
		url: urlBase,
		type: "POST",
		success: function(data){
			console.log(data);
		}
	})
}