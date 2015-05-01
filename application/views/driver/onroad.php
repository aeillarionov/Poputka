<!-- Empty the array of map points -->
<script>
	var point_id = <?php echo $route_id;?>;
	var route_points;
	map_points.forEach(function(item, i, arr){
		if(item['point_id'] == point_id){
			route_points = item;
		}
	});
	map_points = [];
</script>

<dl class="sub-nav">
	<!--dt>Фильтр:</dt-->
	<dd onclick="showMyRoutes()"><a href="#"><i class="fa fa-arrow-left"></i>&nbsp;Назад</a></dd>
</dl>
<h5><i class="fa fa-male"></i>&nbsp;&nbsp;Пешеходы по пути:</h5>
<div class="list_container" id="requests_list">
<?php
foreach($requests as $request):
?>
<script>
	var request_coords = {
		'point_type': 0,
		'point_id': <?php echo $request['request_id'];?>,
		'dep_lat': <?php echo $request['dep_lat'];?>,
		'dep_lon': <?php echo $request['dep_lon'];?>,
		'dep_addr': <?php echo '"'.$request['dep_addr'].'"';?>,
		'des_lat': <?php echo $request['des_lat'];?>,
		'des_lon': <?php echo $request['des_lon'];?>,
		'des_addr': <?php echo '"'.$request['des_addr'].'"';?>,
		'male_pass': <?php echo $request['male_pass'];?>,
		'female_pass': <?php echo $request['female_pass'];?>,
		'regular': <?php echo '"'.$request['regular'].'"';?>,
		'from_time': <?php echo '"'.$request['from_time'].'"';?>,
		'to_time': <?php echo '"'.$request['to_time'].'"';?>,
		'extra': <?php echo '"'.$request['extra'].'"';?>,
		'pic_url': <?php echo '"'.$request['pic_url'].'"';?>
	};
	map_points.push(request_coords);
</script>
<?php endforeach;?>
</div>
<script>
	adjustMap();
	showOnroadRequests(route_points);
</script>