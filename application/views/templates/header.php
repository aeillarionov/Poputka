<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >

<head>
  <meta charset="utf-8">
  <!-- If you delete this meta tag World War Z will become a reality -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title;?></title>

  <!-- If you are using the CSS version, only link these 2 files, you may add app.css to use for your overrides if you like -->
  <link rel="stylesheet" href="<?php echo asset_url(); ?>css/normalize.css">
  <link rel="stylesheet" href="<?php echo asset_url(); ?>css/foundation.css">

  <!-- If you are using the gem version, you need this only -->
  <link rel="stylesheet" href="<?php echo asset_url(); ?>css/add_trip.css">
  <link rel="stylesheet" href="<?php echo asset_url(); ?>css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url(); ?>css/jquery.datetimepicker.css">

  <script src="<?php echo asset_url(); ?>js/vendor/modernizr.js"></script>
  <script src="<?php echo asset_url(); ?>js/vendor/jquery.js"></script>
  
  <!-- Yandex.Maps API -->
  <script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
  <script src="<?php echo asset_url(); ?>js/vendor/yandex_map.modules.js"></script>
  <script src="<?php echo asset_url(); ?>js/vendor/yandex_map.js"></script>
  <script src="<?php echo asset_url(); ?>js/vendor/geolocation_service.js"></script>
  
  <!-- Ajax functions -->
  <script src="<?php echo asset_url(); ?>js/ajax_functions.js"></script>

</head>
<body>