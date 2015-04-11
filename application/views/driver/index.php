<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
  <meta charset="utf-8">
  <!-- If you delete this meta tag World War Z will become a reality -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Главная | Попутчики</title>

  <!-- If you are using the CSS version, only link these 2 files, you may add app.css to use for your overrides if you like -->
  <link rel="stylesheet" href="assets/css/normalize.css">
  <link rel="stylesheet" href="assets/css/foundation.css">

  <!-- If you are using the gem version, you need this only -->
  <link rel="stylesheet" href="assets/css/main_page.css">

  <script src="assets/js/vendor/modernizr.js"></script>

</head>
<body>

  <nav class="top-bar" data-topbar role="navigation">
    <ul class="title-area">
        <li class="name">
             <a href="#" style="height:100%"> <img style="height:100%" src="http://placehold.it/150x70"> </a>
        </li>
        <li class="toggle-topbar menu-icon"><a href="#"><span>Меню</span></a>
        </li>
    </ul>
<?php if(isset($_SESSION['user_id'])){?>
	<section class="top-bar-section right">
		<span style="color:white;"><?php echo $_SESSION['name'].' '.$_SESSION['surename']?></span><img style="width:70px; height: 70px;" src="<?php echo $_SESSION['pic_url'];?>">
	</section>
<?php } else { ?>
    <section class="top-bar-section">
    <ul class="right">
        <li class="divider"></li>
        <li class=""><a href="auth/index">Зарегистрироваться</a></li>
        <li class="divider"></li>
        <li class=""><a href="#">Войти</a></li>
        <li class="divider"></li>
    </ul>
  </section>
<?php }?>
  </nav>

  <header>
    <div class="small-12 columns"><div class="row" >
      <!-- For drivers -->
      <div class="small-6 columns " style="">
        <div class="row">
          <div class="small-12 columns text-center"> <h2 class="category_label"> Водителям: </h2> </div>
        </div>
        <div class="row ">
          <div class="small-12 columns text-center">
            <a href="driver/show_requests" class="button category_button" style="background-color:#339656;"> Найти попутчика</a>
            <a href="#" class="button category_button" style="background-color:#339656;">Добавить поездку</a>
          </div>
        </div>

      </div>

      <!-- For pedestrians -->
      <div class="small-6 columns " style="">
        <div class="row">
          <div class="small-12 columns text-center"> <h2 class="category_label"> Пешеходам: </h2> </div>
        </div>
        <div class="row">
          <div class="small-12 columns text-center">
            <a href="#" class="button category_button">  Найти поездку </a>
            <a href="passenger/pick_me" class="button category_button">  Подвези меня  </a>
          </div>
        </div>

      </div>
      
    </div>
    <!-- Bottom border - chevron -->
    <div class="row">
      <div class="small-3 columns small-centered" style="height: 2.45em;">
        <span class="chevron bottom"> </span>
      </div>
    </div></div>
  </header>

  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation.min.js"></script>
  <script src="js/foundation/foundation.topbar.js"></script>
  <script>
    $(document).foundation();
  </script>
</body>
</html>