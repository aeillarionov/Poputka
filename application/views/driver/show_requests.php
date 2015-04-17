  <!--div class="off-canvas-wrap" data-offcanvas>
    <div class="inner-wrap">
      <nav class="tab-bar">
        <section class="left tab-bar-section">
          <a href="#" style="height:100%"> <img style="height:100%" src="http://placehold.it/150x70"> </a>
        </section>

        <section class="middle tab-bar-section">
          <h1 class="title">Подвези меня</h1>
        </section>

        <section class="right-small">
          <a class="right-off-canvas-toggle menu-icon" href="#"><span></span></a>
        </section>
      </nav>

      <aside class="right-off-canvas-menu">
        <ul class="off-canvas-list">
          <li><label>USERNAME</label></li>
          <li><a href="#">Профиль</a></li>
          <li><a href="#">Настройки</a></li>
          <li><a href="#">Выйти</a></li>
        </ul>
      </aside-->

<!-- Array of points to be shown on the map -->
<script>
	var map_points = [];
</script>        

        <!-- Placemarks parameters area -->
        <div class="row zigzag">

        </div>

        <div class="row panel">
          <!-- Banner placeholder -->
          <!--div class="small-2 columns">
            <img style="height:100%" src="http://placehold.it/150x400">
          </div-->

          <!-- Route parameters & save placeholder -->
          <div class="small-4 columns left_panel_container">

            <!-- Start placemark area -->
            <div class="row">             
              <div class="small-12 columns text-center">
                <!-- Add or view tabs -->
                <ul class="tabs" data-tab>
                  <li class="tab-title <?php if($mode==0) echo 'active';?>" onclick="show_all_requests_list()"><a href="#list_view">Попутчики</a></li>
                  <li class="tab-title <?php if($mode==1) echo 'active';?>" onclick="clearMap()"><a href="#add_trip">Новая поездка</a></li>
                </ul>
                <div class="tabs-content">
                  <!-- List Tab -->
                  <div class="content <?php if($mode==0) echo 'active';?>" id="list_view">
                    <div class="row"> <div class="small-12 columns">
                      <dl class="sub-nav">
                        <!--dt>Фильтр:</dt-->
                        <dd class="active"><a href="#">Все</a></dd>
                        <dd><a href="#">По пути</a></dd>
                        <dd><a href="#">Ближайшие</a></dd>
                      </dl>
                      <div class="list_container" id="requests_list">
<?php
if($mode==0){
foreach($requests as $request):
$from_time_arr = getdate($request['from_time']);
$mins = $from_time_arr['minutes']<10 ? '0'.$from_time_arr['minutes'] : $from_time_arr['minutes'];
$time_str = $from_time_arr['hours'].':'.$mins.' - ';
$to_time_arr = getdate($request['to_time']);
$mins = $to_time_arr['minutes'] < 10 ? '0'.$to_time_arr['minutes'] : $to_time_arr['minutes'];
$time_str .= $to_time_arr['hours'].':'.$mins;
?>
	<div class="row list_item" id="list_item_<?php echo $request['request_id'];?>" onmouseover="highlightMark(this)" onmouseout="defaultMark(this)" onclick="showFinishMark(this)">
	  <!-- User photo -->
	  <div class="small-3 columns" style="padding-right:0;">
		<img src="<?php echo $request['pic_url'];?>">
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
<!-- Fill the array of map points -->
<script>
	var request_coords = {
		'point_id': <?php echo $request['request_id'];?>,
		'dep_lat': <?php echo $request['dep_lat'];?>,
		'dep_lon': <?php echo $request['dep_lon'];?>,
		'des_lat': <?php echo $request['des_lat'];?>,
		'des_lon': <?php echo $request['des_lon'];?>,
		'pic_url': <?php echo '"'.$request['pic_url'].'"';?>
	};
	map_points.push(request_coords);
</script>
<?php
endforeach;
}
?>

                      </div>
                    </div> </div>
                  </div>
                  
                  
                  <!-- Add Trip Tab -->
                  <div class="content <?php if($mode==1) echo 'active';?>" id="add_trip">
                    <h5><i class="fa fa-road"></i>&nbsp;&nbsp;Данные о поездке </h5>
                    <form action="add_route" method="post">
                      <!--fieldset>
                        <legend>Данные о поездке</legend-->
                        <!-- Start point -->

                        <div class="row collapse">
                          <div class="small-3 large-2 columns">
                            <span class="prefix">Из:</span>
                          </div>
                          <div class="small-6 large-8 columns">
                            <input id="startPlacemarkValue" type="text" placeholder="Откуда еду">
                            <input type="hidden" name="departureCoord" id="departureCoord">
                            <!--small class="error">Необходимо указать начальную точку</small-->
                          </div>
                          <div class="small-3 large-2 columns">
                            <a class="button postfix" onClick="searchStartPlacemarkByForm()"><i class="fa fa-search"></i></a>
                          </div>
                        </div>

                        <!-- Finish point -->
                        <div class="row collapse">
                          <div class="small-3 large-2 columns">
                            <span class="prefix">В:</span>
                          </div>
                          <div class="small-6 large-8 columns">
                            <input id="finishPlacemarkValue" type="text" placeholder="Куда еду">
                            <input type="hidden" name="destinationCoord" id="destinationCoord">
                          </div>
                          <div class="small-3 large-2 columns">
                            <a class="button postfix" onClick="searchFinishPlacemarkByForm()"><i class="fa fa-search"></i></a>
                          </div>
                        </div>

                        <!-- Date -->
                        <div class="row collapse">
                          <div class="small-3 large-2 columns">
                            <span class="prefix"><i class="fa fa-calendar"></i></span>
                          </div>
                          <div class="small-9 large-10 columns">
                            <input id="startDate" name="startDate" type="text" placeholder="ДД.ММ.ГГГГ">
                          </div>
                        </div>

                        <!-- Time -->
                        <div class="row collapse">
                          <div class="small-2 columns">
                            <span class="prefix">C:</i> </span>
                          </div>
                          <div class="small-4 columns">
                            <input id="startTime" name="startTime" type="text" placeholder="ЧЧ:ММ">
                          </div>
                          <div class="small-2 columns">
                            <span class="prefix">По:</span>
                          </div>
                          <div class="small-4 columns">
                            <input id="finishTime" name="finishTime" type="text" placeholder="ЧЧ:ММ">
                          </div>
                        </div>

                        <!-- Frequency -->
                        <div class="row collapse frequency">
                          <div class="small-6 columns text-center"> 
                          <label>Один раз
                            <div class="switch tiny ">
                              <input id="isOneoffRadioSwitch" type="radio" checked name="frequencySwitchGroup">
                              <label for="isOneoffRadioSwitch"></label>
                            </div>
                          </label>
                        </div>
                        <div class="small-6 columns text-center">
                          <label>Регулярно
                            <div class="switch tiny ">
                              <input id="isRegularRadioSwitch" type="radio" name="frequencySwitchGroup">
                              <label for="isRegularRadioSwitch"></label>
                            </div>
                          </label>
                        </div>
                      </div>
                      <!-- Week choice -->
                      <div class="row collapse weekdayChoice">
                        <div class="small-12 columns text-center"> 
                          <!-- Radius Button Group -->
                          <ul class="button-group radius even-7 ">
                            <li><a href="#" class="button tiny">Пн</a></li>
                            <li><a href="#" class="button tiny">Вт</a></li>
                            <li><a href="#" class="button tiny">Ср</a></li>
                            <li><a href="#" class="button tiny">Чт</a></li>
                            <li><a href="#" class="button tiny">Пт</a></li>
                            <li><a href="#" class="button tiny">Сб</a></li>
                            <li><a href="#" class="button tiny">Вс</a></li>
                          </ul>
                        </div>
                      </div>

                      <!-- Passengers -->
                      <div class="row collapse passangers">
                        <div class="small-12 columns text-center">
                          <!--label for="right-label" class="inline-label">Свобоных мест-->
                            <div class="row collapse">
                              <div class="small-6 columns ">
                                <span class="prefix"> Свободные места: </span>
                              </div>
                              <div class="small-6 columns">
                                <select id="passangers_quantity" name="passangers_quantity">
                                  <option value="1" selected="selected">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                </select>
                              </div>
                            </div>
                          <!--/label-->
                        </div>
                      </div>

                      <!-- Comment -->
                      <div class="row collapse">
                        <div class="large-12 columns text-center">
                          <label>Доп. информация
                            <textarea id="additional_info" name="extra" placeholder="Введите при необходимости"></textarea>
                          </label>
                        </div>
                      </div>

                      <!-- Submit -->
                      <div class="row collapse">
                        <div class="small-12 columns text-center">
                          <button id="submit_pick_request" class="expand button success"> <i class="fa fa-car"></i> Добавить поездку </button>
                        </div>
                      </div>

                      <!--/fieldset-->
                    </form>
                  </div>
                </div> <!-- /tabs-content -->
              </div>
            </div>
            
          </div>

          <!-- Map placeholder -->
          <div class="small-8 columns">
            <div id="ymap"></div>
          </div>

          

          <!-- Matches placeholder >
          <div class="small-3 columns panel">
            <!-- Matches list >
            <div class="row">
              <div class="small-12 columns text-center">
                <h4>Вам по пути с:</h4>
              </div>
            </div>
            <!--Taxi Banner placeholder>
            <img style="width:100%" src="http://placehold.it/300x500">
          </div-->
          

        </div>



    <!--a class="exit-off-canvas"></a>

    </div>
  </div-->