<nav class="top-bar" data-topbar role="navigation" data-options="is_hover: true">
    <ul class="title-area">
        <li class="name">
             <a href="../../" style="height:100%"> <img style="height:100%" src="http://placehold.it/150x70"> </a>
        </li>
        <li class="toggle-topbar menu-icon"><a href="#"><span>Меню</span></a>
        </li>
    </ul>

    <section class="top-bar-section">
      <ul class="right">
          <li class="divider"></li>
            <li class="active has-dropdown"><a href="#"><i class="fa fa-user"></i> &nbsp; Я - пешеход   </a>
              <ul class="dropdown">
                <li class="active"><a  href="../driver/show_requests"><i class="fa fa-car"></i> &nbsp; Я - водитель  </a></li>
              </ul>
            </li>
          <li class="divider"></li>
          <li class="has-dropdown">
            <a href="#"><?php echo $_SESSION['name'].' '.$_SESSION['surename'];?></a>
            <ul class="dropdown">
              <li><a href="#">Профиль</a></li>
              <li class=""><a href="#">Настройки</a></li>
              <li><a href="../../auth/logout">Выйти</a></li>
            </ul>
          </li>
          <li class="divider"></li>
      </ul>
      <!--ul class="left">
        <li><a href="#">Добавить поездку</a></li>
      </ul-->
    </section>

	</nav>
		<section class="main-section">