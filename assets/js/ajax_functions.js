function show_all_requests_list() {
	$('#list_view').load('show_all_requests_list');
}
function show_all_routes_list() {
	$('#list_view').load('show_all_routes_list');
}
function showMyRequests(){
	$('#list_view').load('showMyRequests');
}
function showMyRoutes(){
	$('#list_view').load('showMyRoutes');
}
function loadOnroadRequests(route_id){
	$('#list_view').load('showOnroad/'+route_id);
}
function loadOnroadRoutes(request_id){
	$('#list_view').load('showOnroad/'+request_id);
}
function showList(points){
	var html_content = '';
	if(points.length > 0){
		points.forEach(function(item, i, arr){
			var persons;
			if(item['spots'] !== undefined){
				persons = item['spots'];
			} else if((item['male_pass'] !== undefined) && (item['female_pass'] !== undefined)){
				persons = +item['male_pass']+item['female_pass'];
			} else {
				persons = '';
			}
			var from_date_obj = new Date(item['from_time'] * 1000);
			var to_date_obj = new Date(item['to_time'] * 1000);
			var time_str;
			var regular = item['regular'];
			if(regular != 0){
				var regul_arr = regular.split(',');
				time_str = 'Регулярно';
				regul_arr.forEach(function(day, index, rar){
					switch(day){
						case '1':
							time_str += ' | Пн';
							break;
						case '2':
							time_str += ' | Вт';
							break;
						case '3':
							time_str += ' | Ср';
							break;
						case '4':
							time_str += ' | Чт';
							break;
						case '5':
							time_str += ' | Пт';
							break;
						case '6':
							time_str += ' | Сб';
							break;
						case '7':
							time_str += ' | Вс';
							break;
					}
				});
				time_str += ' | <br><br>&nbsp;<i class="fa fa-clock-o"></i> ';
			} else {
				var now = new Date();
				time_str = '<h6>';
				if(from_date_obj.getDate()==now.getDate() && from_date_obj.getMonth()==now.getMonth() && from_date_obj.getFullYear()==now.getFullYear()){
					time_str += ' Сегодня ';
				} else {
					time_str += (from_date_obj.getDate()>9 ? from_date_obj.getDate() : '0'+from_date_obj.getDate()) +'/'+ (from_date_obj.getMonth()>9 ? from_date_obj.getMonth() : '0'+from_date_obj.getMonth());
				}
				time_str += '&nbsp;<i class="fa fa-clock-o"></i> ';
			}
			time_str += from_date_obj.getHours()+':'+(from_date_obj.getMinutes()>9 ? from_date_obj.getMinutes() : '0'+from_date_obj.getMinutes());
			time_str += '-'+to_date_obj.getHours()+':'+(to_date_obj.getMinutes()>9 ? to_date_obj.getMinutes() : '0'+to_date_obj.getMinutes());
			
			html_content += '<div class="row list_item hvr-left" id="list_item_'+ item['point_id'] +'" onmouseover="highlightMark(this)" onmouseout="defaultMark(this)" onclick="showFinishMark(this)">\
		  <!-- User photo -->\
		  <div class="small-3 columns" style="padding-right:0;">\
			<img src="'+ item['pic_url'] +'">\
		  </div>\
		  <!-- List item info -->\
		  <div class="small-9 columns">\
			<h6> <i class="fa fa-flag-o"></i>&nbsp; '+ item['dep_addr'] +'</h6>\
			<h6> <i class="fa fa-flag-checkered"></i>&nbsp;'+ item['des_addr'] +'</h6>\
		  </div>\
		  <div class="small-12 columns">\
			<h6> '+ time_str +' &nbsp; | &nbsp; \
			  <a class="show_more_btn" > Подробнее </a>\
			</h6>\
		  </div>\
		   <!-- Show more info container -->\
		<div hidden class="small-12 columns show_more text-center">\
		  <h6>&nbsp;<i class="fa fa-male"></i>&nbsp;x'+ persons +'</h6>\
		  <h6>&nbsp;<i class="fa fa-comment">&nbsp;</i>'+ item['extra'] +'</h6>\
		</div>\
		</div>\
		<hr>';
		});
	} else {
		html_content = 'Попутных не найдено';
	}
	$('#requests_list').html(html_content);
}