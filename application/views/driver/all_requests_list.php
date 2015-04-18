<dl class="sub-nav">
	<!--dt>Фильтр:</dt-->
	<dd class="active"><a href="#">Все</a></dd>
	<dd><a href="#">По пути</a></dd>
	<dd><a href="#">Ближайшие</a></dd>
</dl>
<div class="list_container" id="requests_list">
<?php
foreach($requests as $request):
$from_time_arr = getdate($request['from_time']);
$mins = $from_time_arr['minutes']<10 ? '0'.$from_time_arr['minutes'] : $from_time_arr['minutes'];
$time_str = $from_time_arr['hours'].':'.$mins.' - ';
$to_time_arr = getdate($request['to_time']);
$mins = $to_time_arr['minutes'] < 10 ? '0'.$to_time_arr['minutes'] : $to_time_arr['minutes'];
$time_str .= $to_time_arr['hours'].':'.$mins;
?>
	<div class="row list_item">
	  <!-- User photo -->
	  <div class="small-3 columns" style="padding-right:0;">
		<img src="<?php echo $request['pic_url']?>">
	  </div>
	  <!-- List item info -->
	  <div class="small-9 columns">
		<h6> <i class="fa fa-flag-o"></i>&nbsp; <?php echo $request['dep_lat'].','.$request['dep_lon'];?> </h6>
		<h6> <i class="fa fa-flag-checkered"></i>&nbsp; <?php echo $request['des_lat'].','.$request['des_lon'];?> </h6>
		<h6> <?php echo 'Доп. инфо: '.$request['extra'];?> </h6>
	  </div>
	  <div class="small-12 columns">
		<h6> Сегодня &nbsp;<i class="fa fa-clock-o"></i> <?php echo $time_str;?> &nbsp; | &nbsp; 
		  <a href="#"> Подробнее </a>
		</h6>
	  </div>
	</div>

	<hr>

<?php endforeach;?>
</div>