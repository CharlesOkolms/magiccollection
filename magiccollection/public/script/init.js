function init() {
	ws({
		action: 'getCard',
		data:{
			'name' : 'Pariah'
		}
	}).done(function(response){
		console.log(response);
	});
}


// init();
