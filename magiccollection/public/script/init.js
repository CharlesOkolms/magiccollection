function init() {

	let table;

	$(document).ready(function () {
		console.log('hello');
		table = $('#cardslist').DataTable({
			"columns": [
				{"name": "lastUpdated"},
				{"name": "multiverseId"},
				{"name": "nameEng"},
				{"name": "nameFra"},
				{"name": "setName"},
				{"name": "rarity"},
				{"name": "cost"},
				{"name": "price"}
			],
			"language": {
				"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
			}
		});

		ws({
			action: 'getCard',
			data: {
				'name': 'Fog'
			}
		}).done(function (response) {
			console.log(response);
			let cardArray = mtgCardObjectToArray(response);
			console.log(cardArray);
			table.row.add(cardArray).draw(false);
		});
	});

}



function mtgCardObjectToArray(card){
	return [
		card.lastUpdated || '',
		card.multiverseId,
		card.nameEng,
		card.nameFra || '',
		card.setName,
		card.rarity,
		card.cost,
		card.price
	];
}

function mtgReplaceManaSymbols(str){
	
}


init();
