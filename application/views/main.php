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
  
	<section class="top-bar-section">
    
    <ul class="right title-area">
      <li class="divider"></li>
      <li class="has-dropdown not-click name">
        <a href="#" style="height:100%">
          <img style="height:80%; width:auto;" class="user_photo" src="<?php echo $_SESSION['pic_url'];?>">
        
            <?php echo $_SESSION['name'].' '.$_SESSION['surename']?>
        </a>
        <ul class="dropdown"><li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li><li class="parent-link hide-for-large-up"><a class="parent-link js-generated" href="#">USERNAME</a></li>
          <li><a href="#">Профиль</a></li>
          <li class=""><a href="#">Настройки</a></li>
          <li><a href="auth/logout">Выйти</a></li>
        </ul>
      </li>
      <li class="divider"></li>
      </ul>
      
	</section>

<?php } else { ?>
    <section class="top-bar-section">
    <ul class="right">
        <li class="divider"></li>
        <li class=""><a href="#" data-reveal-id="login_modal">Войти</a></li>
        <li class="divider"></li>
    </ul>
  </section>
  <div id="login_modal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
    <h2 id="modalTitle">Привет! Заходи к нам</h2>
    <!--p class="lead">Your couch.  It is mine.</p-->
    <a class="button" href="<?php echo $login_vk_link;?>"> Войти через VK </a>
    <a class="close-reveal-modal" aria-label="Close">&#215;</a>
  </div>
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
            <a href="carpool/driver/show_requests" class="button category_button" style="background-color:#339656;"> Найти попутчика</a>
            <a href="carpool/driver/add_route_page" class="button category_button" style="background-color:#339656;">Добавить поездку</a>
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
            <a href="carpool/passenger/show_routes" class="button category_button">  Найти поездку </a>
            <a href="carpool/passenger/pick_me" class="button category_button">  Подвези меня  </a>
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

  <script src="assets/js/vendor/jquery.js"></script>
  <script src="assets/js/foundation.min.js"></script>
  <script src="assets/js/foundation/foundation.topbar.js"></script>
  <script>
    $(document).foundation();
  </script>
</body>
</html>