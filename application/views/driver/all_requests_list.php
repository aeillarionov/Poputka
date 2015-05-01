<!-- Empty the array of map points -->
<script>
	map_points = [];
</script>

<dl class="sub-nav">
	<!--dt>Фильтр:</dt-->
	<dd class="active" onclick="show_all_requests_list()"><a href="#">Все</a></dd>
	<dd onclick="showNearestRequests()"><a href="#">Рядом</a></dd>
	<dd onclick="showMyRoutes()"><a href="#">По пути</a></dd>
	<dd onclick=""><a href="#">Фильтр</a></dd>
</dl>
<h5><i class="fa fa-user"></i>&nbsp; Пешеходы: </h5>
<div class="list_container" id="requests_list">
<?php
foreach($requests as $request):
$now = getdate();
$days_str = '';
$from_time_arr = getdate($request['from_time']);
$mins = $from_time_arr['minutes']<10 ? '0'.$from_time_arr['minutes'] : $from_time_arr['minutes'];
$time_str = $from_time_arr['hours'].':'.$mins.' - ';
$to_time_arr = getdate($request['to_time']);
$mins = $to_time_arr['minutes'] < 10 ? '0'.$to_time_arr['minutes'] : $to_time_arr['minutes'];
$time_str .= $to_time_arr['hours'].':'.$mins;
$regular = $request['regular'];
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
	<div class="row list_item hvr-left" id="list_item_<?php echo $request['request_id'];?>" onmouseover="highlightMark(this)" onmouseout="defaultMark(this)" onclick="showFinishMark(this)">
	  <!-- User photo -->
	  <div class="small-3 columns" style="padding-right:0;">
		<img src="<?php echo $request['pic_url']?>">
	  </div>
	  <!-- List item info -->
	  <div class="small-9 columns">
		<h6> <i class="fa fa-flag-o"></i>&nbsp; <?php echo $request['dep_addr'];?> </h6>
		<h6> <i class="fa fa-flag-checkered"></i>&nbsp; <?php echo $request['des_addr'];?> </h6>
	  </div>
	  <div class="small-12 columns">
		<h6> <?php echo $days_str;?> &nbsp;<i class="fa fa-clock-o"></i> <?php echo $time_str;?> &nbsp; | &nbsp; 
		  <a class="show_more_btn" > Подробнее </a>
		</h6>
	  </div>
	  
	   <!-- Show more info container -->
    <div hidden class="small-12 columns show_more text-center">
      <h6>&nbsp;<i class="fa fa-male"></i>&nbsp;x <?php echo $request['male_pass']+$request['female_pass'];?> </h6>
      <h6>&nbsp;<i class="fa fa-comment">&nbsp;</i><?php echo $request['extra'];?> </h6>
    </div>
	</div>

	<hr>
<script>
	var request_coords = {
		'point_type': 0,
		'point_id': <?php echo $request['request_id'];?>,
		'dep_lat': <?php echo $request['dep_lat'];?>,
		'dep_lon': <?php echo $request['dep_lon'];?>,
		'des_lat': <?php echo $request['des_lat'];?>,
		'des_lon': <?php echo $request['des_lon'];?>,
		'des_addr': <?php echo '"'.$request['des_addr'].'"';?>,
		'pic_url': <?php echo '"'.$request['pic_url'].'"';?>
	};
	map_points.push(request_coords);
</script>
<?php endforeach;?>
</div>
<a class="button pulse" onclick="$('#list_view').toggleClass('active'); $('#add_trip').toggleClass('active'); clearMap();" href="#">+ Добавить свою поездку</a>
<script>
	clearMap();
	adjustMap();
	showMapPoints(YMap, map_points);
</script>
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