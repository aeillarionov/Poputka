<!-- Empty the array of map points -->
<script>
	var point_id = <?php echo $request_id;?>;
	var request_points;
	map_points.forEach(function(item, i, arr){
		if(item['point_id'] == point_id){
			request_points = item;
		}
	});
	map_points = [];
</script>

<dl class="sub-nav">
	<!--dt>Фильтр:</dt-->
	<dd onclick="showMyRequests()"><a href="#"><i class="fa fa-arrow-left"></i>&nbsp;Назад</a></dd>
</dl>
<div class="list_container" id="requests_list">
<?php
foreach($routes as $route):
?>
<script>
	var route_coords = {
		'point_type': 1,
		'point_id': <?php echo $route['route_id'];?>,
		'dep_lat': <?php echo $route['dep_lat'];?>,
		'dep_lon': <?php echo $route['dep_lon'];?>,
		'dep_addr': <?php echo '"'.$route['dep_addr'].'"';?>,
		'des_lat': <?php echo $route['des_lat'];?>,
		'des_lon': <?php echo $route['des_lon'];?>,
		'des_addr': <?php echo '"'.$route['des_addr'].'"';?>,
		'spots': <?php echo $route['spots'];?>,
		'regular': <?php echo '"'.$route['regular'].'"';?>,
		'from_time': <?php echo '"'.$route['from_time'].'"';?>,
		'to_time': <?php echo '"'.$route['to_time'].'"';?>,
		'extra': <?php echo '"'.$route['extra'].'"';?>,
		'pic_url': <?php echo '"'.$route['pic_url'].'"';?>
	};
	map_points.push(route_coords);
</script>
<?php endforeach;?>
</div>
<script>
	adjustMap();
	showOnroadRoutes(request_points);
</script>