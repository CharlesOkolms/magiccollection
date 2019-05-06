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
			action: 'getCardsList',
			data: {

			}
		}).done(function (response) {
			console.log(response.data);
			let data = [];
			for(let i in response.data){
				data.push(mtgCardObjectToArray(response.data[i]));
			}
			table.rows.add(data).draw(false);
		});
	});

}


/**
 *
 * @param card
 * @returns {*[]}
 */
function mtgCardObjectToArray(card){
	return [
		card.lastUpdate || '',
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
