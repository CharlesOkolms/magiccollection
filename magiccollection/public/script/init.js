function init() {
	ws({
		action: 'getCard',
		data:{
			'name' : 'Fog'
		}
	}).done(function(response){
		console.log(response);
	});
}


init();
