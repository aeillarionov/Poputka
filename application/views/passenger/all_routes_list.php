<!-- Empty the array of map points -->
<script>
	map_points = [];
</script>
<dl class="sub-nav">
	<!--dt>Фильтр:</dt-->
	<dd class="active" onclick="show_all_routes_list()"><a href="#">Все</a></dd>
	<dd onclick="showNearestRoutes()"><a href="#">Рядом</a></dd>
	<dd onclick="showMyRequests()"><a href="#">По пути</a></dd>
	<dd onclick=""><a href="#">Фильтр</a></dd>
</dl>
<h5><i class="fa fa-car"></i>&nbsp; Водители: </h5>
<div class="list_container" id="routes_list">
<?php
foreach($routes as $route):
$now = getdate();
$days_str = '';
$from_time_arr = getdate($route['from_time']);
$mins = $from_time_arr['minutes']<10 ? '0'.$from_time_arr['minutes'] : $from_time_arr['minutes'];
$time_str = $from_time_arr['hours'].':'.$mins.' - ';
$to_time_arr = getdate($route['to_time']);
$mins = $to_time_arr['minutes'] < 10 ? '0'.$to_time_arr['minutes'] : $to_time_arr['minutes'];
$time_str .= $to_time_arr['hours'].':'.$mins;
$regular = $route['regular'];
      if($regular == 0){
      	if($from_time_arr['mday'] == $now['mday'] && $from_time_arr['mon'] == $now['mon'] && $from_time_arr['year'] == $now['year']){
      		$days_str = 'Сегодня';
      	} else {
      		$days_str .= '<i class="fa fa-calendar"></i> ';
      		$days_str .= $from_time_arr['mday']<10 ? '0'.$from_time_arr['mday'] : $from_time_arr['mday'];
      		$days_str .= '/';
      		$days_str .= $from_time_arr['mon']<10 ? '0'.$from_time_arr['mon'] : $from_time_arr['mon'];
      	}
      } else {
      	$regul_arr = explode(',', $regular);
      	$days_str = 'Регулярно |';
      	foreach($regul_arr as $day):
      		switch($day){
      			case 1:
      				$days_str .= ' Пн |';
      				break;
      			case 2:
      				$days_str .= ' Вт |';
      				break;
      			case 3:
      				$days_str .= ' Ср |';
      				break;
      			case 4:
      				$days_str .= ' Чт |';
      				break;
      			case 5:
      				$days_str .= ' Пт |';
      				break;
      			case 6:
      				$days_str .= ' Сб |';
      				break;
      			case 7:
      				$days_str .= ' Вс |';
      				break;
      		}
      	endforeach;
      	$days_str .= '<br><br>';
      }
?>
	<div class="row list_item hvr-left" id="list_item_<?php echo $route['route_id'];?>" onmouseover="highlightMark(this)" onmouseout="defaultMark(this)" onclick="showFinishMark(this)">
	  <!-- User photo -->
	  <div class="small-3 columns" style="padding-right:0;">
		<img src="<?php echo $route['pic_url']?>">
	  </div>
	  <!-- List item info -->
	  <div class="small-9 columns">
		<h6> <i class="fa fa-flag-o"></i>&nbsp; <?php echo $route['dep_addr'];?> </h6>
		<h6> <i class="fa fa-flag-checkered"></i>&nbsp; <?php echo $route['des_addr'];?> </h6>
	  </div>
	  <div class="small-12 columns">
		<h6> <?php echo $days_str;?> &nbsp;<i class="fa fa-clock-o"></i> <?php echo $time_str;?> &nbsp; | &nbsp; 
		  <a class="show_more_btn"> Подробнее </a>
		</h6>
	  </div>
	  <!-- Show more info container -->
    <div hidden class="small-12 columns show_more text-center">
      <h6>&nbsp;<i class="fa fa-male"></i>&nbsp;x <?php echo $route['spots'];?> </h6>
      <h6>&nbsp;<i class="fa fa-comment">&nbsp;</i><?php echo $route['extra'];?> </h6>
    </div>
	</div>

	<hr>
<script>
	var route_coords = {
		'point_type': 1,
		'point_id': <?php echo $route['route_id'];?>,
		'dep_lat': <?php echo $route['dep_lat'];?>,
		'dep_lon': <?php echo $route['dep_lon'];?>,
		'des_lat': <?php echo $route['des_lat'];?>,
		'des_lon': <?php echo $route['des_lon'];?>,
		'des_addr': <?php echo '"'.$route['des_addr'].'"';?>,
		'pic_url': <?php echo '"'.$route['pic_url'].'"';?>
	};
	map_points.push(route_coords);
</script>
<?php endforeach;?>

    <script src="../../assets/js/vendor/jquery.js"></script>
    <script type="text/javascript">
      /***
        Show and hide extra info about the route
        if one is clicked, the other hides
        Requires jQuery
      ***/
      jQuery(".show_more_btn").click(function(){
        var th_show_more = $(this).closest(".list_item").find(".show_more");
        if ( th_show_more.is(':visible') ){
          $(".show_more").hide();
          th_show_more.hide("fast");
        } else{
          $(".show_more").hide("fast");
          th_show_more.slideDown("fast").css("display", "inline");
        }
        //$(".show_more").hide();
        //$(this).closest(".list_item").find(".show_more").slideToggle("fast").css("display", "inline");
      });
    </script>

<script>
	clearMap();
	adjustMap();
	showMapPoints(YMap, map_points);
</script>
</div>
<a class="button pulse" onclick="$('#list_view').toggleClass('active'); $('#add_trip').toggleClass('active'); clearMap();" href="#">+ Добавить свой маршрут</a>