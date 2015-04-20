        </section>
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