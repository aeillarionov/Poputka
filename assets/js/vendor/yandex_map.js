ymaps.ready(init);
var YMap;
var startPlacemark, finishPlacemark, searchPlacemark;
var multiRoute;
var pathPolyline;
var setAsStartPlacemark;
var setAsFinishPlacemark;
var deleteSearchPlacemark;
var searchControl;
var startPlacemarks = [];
var finishPlacemarks = [];

var is_finish_placemark = false;

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
	searchControl.options.set('provider', 'yandex#search');

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
        
        //Checks is finish placemark is searching from the input form 
        if (is_finish_placemark) {
        	removeFinishPlacemark();
        	createFinishPlacemark(point);
        	YMap.geoObjects.add(finishPlacemark);
        	is_finish_placemark = false;
        	$("#destinationCoord").val(point[0]+","+point[1]);
        	$('#finishPlacemarkValue').val(results[selected].properties.get('name'));


        } else {
        	removeStartPlacemark();
        	createStartPlacemark(point);
        	YMap.geoObjects.add(startPlacemark);
        	$("#departureCoord").val(point[0]+","+point[1]);
        	$('#startPlacemarkValue').val(results[selected].properties.get('name'));

        }

        build_route(startPlacemark, finishPlacemark );  
	});

	// Слушаем клик на карте
	YMap.events.add('click', function (e) {
	    var cl_coords = e.get('coords');
	    //Delete previous search placemark
	    deleteSearchPlacemark();

	    searchPlacemark = new ymaps.Placemark(cl_coords, {
	    	balloonContentHeader: 'Добавить точку к маршруту',
			balloonContentBody: [
				'<p style="margin-bottom:5px;"><a class="balloonStartButton" onClick="setAsStartPlacemark()">',
				'<i class="fa fa-flag-o"></i>',
				'&nbsp; Отсюда</a>',
				'&nbsp;&nbsp; или  &nbsp;&nbsp; ',
				'<a class="balloonFinishButton" onClick="setAsFinishPlacemark()">',
				'<i class="fa fa-flag-checkered"></i>',
				'&nbsp; Сюда</a></p>'
				].join(''),
			balloonContentFooter: [
				'<a class="balloonDeleteButton deletePlacemark" onClick="deleteSearchPlacemark()">',
				'<i class="fa fa-trash-o"></i> &nbsp; Удалить метку</a>'
	        ].join('')
		});
		//console.log(searchPlacemark);
		YMap.geoObjects.add(searchPlacemark);
		searchPlacemark.balloon.open();

	});

	//Reverse geocoding by placemark and updating information in form
	function getAddress(placemark){
		var placemark_coord = placemark.geometry.getCoordinates();
		ymaps.geocode(placemark_coord).then(function (res) {
			var firstGeoObject = res.geoObjects.get(0);

			//Detect what placemark is geocoding
			if (placemark_coord==startPlacemark.geometry.getCoordinates()){
				$("#departureCoord").val(placemark_coord[0]+","+placemark_coord[1]);
        		$('#startPlacemarkValue').val( firstGeoObject.properties.get('name') );

        		startPlacemark.properties.set({
        			balloonContentBody: firstGeoObject.properties.get('text')
        		})
			} else if ( placemark_coord==finishPlacemark.geometry.getCoordinates() ) {
				$("#destinationCoord").val(placemark_coord[0]+","+placemark_coord[1]);
        		$('#finishPlacemarkValue').val( firstGeoObject.properties.get('name') );

        		finishPlacemark.properties.set({
        			balloonContentBody: firstGeoObject.properties.get('text')
        		})
			} else {
				console.log("Invalid placemark parameter");
				console.log(placemark);
			}

		});
	}

	// Создание начальной метки
    function createStartPlacemark(coords) {
    	startPlacemark = new ymaps.Placemark(coords, {
        	/*hintContent: "Начальная метка",*/
			iconContent: 'Отсюда',
			balloonContentHeader: "Начальная точка",
			balloonContentBody: 'Адрес',
        	balloonContentFooter: [
            '<a class="deletePlacemark" onClick="removeStartPlacemark()"><i class="fa fa-trash-o"></i> &nbsp;Удалить точку</a>'
        ].join('')
        }, {
            preset: 'islands#greenStretchyIcon',
            /*preset: 'islands#greenDotIcon',*/
            draggable: true
            
        });

    	 getAddress(startPlacemark);

    	// Слушаем событие окончания перетаскивания на метке.
        startPlacemark.events.add('dragend', function () {
            //Get placemark address after drag and set placemark values in form
            getAddress(startPlacemark);
            // При изменении положения меток меняем линию
            build_route(startPlacemark, finishPlacemark );
        });

        build_route(startPlacemark, finishPlacemark );

    }

	// Создание конечной метки
    function createFinishPlacemark(coords) {

        finishPlacemark = new ymaps.Placemark(coords, {
            iconContent: 'Сюда',
            balloonContentHeader: "Конечная точка",
            balloonContentBody: 'Адрес',
        	balloonContentFooter: [
            '<a class="deletePlacemark" onClick="removeFinishPlacemark()"><i class="fa fa-trash-o"></i> &nbsp;Удалить точку</a>'
        ].join('')
        }, {
            preset: 'islands#redStretchyIcon',
            draggable: true
        });

     	getAddress(finishPlacemark);

    	// Слушаем событие окончания перетаскивания на метке.
        finishPlacemark.events.add('dragend', function () {
            //Get placemark address after drag and set placemark values in form
            getAddress(finishPlacemark);
            // При изменении положения меток меняем линию
            build_route(startPlacemark, finishPlacemark );
        });

        build_route(startPlacemark, finishPlacemark );

    }

    setAsStartPlacemark = function(){
    	if (searchPlacemark) {
    		var searchPlacemarkCoords = searchPlacemark.geometry.getCoordinates();
    		YMap.geoObjects.remove(searchPlacemark);
    		removeStartPlacemark();
    		YMap.geoObjects.remove(startPlacemark);
    		createStartPlacemark(searchPlacemarkCoords);
    		YMap.geoObjects.add(startPlacemark);
		}
    }

    setAsFinishPlacemark = function(){
    	if (searchPlacemark) {
    		var searchPlacemarkCoords = searchPlacemark.geometry.getCoordinates();
    		YMap.geoObjects.remove(searchPlacemark);
    		removeFinishPlacemark();
    		createFinishPlacemark(searchPlacemarkCoords);
    		YMap.geoObjects.add(finishPlacemark);
		}
    }

    deleteSearchPlacemark = function(){
    	YMap.geoObjects.remove(searchPlacemark);
		searchPlacemark = null;
    }

    /***
		DEPRECATED with build_route function
		Creates / updates dashed polyline of the route 
    ***/
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

	        YMap.geoObjects.add(pathPolyline);
	    }
    }


    //Построение маршрута по двум точкам
    function build_route(start_placemark, finish_placemark){

    	if (start_placemark && finish_placemark){

    		
    		//Remove previous multiroute from the map
    		YMap.geoObjects.remove(multiRoute);

	    	multiRoute = new ymaps.multiRouter.MultiRoute({
		        // Описание опорных точек мультимаршрута.
		        referencePoints: [
		            start_placemark,
		            finish_placemark
		        ],
		        // Параметры маршрутизации.
		        params: {
		            // Ограничение на максимальное количество маршрутов, возвращаемое маршрутизатором.
		            results: 1
		        }
		    }, {
		    	wayPointStart: startPlacemark,
		    	// Внешний вид линии маршрута.
		        routeStrokeWidth: 2,
		        routeStrokeColor: "#000088",
		        routeActiveStrokeWidth: 6,
		        routeActiveStrokeColor: "#000088",
		        // Автоматически устанавливать границы карты так, чтобы маршрут был виден целиком.
		        boundsAutoApply: true
		    });

		    YMap.geoObjects.add(multiRoute);

		    // Подписываемся на события модели мультимаршрута.
		    multiRoute.model.events.once("requestsuccess", function () {
		    	var first_route_point = multiRoute.getWayPoints().get(0);
	            var last_route_point = multiRoute.getWayPoints().get(1);
	            //Hide first and last points from map
	            first_route_point.options.set('visible', false);
	            last_route_point.options.set('visible', false);
	            // Создаем балун у метки второй точки.
	            //ymaps.geoObject.addon.balloon.get(last_route_point);
	            /*last_route_point.options.set({
	                preset: "islands#grayStretchyIcon",
	                iconContentLayout: ymaps.templateLayoutFactory.createClass(
	                    '<span style="color: red;">Я</span>ндекс'
	                ),
	                balloonContentLayout: ymaps.templateLayoutFactory.createClass(
	                    '{{ properties.address|raw }}'
	                )
	            });*/
	        });

		    /*
			multiRoute.model.events
			    .add("requestsuccess", function (event) {
			        var way_points = event.get("target").getReferencePoints();
			        console.log(way_points);
			        var last_point = way_points.getLength() - 1;
			        //Hide default multiroute waypoints
			        way_points.get(0).options.set('visible', false);
		    		way_points.get(last_point).options.set('visible', false);
			    })
			    .add("requestfail", function (event) {
			        console.log("Ошибка: " + event.get("error").message);
			    });
			*/
	    	/*var way_points = multiRoute.getWayPoints();
	    	var last_point = way_points.getLength() - 1; 
	    	//Hide first and last points from map
	    	console.log(way_points);
	    	console.log(last_point);
	    	console.log(way_points.get(0));
		    way_points.get(0).options.set('visible', false);
		    way_points.get(last_point).options.set('visible', false);*/

		    

		}
    }

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

	//Clear form inputs
	$("#departureCoord").val("");
	$('#startPlacemarkValue').val("");


	YMap.geoObjects.remove(pathPolyline);
	pathPolyline = null;
}
// Удаление конечной метки
function removeFinishPlacemark() {
	YMap.geoObjects.remove(finishPlacemark);
	finishPlacemark = null;

	//Clear form inputs
	$("#destinationCoord").val("");
	$('#finishPlacemarkValue').val("");

	YMap.geoObjects.remove(pathPolyline);
	pathPolyline = null;
}

//Поиск начальной метки по значению, введенному в форме
function searchStartPlacemarkByForm(){
	is_finish_placemark = false;
	var query = jQuery("#startPlacemarkValue").val();	
	searchAddress(query);
}

//Поиск конечной метки по значению, введенному в форме
function searchFinishPlacemarkByForm(){
	is_finish_placemark = true;
	var query = jQuery("#finishPlacemarkValue").val();	
	searchAddress(query);
}

/***
	Form input information handler 
	and correspondance to the Yandex Map
	Requires jQuery
**/
$( document ).ready(function() {
	//Search starts with the mouse focused on the form and Enter key pressed
	$("#startPlacemarkValue").keyup(function (e) {
	    if(e.which == 13) {
	    	is_finish_placemark = false;
	        searchAddress( $("#startPlacemarkValue").val() );    
	    } 
	});

	$("#finishPlacemarkValue").keyup(function (e) {
	    if(e.which == 13) {
	    	is_finish_placemark = true;
	        searchAddress( $("#finishPlacemarkValue").val() );    
	    } 
	});

	$(".submit_btn").click(function(){
		$(this).closest('form').submit();
	});  

});   
// + searchStartPlacemarkByForm() from yandex_map.js


function searchAddress(query_address){
	searchControl.search(query_address);
	searchControl.getLayout().then(function (layout) {
        // Открываем панель.
        layout.openPanel();
        layout.openPopup();
    });
}



function showMapPoints (map, points){
	points.forEach(function(item, i, arr){
		var img_src = '';
		if(item.point_type == 0){
			img_src = "../../assets/img/req_pin.png";
		} else if(item.point_type == 1) {
			img_src = "../../assets/img/route_pin.png";
		}
		var startPoint = new ymaps.Placemark([item.dep_lat, item.dep_lon],
		{
			hintContent: '',
			iconContent: '<img style="border-radius: 3px" src="'+ item.pic_url +'">',
			
		},{
			iconLayout: 'default#imageWithContent',
			iconImageHref: img_src,
			iconImageClipRect: [[0, 0], [50, 50]],
			iconImageSize: [50, 50],
			iconImageOffset: [-25, -50],
			iconContentOffset: [21, 7],
			iconContentSize: [27, 27],
		});
		var index = item.point_id;
		startPlacemarks[index] = startPoint;
		map.geoObjects.add(startPoint);
		startPoint.events.add('click', function(placemark, e){ //Событие клика на начальной метке
			showFinishMarkById(index);
		});
		var finishPoint = new ymaps.Placemark([item.des_lat, item.des_lon],
		{
			hintContent: '',
			//iconContent: '<img style="border-radius: 50%" src="'+ item.pic_url +'">',
			
		},{
			iconLayout: 'default#imageWithContent',
			iconImageHref: "../../assets/img/fin_pin.png",
			iconImageSize: [40, 40],
			iconImageOffset: [-25, -40]
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
	placemark.options.set('iconImageClipRect', [[0, 50], [60, 110]]);
	placemark.options.set('iconImageSize',[60, 60]);
	placemark.options.set('iconImageOffset',[-30, -60]);
	placemark.options.set('iconContentOffset',[28, 11]);
}
function defaultMark(item){
	var item_id = item.id;
	var mark_id = +item_id.replace('list_item_', '');
	var placemark = startPlacemarks[mark_id];
	placemark.options.set('iconImageClipRect', [[0, 0], [50, 50]]);
	placemark.options.set('iconImageSize',[50, 50]);
	placemark.options.set('iconImageOffset',[-25, -50]);
	placemark.options.set('iconContentOffset',[21, 7]);
}
function defaultAllMarks(){
	startPlacemarks.forEach( function(item, i, arr){
		item.options.set('iconImageClipRect', [[0, 0], [50, 50]]);
	});
}
function showFinishMark(item){
	hideFinishMarks();
	defaultAllMarks();
	var item_id = item.id;
	var mark_id = +item_id.replace('list_item_', '');
	var finish_placemark = finishPlacemarks[mark_id];
	YMap.geoObjects.add(finish_placemark);
	var start_placemark = startPlacemarks[mark_id];
	//start_placemark.options.set('iconImageHref', '../../assets/img/map_pin2.png');
	YMap.geoObjects.remove(multiRoute);

	    	multiRoute = new ymaps.multiRouter.MultiRoute({
		        // Описание опорных точек мультимаршрута.
		        referencePoints: [
		            start_placemark,
		            finish_placemark
		        ],
		        // Параметры маршрутизации.
		        params: {
		            // Ограничение на максимальное количество маршрутов, возвращаемое маршрутизатором.
		            results: 1
		        }
		    }, {
		    	wayPointStart: startPlacemark,
		    	// Внешний вид линии маршрута.
		        routeStrokeWidth: 2,
		        routeStrokeColor: "#000088",
		        routeActiveStrokeWidth: 6,
		        routeActiveStrokeColor: "#000088",
		        // Автоматически устанавливать границы карты так, чтобы маршрут был виден целиком.
		        boundsAutoApply: true
		    });

		    YMap.geoObjects.add(multiRoute);
		    
		    // Подписываемся на события модели мультимаршрута.
		    multiRoute.model.events.once("requestsuccess", function () {
		    	var first_route_point = multiRoute.getWayPoints().get(0);
	            var last_route_point = multiRoute.getWayPoints().get(1);
	            //Hide first and last points from map
	            first_route_point.options.set('visible', false);
	            last_route_point.options.set('visible', false);
	            // Создаем балун у метки второй точки.
	            //ymaps.geoObject.addon.balloon.get(last_route_point);
	            /*last_route_point.options.set({
	                preset: "islands#grayStretchyIcon",
	                iconContentLayout: ymaps.templateLayoutFactory.createClass(
	                    '<span style="color: red;">Я</span>ндекс'
	                ),
	                balloonContentLayout: ymaps.templateLayoutFactory.createClass(
	                    '{{ properties.address|raw }}'
	                )
	            });*/
	        });
}
function showFinishMarkById(mark_id){
	hideFinishMarks();
	defaultAllMarks();
	var finish_placemark = finishPlacemarks[mark_id];
	YMap.geoObjects.add(finish_placemark);
	var start_placemark = startPlacemarks[mark_id];
	//start_placemark.options.set('iconImageHref', '../../assets/img/map_pin2.png');
	YMap.geoObjects.remove(multiRoute);

	    	multiRoute = new ymaps.multiRouter.MultiRoute({
		        // Описание опорных точек мультимаршрута.
		        referencePoints: [
		            start_placemark,
		            finish_placemark
		        ],
		        // Параметры маршрутизации.
		        params: {
		            // Ограничение на максимальное количество маршрутов, возвращаемое маршрутизатором.
		            results: 1
		        }
		    }, {
		    	wayPointStart: startPlacemark,
		    	// Внешний вид линии маршрута.
		        routeStrokeWidth: 2,
		        routeStrokeColor: "#000088",
		        routeActiveStrokeWidth: 6,
		        routeActiveStrokeColor: "#000088",
		        // Автоматически устанавливать границы карты так, чтобы маршрут был виден целиком.
		        boundsAutoApply: true
		    });

		    YMap.geoObjects.add(multiRoute);
		    
		    // Подписываемся на события модели мультимаршрута.
		    multiRoute.model.events.once("requestsuccess", function () {
		    	var first_route_point = multiRoute.getWayPoints().get(0);
	            var last_route_point = multiRoute.getWayPoints().get(1);
	            //Hide first and last points from map
	            first_route_point.options.set('visible', false);
	            last_route_point.options.set('visible', false);
	        });
}
function hideFinishMarks(){
	finishPlacemarks.forEach( function(item, i, arr){
		YMap.geoObjects.remove(item);
	});
}

function showNearestRequests() {
    clearMap();
    var geolocation = ymaps.geolocation;

    geolocation.get({
        provider: 'browser',
        mapStateAutoApply: false
    }).then(function (result) {
        // Синим цветом пометим положение, полученное через браузер.
        // Если браузер не поддерживает эту функциональность, метка не будет добавлена на карту.
        result.geoObjects.options.set('preset', 'islands#geolocationIcon');
        YMap.geoObjects.add(result.geoObjects);
        YMap.setCenter(result.geoObjects.get(0).geometry.getCoordinates(), 12);
        var myCoords = result.geoObjects.get(0).geometry.getCoordinates();
        var string = myCoords[0]+'-'+myCoords[1];
        string = string.replace(/\./g,'_');
        $('#list_view').load('showNearestRequests/'+string);
    });
}

function showNearestRoutes(){
	clearMap();
    var geolocation = ymaps.geolocation;

    geolocation.get({
        provider: 'browser',
        mapStateAutoApply: false
    }).then(function (result) {
        // Синим цветом пометим положение, полученное через браузер.
        // Если браузер не поддерживает эту функциональность, метка не будет добавлена на карту.
        result.geoObjects.options.set('preset', 'islands#geolocationIcon');
        YMap.geoObjects.add(result.geoObjects);
        var myCoords = result.geoObjects.get(0).geometry.getCoordinates();
        var string = myCoords[0]+'-'+myCoords[1];
        string = string.replace(/\./g,'_');
        $('#list_view').load('showNearestRoutes/'+string);
    });
}

/*function showOnroadRequests(route){
	clearMap();
	var rad2deg = 180/Math.PI;
	var onroad = [];
	var area_coords = [];
	var route_start = new ymaps.Placemark([route['dep_lat'], route['dep_lon']]);
	var route_finish = new ymaps.Placemark([route['des_lat'], route['des_lon']]);
	var multiRoute = new ymaps.multiRouter.MultiRoute({
		        // Описание опорных точек мультимаршрута.
		        referencePoints: [
		            route_start,
		            route_finish,
		        ],
		        params: {
		            results: 1
		        }
		    }, {
		    	wayPointStart: startPlacemark,
		        routeStrokeWidth: 2,
		        routeStrokeColor: "#008800",
		        routeActiveStrokeWidth: 6,
		        routeActiveStrokeColor: "#008800",
		        boundsAutoApply: true
		    });
		    YMap.geoObjects.add(multiRoute);
		    multiRoute.model.events.once("requestsuccess", function () {
		    	var first_route_point = multiRoute.getWayPoints().get(0);
	            var last_route_point = multiRoute.getWayPoints().get(1);
	            //Hide first and last points from map
	            first_route_point.options.set('visible', false);
	            last_route_point.options.set('visible', false);
	        });
	var route_dep_lat = route['dep_lat'];
	var route_dep_lon = route['dep_lon'];
	var route_des_lat = route['des_lat'];
	var route_des_lon = route['des_lon'];
	var vector_length, vector_tg, vert_shift, hor_shift, route_angle; //Угол считается от вертикали по часовой стрелке
	var tg10 = 0.17632698;
	var tg15 = 0.26794919;
	if(route_des_lon > route_dep_lon){
		if(route_des_lat > route_dep_lat){ //Маршрут направлен вправо и вверх
			vector_length = Math.sqrt(Math.pow(route_des_lat - route_dep_lat, 2) + Math.pow(route_des_lon - route_dep_lon, 2));
			vector_tg = (route_des_lat - route_dep_lat)/(route_des_lon - route_dep_lon);
			vert_shift = (vector_length * tg10)/Math.sqrt(1 + vector_tg);
			hor_shift = vert_shift*vector_tg;
			area_coords = [
				[route_dep_lat, route_dep_lon],
				//[route_dep_lat + 0.3*vert_shift, route_dep_lon - hor_shift],
				//[route_dep_lat - 0.3*vert_shift, route_dep_lon + hor_shift],
				[route_des_lat - vert_shift, route_des_lon + 3*hor_shift],
				[route_des_lat + vert_shift, route_des_lon - 3*hor_shift],
			];
			route_angle = Math.atan(vector_tg) * rad2deg;
			route_angle = 90 - route_angle;
		} else { //Маршрут направлен вправо и вниз
			vector_length = Math.sqrt(Math.pow(route_dep_lat - route_des_lat, 2) + Math.pow(route_des_lon - route_dep_lon, 2));
			vector_tg = (route_dep_lat - route_des_lat)/(route_des_lon - route_dep_lon);
			vert_shift = (vector_length * tg10)/Math.sqrt(1 + vector_tg);
			hor_shift = vert_shift*vector_tg;
			area_coords = [
				[route_dep_lat, route_dep_lon],
				//[route_dep_lat + 0.3*vert_shift, route_dep_lon + hor_shift],
				//[route_dep_lat - 0.3*vert_shift, route_dep_lon - hor_shift],
				[route_des_lat - vert_shift, route_des_lon - 3*hor_shift],
				[route_des_lat + vert_shift, route_des_lon + 3*hor_shift],
			];
			route_angle = Math.atan(vector_tg) * rad2deg;
			route_angle = 90 + route_angle;
		}
	} else {
		if(route_des_lat > route_dep_lat){ //Маршрут направлен влево и вверх
			vector_length = Math.sqrt(Math.pow(route_des_lat - route_dep_lat, 2) + Math.pow(route_dep_lon - route_des_lon, 2));
			vector_tg = (route_des_lat - route_dep_lat)/(route_dep_lon - route_des_lon);
			vert_shift = (vector_length * tg10)/Math.sqrt(1 + vector_tg);
			hor_shift = vert_shift*vector_tg;
			area_coords = [
				[route_dep_lat, route_dep_lon],
				//[route_dep_lat + 0.3*vert_shift, route_dep_lon + hor_shift],
				//[route_dep_lat - 0.3*vert_shift, route_dep_lon - hor_shift],
				[route_des_lat - vert_shift, route_des_lon - 3*hor_shift],
				[route_des_lat + vert_shift, route_des_lon + 3*hor_shift],
			];
			route_angle = Math.atan(vector_tg) * rad2deg;
			route_angle = 270 + route_angle;
		} else { //Маршрут направлен влево и вниз
			vector_length = Math.sqrt(Math.pow(route_dep_lat - route_des_lat, 2) + Math.pow(route_dep_lon - route_des_lon, 2));
			vector_tg = (route_dep_lat - route_des_lat)/(route_dep_lon - route_des_lon);
			vert_shift = (vector_length * tg10)/Math.sqrt(1 + vector_tg);
			hor_shift = vert_shift*vector_tg;
			area_coords = [
				[route_dep_lat, route_dep_lon],
				//[route_dep_lat + 0.3*vert_shift, route_dep_lon - hor_shift],
				//[route_dep_lat - 0.3*vert_shift, route_dep_lon + hor_shift],
				[route_des_lat - vert_shift, route_des_lon + 3*hor_shift],
				[route_des_lat + vert_shift, route_des_lon - 3*hor_shift],
			];
			route_angle = Math.atan(vector_tg) * rad2deg;
			route_angle = 270 - route_angle;
		}
	}
	var area = new ymaps.Polygon(
			[
				area_coords,
			],
	{
		fillColor: '#000088',
		strokeColor: '#000088',
		strokeWidth: 2,
	});
	area.options.set('visible', true);
	YMap.geoObjects.add(area);
	var req_tg, req_angle;
	map_points.forEach(function(item, i, arr){
		var request = ymaps.route([[item['dep_lat'], item['dep_lon']], [item['des_lat'], item['des_lon']]]).then(
				function (res) {
					//Определяем, куда направлен пешеходный маршрут (в какую из 4-х четвертей попадает), присваиваем один из 4-х quarter
					if(item['des_lon'] > item['dep_lon']){
						if(item['des_lat'] > item['dep_lat']){
							req_tg = (item['des_lat'] - item['dep_lat'])/(item['des_lon'] - item['dep_lon']);
							req_angle = Math.atan(req_tg) * rad2deg;
							req_angle = 90 - req_angle;
						} else {
							req_tg = (item['dep_lat'] - item['des_lat'])/(item['des_lon'] - item['dep_lon']);
							req_angle = Math.atan(req_tg) * rad2deg;
							req_angle = 90 + req_angle;
						}
					} else {
						if(item['des_lat'] > item['dep_lat']){
							req_tg = (item['des_lat'] - item['dep_lat'])/(item['dep_lon'] - item['des_lon']);
							req_angle = Math.atan(req_tg) * rad2deg;
							req_angle = 270 + req_angle;
						} else {
							req_tg = (item['dep_lat'] - item['des_lat'])/(item['dep_lon'] - item['des_lon']);
							req_angle = Math.atan(req_tg) * rad2deg;
							req_angle = 270 - req_angle;
						}
					}
					// Объединим в выборку все сегменты маршрута.
					var pathsObjects = ymaps.geoQuery(res.getPaths()),
						edges = [];
					// Переберем все сегменты и разобьем их на отрезки.
					pathsObjects.each(function (path) {
						var coordinates = path.geometry.getCoordinates();
						for (var i = 1, l = coordinates.length; i < l; i++) {
							edges.push({
								type: 'LineString',
								coordinates: [coordinates[i], coordinates[i - 1]]
							});
						}
					});
				
					// Создадим новую выборку, содержащую:
					// - отрезки, описываюшие маршрут;
					// - начальную и конечную точки;
					// - промежуточные точки.
					var routeObjects = ymaps.geoQuery(edges)
							.add(res.getWayPoints())
							.add(res.getViaPoints())
							.setOptions('visible', false)
							.addToMap(YMap),
						// Найдем все объекты, попадающие внутрь области.
						objectsInArea = routeObjects.searchInside(area),
						// Найдем объекты, пересекающие область.
						boundaryObjects = routeObjects.searchIntersect(area);
					var inside = 0;
					objectsInArea.search('geometry.type = "LineString"').each(function (s) {
						inside += s.geometry.getDistance(); //Длина участка пересечения
					});
					//alert('ID: '+ item['point_id'] +'; Req angl: '+ req_angle +'; Lim1 '+ ((360 + route_angle - 35)%360) + '; Lim2: '+ ((route_angle + 35)%360));
					//Процент пересечения по отношению ко всему маршруту должен быть не менее 60%,
					//а направления маршрутов не должны отличаться более чем на 35 град в каждую сторону
					if( (inside / res.getLength() >= 0.6) && ( (Math.abs(route_angle - req_angle)<=35) || (Math.abs(route_angle - req_angle)>=325) ) ){
						onroad.push(item); //При выполнении условий добавляем пешеходный маршрут для отображения
					}
					if(i + 1 == arr.length){
						setTimeout(function(){showMapPoints(YMap, onroad); showList(onroad);}, 200);
					}
				}
			);
			
		});
}*/
/*function showOnroadRoutes(request){
	clearMap();
	var rad2deg = 180/Math.PI;
	var onroad = [];
	var area_coords = [];
	var request_start = new ymaps.Placemark([request['dep_lat'], request['dep_lon']]);
	var request_finish = new ymaps.Placemark([request['des_lat'], request['des_lon']]);
	//Добавили на карту пешеходный маршрут, для которого будем искать попутные автомобильные
	var multiRoute = new ymaps.multiRouter.MultiRoute({
		// Описание опорных точек мультимаршрута.
		referencePoints: [
			request_start,
			request_finish,
		],
		params: {
			results: 1
		}
	}, {
		wayPointStart: startPlacemark,
		routeStrokeWidth: 2,
		routeStrokeColor: "#008800",
		routeActiveStrokeWidth: 6,
		routeActiveStrokeColor: "#008800",
		boundsAutoApply: true
	});
	YMap.geoObjects.add(multiRoute);
	multiRoute.model.events.once("requestsuccess", function () {
		var first_route_point = multiRoute.getWayPoints().get(0);
		var last_route_point = multiRoute.getWayPoints().get(1);
		//Hide first and last points from map
		first_route_point.options.set('visible', false);
		last_route_point.options.set('visible', false);
	});
	var vector_length, vector_tg, vert_shift, hor_shift, req_tg, req_angle;
	var tg10 = 0.17632698;
	var tg15 = 0.26794919;
	//Определяем, куда направлен пешеходный маршрут, считаем угол
	if(request['des_lon'] > request['dep_lon']){
		if(request['des_lat'] > request['dep_lat']){
			req_tg = (request['des_lat'] - request['dep_lat'])/(request['des_lon'] - request['dep_lon']);
			req_angle = Math.atan(req_tg) * rad2deg;
			req_angle = 90 - req_angle;
		} else {
			req_tg = (request['dep_lat'] - request['des_lat'])/(request['des_lon'] - request['dep_lon']);
			req_angle = Math.atan(req_tg) * rad2deg;
			req_angle = 90 + req_angle;
		}
	} else {
		if(request['des_lat'] > request['dep_lat']){
			req_tg = (request['des_lat'] - request['dep_lat'])/(request['dep_lon'] - request['des_lon']);
			req_angle = Math.atan(req_tg) * rad2deg;
			req_angle = 270 + req_angle;
		} else {
			req_tg = (request['dep_lat'] - request['des_lat'])/(request['dep_lon'] - request['des_lon']);
			req_angle = Math.atan(req_tg) * rad2deg;
			req_angle = 270 - req_angle;
		}
	}
	var route_dep_lat, route_dep_lon, route_des_lat, route_des_lon;
	map_points.forEach(function(item, i, arr){
		route_dep_lat = item['dep_lat'];
		route_dep_lon = item['dep_lon'];
		route_des_lat = item['des_lat'];
		route_des_lon = item['des_lon'];
		var route_angle;
		if(route_des_lon > route_dep_lon){
			if(route_des_lat > route_dep_lat){ //Маршрут направлен вправо и вверх
				vector_length = Math.sqrt(Math.pow(route_des_lat - route_dep_lat, 2) + Math.pow(route_des_lon - route_dep_lon, 2));
				vector_tg = (route_des_lat - route_dep_lat)/(route_des_lon - route_dep_lon);
				vert_shift = (vector_length * tg10)/Math.sqrt(1 + vector_tg);
				hor_shift = vert_shift*vector_tg;
				area_coords = [
					[route_dep_lat, route_dep_lon],
					//[route_dep_lat + 0.3*vert_shift, route_dep_lon - hor_shift],
					//[route_dep_lat - 0.3*vert_shift, route_dep_lon + hor_shift],
					[route_des_lat - vert_shift, route_des_lon + 3*hor_shift],
					[route_des_lat + vert_shift, route_des_lon - 3*hor_shift],
				];
				route_angle = Math.atan(vector_tg) * rad2deg;
				route_angle = 90 - route_angle;
			} else { //Маршрут направлен вправо и вниз
				vector_length = Math.sqrt(Math.pow(route_dep_lat - route_des_lat, 2) + Math.pow(route_des_lon - route_dep_lon, 2));
				vector_tg = (route_dep_lat - route_des_lat)/(route_des_lon - route_dep_lon);
				vert_shift = (vector_length * tg10)/Math.sqrt(1 + vector_tg);
				hor_shift = vert_shift*vector_tg;
				area_coords = [
					[route_dep_lat, route_dep_lon],
					//[route_dep_lat + 0.3*vert_shift, route_dep_lon + hor_shift],
					//[route_dep_lat - 0.3*vert_shift, route_dep_lon - hor_shift],
					[route_des_lat - vert_shift, route_des_lon - 3*hor_shift],
					[route_des_lat + vert_shift, route_des_lon + 3*hor_shift],
				];
				route_angle = Math.atan(vector_tg) * rad2deg;
				route_angle = 90 + route_angle;
			}
		} else {
			if(route_des_lat > route_dep_lat){ //Маршрут направлен влево и вверх
				vector_length = Math.sqrt(Math.pow(route_des_lat - route_dep_lat, 2) + Math.pow(route_dep_lon - route_des_lon, 2));
				vector_tg = (route_des_lat - route_dep_lat)/(route_dep_lon - route_des_lon);
				vert_shift = (vector_length * tg10)/Math.sqrt(1 + vector_tg);
				hor_shift = vert_shift*vector_tg;
				area_coords = [
					[route_dep_lat, route_dep_lon],
					//[route_dep_lat + 0.3*vert_shift, route_dep_lon + hor_shift],
					//[route_dep_lat - 0.3*vert_shift, route_dep_lon - hor_shift],
					[route_des_lat - vert_shift, route_des_lon - 3*hor_shift],
					[route_des_lat + vert_shift, route_des_lon + 3*hor_shift],
				];
				route_angle = Math.atan(vector_tg) * rad2deg;
				route_angle = 270 + route_angle;
			} else { //Маршрут направлен влево и вниз
				vector_length = Math.sqrt(Math.pow(route_dep_lat - route_des_lat, 2) + Math.pow(route_dep_lon - route_des_lon, 2));
				vector_tg = (route_dep_lat - route_des_lat)/(route_dep_lon - route_des_lon);
				vert_shift = (vector_length * tg10)/Math.sqrt(1 + vector_tg);
				hor_shift = vert_shift*vector_tg;
				area_coords = [
					[route_dep_lat, route_dep_lon],
					//[route_dep_lat + 0.3*vert_shift, route_dep_lon - hor_shift],
					//[route_dep_lat - 0.3*vert_shift, route_dep_lon + hor_shift],
					[route_des_lat - vert_shift, route_des_lon + 3*hor_shift],
					[route_des_lat + vert_shift, route_des_lon - 3*hor_shift],
				];
				route_angle = Math.atan(vector_tg) * rad2deg;
				route_angle = 270 - route_angle;
			}
		}
		//YMap.geoObjects.remove(area);
		var area = new ymaps.Polygon(
				[
					area_coords,
				],
		{
			fillColor: '#000088',
			strokeColor: '#000088',
			strokeWidth: 2,
		});
		area.options.set('visible', true);
		YMap.geoObjects.add(area);
				
		var req = ymaps.route([[request['dep_lat'], request['dep_lon']], [request['des_lat'], request['des_lon']]]).then(
		function (res) {
			// Объединим в выборку все сегменты маршрута.
			var pathsObjects = ymaps.geoQuery(res.getPaths()),
				edges = [];
			// Переберем все сегменты и разобьем их на отрезки.
			pathsObjects.each(function (path) {
				var coordinates = path.geometry.getCoordinates();
				for (var i = 1, l = coordinates.length; i < l; i++) {
					edges.push({
						type: 'LineString',
						coordinates: [coordinates[i], coordinates[i - 1]]
					});
				}
			});
			var inside = 0;
			// Создадим новую выборку, содержащую:
			// - отрезки, описываюшие маршрут;
			// - начальную и конечную точки;
			// - промежуточные точки.
			var routeObjects = ymaps.geoQuery(edges)
					.add(res.getWayPoints())
					.add(res.getViaPoints())
					.setOptions('visible', false)
					.addToMap(YMap),
					objectsInArea = routeObjects.searchInside(area);
				objectsInArea.search('geometry.type = "LineString"').each(function (s) {
					inside += s.geometry.getDistance(); //Длина участка пересечения
				});
				//alert('ID: '+item['point_id']+'; Intersect: '+inside/res.getLength()+'; Req angl: '+req_angle+'; Route angl: '+route_angle);
				//Процент пересечения по отношению ко всему маршруту должен быть не менее 60%,
				//а направления маршрутов не должны отличаться более чем на 35 град в каждую сторону
				if( (inside / res.getLength() >= 0.6) && ( (Math.abs(route_angle - req_angle)<=35) || (Math.abs(route_angle - req_angle)>=325) ) ){
					onroad.push(item); //При выполнении условий добавляем пешеходный маршрут для отображения
				}
			}//function(res) end
		);//req end
		//alert('ID: '+ item['point_id'] +'; Req angl: '+ req_angle +'; Lim1 '+ ((360 + route_angle - 35)%360) + '; Lim2: '+ ((route_angle + 35)%360));
		//alert(inside);
		if(i + 1 == arr.length){
			setTimeout(function(){showMapPoints(YMap, onroad); showList(onroad);}, 200);
		}
	});
}*/
function showOnroadRequests(route){
	clearMap();
	var onroad = [];
	var startPoint = new ymaps.Placemark([route['dep_lat'], route['dep_lon']],
	{
		hintContent: '',
		iconContent: '<img style="border-radius: 3px" src="'+ route['pic_url'] +'">',
		
	},{
		iconLayout: 'default#imageWithContent',
		iconImageHref: '../../assets/img/route_pin.png',
		iconImageClipRect: [[0, 0], [50, 50]],
		iconImageSize: [50, 50],
		iconImageOffset: [-25, -50],
		iconContentOffset: [21, 7],
		iconContentSize: [27, 27],
	});
	var finishPoint = new ymaps.Placemark([route['des_lat'], route['des_lon']],
	{
		iconLayout: 'default#imageWithContent',
		iconImageHref: "../../assets/img/fin_pin.png",
		iconImageSize: [40, 40],
		iconImageOffset: [-25, -40]
	});
	YMap.geoObjects.add(startPoint); YMap.geoObjects.add(finishPoint);
	var multRoute = new ymaps.multiRouter.MultiRoute({
		// Описание опорных точек мультимаршрута.
		referencePoints: [
			startPoint,
			finishPoint
		],
		// Параметры маршрутизации.
		params: {
			// Ограничение на максимальное количество маршрутов, возвращаемое маршрутизатором.
			results: 1
		}
	}, {
		wayPointStart: startPlacemark,
		// Внешний вид линии маршрута.
		routeStrokeWidth: 2,
		routeStrokeColor: "#008800",
		routeActiveStrokeWidth: 6,
		routeActiveStrokeColor: "#008800",
		// Автоматически устанавливать границы карты так, чтобы маршрут был виден целиком.
		boundsAutoApply: true
	});
	YMap.geoObjects.add(multRoute);
	multRoute.model.events.once("requestsuccess", function () {
		var first_route_point = multRoute.getWayPoints().get(0);
		var last_route_point = multRoute.getWayPoints().get(1);
		//Hide first and last points from map
		first_route_point.options.set('visible', false);
		last_route_point.options.set('visible', false);
	});
	map_points.forEach(function(item, i, arr){
		var startCircle = new ymaps.Circle([[item['dep_lat'], item['dep_lon']], 2100],{},{
			visible: false
        });
		var finishCircle = new ymaps.Circle([[item['des_lat'], item['des_lon']], 2100],{},{
			visible: false
        });
		YMap.geoObjects.add(startCircle); YMap.geoObjects.add(finishCircle);
		var rt = ymaps.route([[route['dep_lat'], route['dep_lon']], [route['des_lat'], route['des_lon']]]).then(
			function (res) {
				var pathsObjects = ymaps.geoQuery(res.getPaths()),
				edges = [];
				pathsObjects.each(function (path) {
					var coordinates = path.geometry.getCoordinates();
					for (var i = 1, l = coordinates.length; i < l; i++) {
						edges.push({
							type: 'LineString',
							coordinates: [coordinates[i], coordinates[i - 1]]
						});
					}
				});
				var routeObjects = ymaps.geoQuery(edges)
					.add(res.getWayPoints())
					.add(res.getViaPoints())
					.setOptions('visible', false)
					.addToMap(YMap),
					objectsInStartCircle = routeObjects.searchInside(startCircle),
					objectsInFinishCircle = routeObjects.searchInside(finishCircle);
				var indexOfStart, indexOfFinish;
				objectsInStartCircle.search('geometry.type = "LineString"').each(function (s) {
					indexOfStart = routeObjects.indexOf(s);
					return false;
				});
				objectsInFinishCircle.search('geometry.type = "LineString"').each(function (s) {
					indexOfFinish = routeObjects.indexOf(s);
					return false;
				});
				if(indexOfStart !== undefined && indexOfFinish !== undefined){
					if(indexOfStart < indexOfFinish){
						onroad.push(item);
					}
				}
				if(i + 1 == arr.length){
					setTimeout(function(){showMapPoints(YMap, onroad); showList(onroad);}, 200);
				}
			} //function(res) end
		); //route end
	}); //end foreach
}

function showOnroadRoutes(request){
	clearMap();
	var onroad = [];
	var startPoint = new ymaps.Placemark([request['dep_lat'], request['dep_lon']],
	{
		hintContent: '',
		iconContent: '<img style="border-radius: 3px" src="'+ request['pic_url'] +'">',
		
	},{
		iconLayout: 'default#imageWithContent',
		iconImageHref: '../../assets/img/req_pin.png',
		iconImageClipRect: [[0, 0], [50, 50]],
		iconImageSize: [50, 50],
		iconImageOffset: [-25, -50],
		iconContentOffset: [21, 7],
		iconContentSize: [27, 27],
	});
	var finishPoint = new ymaps.Placemark([request['des_lat'], request['des_lon']],
	{
		iconLayout: 'default#imageWithContent',
		iconImageHref: "../../assets/img/fin_pin.png",
		iconImageSize: [40, 40],
		iconImageOffset: [-25, -40]
	});
	YMap.geoObjects.add(startPoint); YMap.geoObjects.add(finishPoint);
	var multRoute = new ymaps.multiRouter.MultiRoute({
		// Описание опорных точек мультимаршрута.
		referencePoints: [
			startPoint,
			finishPoint
		],
		// Параметры маршрутизации.
		params: {
			// Ограничение на максимальное количество маршрутов, возвращаемое маршрутизатором.
			results: 1
		}
	}, {
		wayPointStart: startPlacemark,
		// Внешний вид линии маршрута.
		routeStrokeWidth: 2,
		routeStrokeColor: "#008800",
		routeActiveStrokeWidth: 6,
		routeActiveStrokeColor: "#008800",
		// Автоматически устанавливать границы карты так, чтобы маршрут был виден целиком.
		boundsAutoApply: true
	});
	YMap.geoObjects.add(multRoute);
	multRoute.model.events.once("requestsuccess", function () {
		var first_route_point = multRoute.getWayPoints().get(0);
		var last_route_point = multRoute.getWayPoints().get(1);
		//Hide first and last points from map
		first_route_point.options.set('visible', false);
		last_route_point.options.set('visible', false);
	});
	var startCircle = new ymaps.Circle([[request['dep_lat'], request['dep_lon']], 2100],{},{
		visible: false
	});
	var finishCircle = new ymaps.Circle([[request['des_lat'], request['des_lon']], 2100],{},{
		visible: false
	});
	map_points.forEach(function(item, i, arr){
		YMap.geoObjects.add(startCircle); YMap.geoObjects.add(finishCircle);
		var rt = ymaps.route([[item['dep_lat'], item['dep_lon']], [item['des_lat'], item['des_lon']]]).then(
			function (res) {
				var pathsObjects = ymaps.geoQuery(res.getPaths()),
				edges = [];
				pathsObjects.each(function (path) {
					var coordinates = path.geometry.getCoordinates();
					for (var i = 1, l = coordinates.length; i < l; i++) {
						edges.push({
							type: 'LineString',
							coordinates: [coordinates[i], coordinates[i - 1]]
						});
					}
				});
				var routeObjects = ymaps.geoQuery(edges)
					.add(res.getWayPoints())
					.add(res.getViaPoints())
					.setOptions('visible', false)
					.addToMap(YMap),
					objectsInStartCircle = routeObjects.searchInside(startCircle),
					objectsInFinishCircle = routeObjects.searchInside(finishCircle);
				var indexOfStart, indexOfFinish;
				objectsInStartCircle.search('geometry.type = "LineString"').each(function (s) {
					indexOfStart = routeObjects.indexOf(s);
					return false;
				});
				objectsInFinishCircle.search('geometry.type = "LineString"').each(function (s) {
					indexOfFinish = routeObjects.indexOf(s);
					return false;
				});
				if(indexOfStart !== undefined && indexOfFinish !== undefined){
					if(indexOfStart < indexOfFinish){
						onroad.push(item);
					}
				}
				if(i + 1 == arr.length){
					setTimeout(function(){showMapPoints(YMap, onroad); showList(onroad);}, 200);
				}
			} //function(res) end
		); //route end
	}); //end foreach
}