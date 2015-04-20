<!-- Empty the array of map points -->
<script>
	map_points = [];
</script>

<dl class="sub-nav">
	<!--dt>Фильтр:</dt-->
	<dd onclick="show_all_routes_list()"><a href="#">Все</a></dd>
	<dd onclick="showMyRequests()"><a href="#">По пути</a></dd>
	<dd class="active" onclick="showNearestRoutes()"><a href="#">Ближайшие</a></dd>
</dl>
<div class="list_container" id="requests_list">
<?php
foreach($routes as $route):
$from_time_arr = getdate($route['from_time']);
$mins = $from_time_arr['minutes']<10 ? '0'.$from_time_arr['minutes'] : $from_time_arr['minutes'];
$time_str = $from_time_arr['hours'].':'.$mins.' - ';
$to_time_arr = getdate($route['to_time']);
$mins = $to_time_arr['minutes'] < 10 ? '0'.$to_time_arr['minutes'] : $to_time_arr['minutes'];
$time_str .= $to_time_arr['hours'].':'.$mins;
?>
	<div class="row list_item" id="list_item_<?php echo $route['route_id'];?>" onmouseover="highlightMark(this)" onmouseout="defaultMark(this)" onclick="showFinishMark(this)">
	  <!-- User photo -->
	  <div class="small-3 columns" style="padding-right:0;">
		<img src="<?php echo $route['pic_url']?>">
	  </div>
	  <!-- List item info -->
	  <div class="small-9 columns">
		<h6> <i class="fa fa-flag-o"></i>&nbsp; <?php echo $route['dep_lat'].','.$route['dep_lon'];?> </h6>
		<h6> <i class="fa fa-flag-checkered"></i>&nbsp; <?php echo $route['des_lat'].','.$route['des_lon'];?> </h6>
		<h6> <?php echo 'Доп. инфо: '.$route['extra'];?> </h6>
	  </div>
	  <div class="small-12 columns">
		<h6> Сегодня &nbsp;<i class="fa fa-clock-o"></i> <?php echo $time_str;?> &nbsp; | &nbsp; 
		  <a href="#"> Подробнее </a>
		</h6>
	  </div>
	</div>

	<hr>

<script>
	var route_coords = {
		'point_id': <?php echo $route['route_id'];?>,
		'dep_lat': <?php echo $route['dep_lat'];?>,
		'dep_lon': <?php echo $route['dep_lon'];?>,
		'des_lat': <?php echo $route['des_lat'];?>,
		'des_lon': <?php echo $route['des_lon'];?>,
		'pic_url': <?php echo '"'.$route['pic_url'].'"';?>
	};
	map_points.push(route_coords);
</script>

<?php
endforeach;
?>

</div>
<script>
	showMapPoints(YMap, map_points);
</script>