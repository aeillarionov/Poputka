<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
  <meta charset="utf-8">
  <!-- If you delete this meta tag World War Z will become a reality -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Подвези меня | Попутчики</title>

  <!-- If you are using the CSS version, only link these 2 files, you may add app.css to use for your overrides if you like -->
  <link rel="stylesheet" href="<?php echo asset_url(); ?>css/normalize.css">
  <link rel="stylesheet" href="<?php echo asset_url(); ?>css/foundation.css">

  <!-- If you are using the gem version, you need this only -->
  <link rel="stylesheet" href="<?php echo asset_url(); ?>css/pick_me.css">
  <link rel="stylesheet" href="<?php echo asset_url(); ?>css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url(); ?>css/jquery.datetimepicker.css">

  <script src="<?php echo asset_url(); ?>js/vendor/modernizr.js"></script>

  <!-- Yandex.Maps API -->
  <script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

</head>
<body>

  <nav class="top-bar" data-topbar role="navigation" data-options="is_hover: true">
    <ul class="title-area">
        <li class="name">
             <a href="../" style="height:100%"> <img style="height:100%" src="http://placehold.it/150x70"> </a>
        </li>
        <li class="toggle-topbar menu-icon"><a href="#"><span>Меню</span></a>
        </li>
    </ul>

    <section class="top-bar-section">
      <ul class="right">
          <li class="divider"></li>
            <li class="active has-dropdown"><a href="#"><i class="fa fa-car"></i> &nbsp; Я - пешеход   </a>
              <ul class="dropdown">
                <li class="active"><a  href="../driver/show_requests"><i class="fa fa-user"></i> &nbsp; Я - водитель  </a></li>
              </ul>
            </li>
          <li class="divider"></li>
          <li class="has-dropdown">
            <a href="#"><?php echo $_SESSION['name'].' '.$_SESSION['surename'];?></a>
            <ul class="dropdown">
              <li><a href="#">Профиль</a></li>
              <li class=""><a href="#">Настройки</a></li>
              <li><a href="../auth/logout">Выйти</a></li>
            </ul>
          </li>
          <li class="divider"></li>
      </ul>
      <!--ul class="left">
        <li><a href="#">Добавить поездку</a></li>
      </ul-->
    </section>

  </nav>

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

      <section class="main-section">
        

        <!-- Placemarks parameters area -->
        <div class="row zigzag">

        </div>

        <div class="row panel">
          <!-- Banner placeholder -->
          <!--div class="small-2 columns">
            <img style="height:100%" src="http://placehold.it/150x400">
          </div-->

          <!-- Route parameters & save placeholder -->
          <div class="small-4 columns">

            <!-- Start placemark area -->
            <div class="row">             
              <div class="small-12 columns text-center">
                <h5><i class="fa fa-road"></i>&nbsp;&nbsp;Данные о поездке </h5>
                <form action="add_request" method="post">
                  <!--fieldset>
                    <legend>Данные о поездке</legend-->
                    <!-- Start point -->

                    <div class="row collapse">
                      <div class="small-3 large-2 columns">
                        <span class="prefix">Из:</span>
                      </div>
                      <div class="small-6 large-8 columns">
                        <input id="startPlacemarkValue" type="text" placeholder="Откуда забрать">
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
                        <input id="finishPlacemarkValue" type="text" placeholder="Куда довезти">
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
                      <label for="right-label" class="">Пассажиры
                        <div class="row">
                          <div class="small-6 columns">
                            <div class="row collapse">
                              <div class="small-5 columns ">
                                <span class="prefix"> <i class="fa fa-male"></i> </span>
                              </div>
                              <div class="small-7 columns">
                                <select id="male_quantity" name="male_quantity">
                                  <option value="0">0</option>
                                  <option value="1" selected="selected">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="small-6 columns">
                            <div class="row collapse">
                              <div class="small-5 columns ">
                                <span class="prefix"> <i class="fa fa-female"></i> </span>
                              </div>
                              <div class="small-7 columns">
                                <select id="female_quantity" name="female_quantity">
                                  <option value="0" selected="selected">0</option>
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                </select>
                              </div>
                            </div>
                          </div>

                        </div>
                      </label>
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
                      <button id="submit_pick_request" class="expand button success">Подвези меня <i class="fa fa-thumbs-up"></i> </button>
                    </div>
                  </div>

                  <!--/fieldset-->
                </form>
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

      </section>

    <!--a class="exit-off-canvas"></a>

    </div>
  </div-->

  <footer>
    <div class="row footer">
      <!-- About us -->
      <div class="small-4 columns">
        <h5 class="">О сервисе</h5>
        <ul class="no-bullet">
          <li><a href="#"> FAQ </a></li>
          <li><a href="#">Бонусная система </a></li>
          <!--li><a href="#">Партнеры </a></li-->
          <li><a href="#">Конфиденциальность </a></li>
          <li><a href="#">Отзывы </a></li>
        </ul>
      </div>
      <!-- Social -->
      <div class="small-4 columns">
        <h5 class="">Мы в соцсетях</h5>
        <ul class="no-bullet social">
          <li><a href="#"> <i class="fa fa-vk"></i> ВКонтакте </a></li>
          <li><a href="#"> <i class="fa fa-instagram"></i> Instagram </a></li>
          <li><a href="#"> <i class="fa fa-facebook"></i> Facebook </a></li>
        </ul>
      </div>
      <!-- Contacts -->
      <div class="small-4 columns">
        <h5 class="">Контакты</h5>
        <ul class="no-bullet contacts">
          <li> <a href="#">  <i class="fa fa-envelope"></i> info@bipbip.me  </a></li>
          <li> <img style="margin:5px 0 5px 0;" src="http://placehold.it/150x50"> </li>
          <li> © 2015. bipbip.me Все права защищены</li>
        </ul>  
      </div>
    </div>
  </footer>

  
  <script src="<?php echo asset_url(); ?>js/vendor/yandex_map.modules.js"></script>
  <script src="<?php echo asset_url(); ?>js/vendor/yandex_map.js"></script>
  <script src="<?php echo asset_url(); ?>js/vendor/jquery.js"></script>
  <script src="<?php echo asset_url(); ?>js/foundation.min.js"></script>
  <script src="<?php echo asset_url(); ?>js/foundation/foundation.topbar.js"></script>
  <script src="<?php echo asset_url(); ?>js/foundation/foundation.abide.js"></script>
  <script src="<?php echo asset_url(); ?>js/vendor/jquery.datetimepicker.js"></script>
  <script>
    $(document).foundation();
  </script>
  <!-- Date-time Picker -->
  <script type="text/javascript">
    jQuery('#startDate').datetimepicker({
      timepicker:false,
      datepicker:true,
      lang:'ru',
      format:'d/m/Y',
      closeOnDateSelect:true,
      minDate:'-1970/01/02',
      mask:true,
      dayOfWeekStart: 1,

    });

    jQuery(function(){
     jQuery('#startTime').datetimepicker({
      datepicker:false,
      lang: 'ru',
      format:'H:i',
      step:15,
      /*validateOnBlur:false,
      mask:true,*/
      closeOnTimeSelect:true,
      onShow:function( ct ){
       this.setOptions({
        minTime:0,
        maxTime:jQuery('#finishTime').val()?jQuery('#finishTime').val():false
       })
      },
     });
     jQuery('#finishTime').datetimepicker({
      datepicker:false,
      lang: 'ru',
      format:'H:i',
      step:15,
      /*mask:true,
      defaultTime:new Date(),*/
      closeOnTimeSelect:true,
      onShow:function( ct ){
       this.setOptions({
        minTime:jQuery('#startTime').val()?jQuery('#startTime').val():false
       })
      },
     });
    });
  </script>
  <!-- Show & hide week choice -->
  <script type="text/javascript">
    $(".weekdayChoice").hide();
    jQuery(".switch input").click(function() {
        if( $("#isRegularRadioSwitch").is(':checked')) {
            $(".weekdayChoice").show();
        } else {
            $(".weekdayChoice").hide();
        }
    }); 
  </script>
  <!-- People quantity autovalidation -->
  <script type="text/javascript">
    $(document).ready(function() {
      jQuery("#male_quantity").change(function(){
        var men_q = parseInt($("#male_quantity").val(), 10);
        var women_q = parseInt($("#female_quantity").val(), 10);
        while (men_q+women_q>4){
          men_q--;
        }
        $("#male_quantity").val(men_q);
      });
      jQuery("#female_quantity").change(function(){
        var men_q = parseInt($("#male_quantity").val(), 10);
        var women_q = parseInt($("#female_quantity").val(), 10);
        while (men_q+women_q>4){
          women_q--;
        }
        $("#female_quantity").val(women_q);
      });
    });
  </script>

</body>
</html>