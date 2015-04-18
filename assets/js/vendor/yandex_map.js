ymaps.ready(init);
var YMap;
var startPlacemark, finishPlacemark, searchPlacemark;
var pathPolyline;
var setAsStartPlacemark;
var setAsFinishPlacemark;
var deleteSearchPlacemark;
var searchControl;
var startPlacemarks = [];
var finishPlacemarks = [];
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
	
	//Отображение меток из массива на карте
	showMapPoints(YMap, map_points);

	//Изменение балуна метки результата поиска
	searchControl = YMap.controls.get('searchControl');

	searchControl.options.set('noPlacemark',true);

	/*searchControl.events.add('resultshow', function (e) {
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
	});*/

	searchControl.events.add('resultselect', function (e) {
		var results = searchControl.getResultsArray(),
            selected = e.get('index'),
            point = results[selected].geometry.getCoordinates();
        //console.log(point);
        $('#startPlacemarkValue').data('lat', point[0]).data('lon',point[1]);
        $('#startPlacemarkValue').val(results[selected].properties.get('name'));
        
        removeStartPlacemark();
		startPlacemark = createStartPlacemark(point);
		YMap.geoObjects.add(startPlacemark);
		createPolyline(startPlacemark, finishPlacemark);
		// Слушаем событие окончания перетаскивания на метке.
        startPlacemark.events.add('dragend', function () {
            // При изменении положения меток меняем линию
            createPolyline(startPlacemark, finishPlacemark);
        });
       
        //console.log(results[selected].properties.get('name'));
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


    //Построение маршрута по двум точкам
    function build_route(start_coords, finish_coords){
    	var multiRoute = new ymaps.multiRouter.MultiRoute({
	        // Описание опорных точек мультимаршрута.
	        referencePoints: [
	            start_coords,
	            finish_coords
	        ],
	        // Параметры маршрутизации.
	        params: {
	            // Ограничение на максимальное количество маршрутов, возвращаемое маршрутизатором.
	            results: 1
	        }
	    }, {
	    	// Внешний вид линии маршрута.
	        routeStrokeWidth: 2,
	        routeStrokeColor: "#000088",
	        routeActiveStrokeWidth: 6,
	        routeActiveStrokeColor: "#000088",
	        // Автоматически устанавливать границы карты так, чтобы маршрут был виден целиком.
	        boundsAutoApply: true
	    });
	    YMap.geoObjects.add(multiRoute);
    }

    $( document ).ready(function() {
	    var dep_lat, dep_lon, des_lat, des_lon; 
	    dep_lat = 56.298645360520744;
	    dep_lon = 44.02325942605588;

	    des_lat = 56.32841297776779;
	    des_lon = 43.92918899148557;

	    build_route([dep_lat, dep_lon], [des_lat, des_lon]);
	});

}

// Удаление всех меток
var removeAllPlacemarks = function() {
	YMap.geoObjects.remove(startPlacemark);
	startPlacemark = null;
	removeFinishPlacemark();

	//Перевод карты в полноэкранный режим
	//YMap.controls.get('fullscreenControl').select();
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
function showMapPoints (map, points){
	map.geoObjects.removeAll();
	points.forEach(function(item, i, arr){
		var startPoint = new ymaps.Placemark([item.dep_lat, item.dep_lon],
		{
			hintContent: '',
			iconContent: '<img style="border-radius: 50%" src="'+ item.pic_url +'">',
			
		},{
			iconLayout: 'default#imageWithContent',
			iconImageHref: "../../assets/img/map_pin.png",
			iconImageSize: [40, 40],
			iconImageOffset: [-20, -40],
			iconContentOffset: [7, 3],
			iconContentSize: [27, 27],
		}
		);
		var index = item.point_id;
		startPlacemarks[index] = startPoint;
		map.geoObjects.add(startPoint);
		startPoint.events.add('click', function(placemark, e){
			showFinishMarkById(index);
		});
		var finishPoint = new ymaps.Placemark([item.des_lat, item.des_lon],
		{
			hintContent: '',
			iconContent: '<img style="border-radius: 50%" src="'+ item.pic_url +'">',
			
		},{
			iconLayout: 'default#imageWithContent',
			iconImageHref: "../../assets/img/map_pin3.png",
			iconImageSize: [40, 40],
			iconImageOffset: [-20, -40],
			iconContentOffset: [7, 3],
			iconContentSize: [27, 27],
		}
		);
		finishPlacemarks[index] = finishPoint;
	});
}
function clearMap(){
	YMap.geoObjects.removeAll();
}
function highlightMark(item){
	var item_id = item.id;
	var mark_id = +item_id.replace('list_item_', '');
	var placemark = startPlacemarks[mark_id];
	placemark.options.set('iconImageSize',[50, 50]);
	placemark.options.set('iconImageOffset',[-25, -50]);
	placemark.options.set('iconContentOffset',[12, 7]);
	var placemark = finishPlacemarks[mark_id];
	placemark.options.set('iconImageSize',[50, 50]);
	placemark.options.set('iconImageOffset',[-25, -50]);
	placemark.options.set('iconContentOffset',[12, 7]);
}
function defaultMark(item){
	var item_id = item.id;
	var mark_id = +item_id.replace('list_item_', '');
	var placemark = startPlacemarks[mark_id];
	placemark.options.set('iconImageSize',[40, 40]);
	placemark.options.set('iconImageOffset',[-20, -40]);
	placemark.options.set('iconContentOffset',[7, 3]);
	var placemark = finishPlacemarks[mark_id];
	placemark.options.set('iconImageSize',[40, 40]);
	placemark.options.set('iconImageOffset',[-20, -40]);
	placemark.options.set('iconContentOffset',[7, 3]);
}
function defaultAllMarks(){
	startPlacemarks.forEach( function(item, i, arr){
		item.options.set('iconImageHref', '../../assets/img/map_pin.png');
	});
}
function showFinishMark(item){
	hideFinishMarks();
	defaultAllMarks();
	var item_id = item.id;
	var mark_id = +item_id.replace('list_item_', '');
	var placemark = finishPlacemarks[mark_id];
	YMap.geoObjects.add(placemark);
	placemark = startPlacemarks[mark_id];
	placemark.options.set('iconImageHref', '../../assets/img/map_pin2.png');
}
function showFinishMarkById(mark_id){
	hideFinishMarks();
	defaultAllMarks();
	var placemark = finishPlacemarks[mark_id];
	YMap.geoObjects.add(placemark);
	placemark = startPlacemarks[mark_id];
	placemark.options.set('iconImageHref', '../../assets/img/map_pin2.png');
}
function hideFinishMarks(){
	finishPlacemarks.forEach( function(item, i, arr){
		YMap.geoObjects.remove(item);
	});
}

