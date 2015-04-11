ymaps.ready(init);
var YMap;
var startPlacemark, finishPlacemark, searchPlacemark;
var pathPolyline;
var setAsStartPlacemark;
var setAsFinishPlacemark;
var deleteSearchPlacemark;
var searchControl;
function init(){   
    YMap = new ymaps.Map("ymap", {
        center: [55.76, 37.64],
        zoom: 5
    });// searchControl = new ymaps.control.SearchControl({ options: {noPlacemark: true} });

	
	//YMap.controls.add(searchControl);

    ymaps.geolocation.get({
	    // Выставляем опцию для определения положения по ip
	    provider: 'yandex',
	    // Карта автоматически отцентрируется по положению пользователя.
	    mapStateAutoApply: true
	}).then(function (result) {
		//Center map on geolocation
		YMap.setCenter(result.geoObjects.get(0).geometry.getCoordinates(), 10);
	});

	

	//Изменение балуна метки результата поиска
	searchControl = YMap.controls.get('searchControl');

	searchControl.options.set('noPlacemark',true);

	searchControl.events.add('resultshow', function (e) {
		var index = e.get('index');
		searchControl.getResult(index).then(function (geoObject) {
			//var contentHeader = geoObject.properties.get('balloonContentHeader');
			//var contentBody = geoObject.properties.get('balloonContentBody');
			searchPlacemark = geoObject;
			searchPlacemark.properties.set({
				balloonContentFooter: ['<div class="row placemark_row"><div class="small-12 columns text-center">',
					'<a class="balloonStartButton" onClick="setAsStartPlacemark()">',
					'<i class="fa fa-map-marker start"></i>',
					'&nbsp; Забрать тут</a>',
					'&nbsp;&nbsp; или  &nbsp;&nbsp;',
					'<a class="balloonFinishButton" onClick="setAsFinishPlacemark()">',
					'<i class="fa fa-map-marker finish"></i>',
					'&nbsp; Подвезти сюда</a>',
					'</div> </div>',
					'<div class="row placemark_row"><div class="small-12 columns text-center">',
					'<a class="balloonDeleteButton" onClick="deleteSearchPlacemark()">',
					'<i class="fa fa-trash-o"></i> &nbsp; Удалить метку</a>',
					'</div> </div>'].join('')
			});
			//console.log(searchPlacemark);
			YMap.geoObjects.add(searchPlacemark);
			searchPlacemark.balloon.open();

		}, this);
	});

	// Слушаем клик на карте
	YMap.events.add('click', function (e) {
	    var coords = e.get('coords');

	    //Если добавлены обе метки
    	if (startPlacemark && finishPlacemark) {
    		alert("Вы уже добавили начальную и конечную метки. Для изменения положения просто перетащите их ");
    	}
    	else {
	    	//Если начальная метка создана, а конечная нет - создаем конечную
		    if (startPlacemark && !finishPlacemark) {

		        finishPlacemark = createFinishPlacemark(coords);
		        document.getElementById('destinationCoord').value = coords;
		    	YMap.geoObjects.add(finishPlacemark);

		    	createPolyline(startPlacemark, finishPlacemark);

		        // Слушаем событие окончания перетаскивания на метке.
		        finishPlacemark.events.add('dragend', function () {
		            // При изменении положения меток меняем линию

		            createPolyline(startPlacemark, finishPlacemark);
		        });
		    }

		    //Проверяем наличие начальной метки
		    else if (!startPlacemark && !finishPlacemark){

				// Если ни одна метка не создана –  создаем начальную
		        startPlacemark = createStartPlacemark(coords);
		        document.getElementById('departureCoord').value = coords;
		        YMap.geoObjects.add(startPlacemark);

		        // Слушаем событие окончания перетаскивания на метке.
		        startPlacemark.events.add('dragend', function () {
		            // При изменении положения меток меняем линию
		            createPolyline(startPlacemark, finishPlacemark);
		        });		    
		    }
		    else{
		    	//alert("ERROR: Нарушение порядка меток");
		    	//Добавлена только конечная метка - создаем начальную
		    	startPlacemark = createStartPlacemark(coords);
		    	document.getElementById('departureCoord').value = coords;
		    	YMap.geoObjects.add(startPlacemark);

		    	createPolyline(startPlacemark, finishPlacemark);

		    	// Слушаем событие окончания перетаскивания на метке.
		        startPlacemark.events.add('dragend', function () {
		            // При изменении положения меток меняем линию
		            createPolyline(startPlacemark, finishPlacemark);
		        });
		    }
		}	    
	});

	// Создание начальной метки
    function createStartPlacemark(coords) {
        return new ymaps.Placemark(coords, {
        	/*hintContent: "Начальная метка",*/
			iconContent: 'Забрать тут',
			balloonContentHeader: "Начальная метка",
        	balloonContentBody: [
            '<a class="deletePlacemark" onClick="removeAllPlacemarks()">Удалить</a>'
        ].join('')
        }, {
            preset: 'islands#greenStretchyIcon',
            /*preset: 'islands#greenDotIcon',*/
            draggable: true
            
        });
    }

	// Создание конечной метки
    function createFinishPlacemark(coords) {
        return new ymaps.Placemark(coords, {
            iconContent: 'Подвезти сюда',
            balloonContentHeader: "Конечная метка",
        	balloonContentBody: [
            '<a class="deletePlacemark" onClick="removeFinishPlacemark()">Удалить</a>'
        ].join('')
        }, {
            preset: 'islands#redStretchyIcon',
            draggable: true
        });
    }

    setAsStartPlacemark = function(){
    	if (searchPlacemark) {
    		var searchPlacemarkCoords = searchPlacemark.geometry.getCoordinates();
    		document.getElementById('departureCoord').value = searchPlacemarkCoords;
    		YMap.geoObjects.remove(searchPlacemark);
    		removeStartPlacemark();
    		startPlacemark = createStartPlacemark(searchPlacemarkCoords);
    		YMap.geoObjects.add(startPlacemark);
			createPolyline(startPlacemark, finishPlacemark);
			// Слушаем событие окончания перетаскивания на метке.
	        startPlacemark.events.add('dragend', function () {
	            // При изменении положения меток меняем линию
	            createPolyline(startPlacemark, finishPlacemark);
	        });
		}
    }

    setAsFinishPlacemark = function(){
    	if (searchPlacemark) {
    		var searchPlacemarkCoords = searchPlacemark.geometry.getCoordinates();
    		document.getElementById('destinationCoord').value = searchPlacemarkCoords;
    		YMap.geoObjects.remove(searchPlacemark);
    		removeFinishPlacemark();
    		finishPlacemark = createFinishPlacemark(searchPlacemarkCoords);
    		YMap.geoObjects.add(finishPlacemark);
    		createPolyline(startPlacemark, finishPlacemark);
    		// Слушаем событие окончания перетаскивания на метке.
	        finishPlacemark.events.add('dragend', function () {
	            // При изменении положения меток меняем линию
	            createPolyline(startPlacemark, finishPlacemark);
	        });
			
		}
    }

    deleteSearchPlacemark = function(){
    	YMap.geoObjects.remove(searchPlacemark);
		searchPlacemark = null;
    }

    // Создание/обновление ломаной линии
    function createPolyline(start_coords, finish_coords) {

    	YMap.geoObjects.remove(pathPolyline);

    	if (startPlacemark && finishPlacemark){

    		//alert(startPlacemark.geometry.getCoordinates());

	    	pathPolyline = new ymaps.Polyline([
	            // Указываем координаты вершин ломаной.
	            startPlacemark.geometry.getCoordinates(),
	            finishPlacemark.geometry.getCoordinates()
	        ], {
	            // Описываем свойства геообъекта.
	            
	        }, {
	            // Задаем опции геообъекта.
	            // Цвет линии.
	            strokeColor: "#000000",
	            // Ширина линии.
	            strokeWidth: 4,
	            // Коэффициент прозрачности.
	            strokeOpacity: 0.5,
	            strokeStyle: 'shortdash'
	        });

	    	console.log(pathPolyline);
	        YMap.geoObjects.add(pathPolyline);
	    }
    }
}

// Удаление всех меток
var removeAllPlacemarks = function() {
	YMap.geoObjects.remove(startPlacemark);
	startPlacemark = null;
	removeFinishPlacemark();

	//Перевод карты в полноэкранный режим
	YMap.controls.get('fullscreenControl').select();
}
// Удаление начальной метки
function removeStartPlacemark() {
	YMap.geoObjects.remove(startPlacemark);
	startPlacemark = null;
	YMap.geoObjects.remove(pathPolyline);
	pathPolyline = null;
}
// Удаление конечной метки
function removeFinishPlacemark() {
	YMap.geoObjects.remove(finishPlacemark);
	finishPlacemark = null;
	YMap.geoObjects.remove(pathPolyline);
	pathPolyline = null;
}

//Поиск метки по значению, введенному в форме
function searchStartPlacemarkByForm(){
	var query = jQuery("#startPlacemarkValue").val();
	
	//удалить предыдущую метку с карты
	deleteSearchPlacemark();
	searchControl.search(query);
	searchControl.getLayout().then(function (layout) {
        // Открываем панель.
        layout.openPanel();
        layout.openPopup();
    });
	/*.then(function(a) {
	    // geoObjectsArr - это массив геообъектов, содержащий результаты запроса.
	    //var geoObjectsArray = searchControl.getResultsArray();
	});;
	


	/*searchControl.events.add('load', function (event) {
        if (!event.get('skip') && searchControl.getResultsCount()) {
            searchControl.showResult(0);
        }
    });*/
}