<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>

<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>

<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>

<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js" type="text/javascript"></script>
<script src="catalog/view/javascript/paper-filter/paper-filter.js" type="text/javascript"></script>
<script src="catalog/view/javascript/paper-filter/touch-punch.js" type="text/javascript"></script>

<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>

<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>

<script src="catalog/view/javascript/common.js" type="text/javascript"></script>

<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>

<link href="catalog/view/javascript/paper-filter/paper-filter.css" rel="stylesheet" type="text/css" />
<link href="//code.jquery.com/ui/1.10.4/themes/flick/jquery-ui.css" rel="stylesheet" type="text/css" />

<link href="catalog/view/theme/default/stylesheet/mob.css" rel="stylesheet">

<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>

</head>

<body class="<?php echo $class; ?>"><br/>
<?php if ($categories) { ?>
<div class="container">
  <div class="col-sm-4" style="padding:0">
    <div id="logo">
      <!-- <?php if ($logo) { ?>
      <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
      <?php } else { ?>
      <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
      <?php } ?> -->
      <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
    </div>
  </div>
  <nav id="menu" class="navbar col-sm-8" style="padding:0">
    <div class="navbar-header">
      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><span id="category" class="visible-xs">Navigation</span> <i class="fa fa-chevron-circle-down"></i></button>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse" style="padding:0">
      <ul class="nav navbar-nav" style="width:100%;height:40px">
        <li class="menu-extra">
          <a href="index.php?route=account/<?= $logged ? 'logout' : 'login' ?>" class="see-all" style="border-radius:0"><i class="fa fa-sign-in"></i> <?= $logged ? 'Sign out' : 'Log in / Sign up' ?></a>
        </li>
        <li class="menu-extra">
          <a href="index.php?route=checkout/cart" class="see-all" style="margin-top:0"><i class="fa fa-shopping-cart"></i> Cart</a>
        </li>
        <li class="menu-extra">
          <a style="font-size:16px;padding:25px"><i class="fa fa-tasks"></i> <b>Categories</b></a>
        </li>
        <?php foreach ($categories as $key => $category) { ?>
            <?php if ($category['children']) { ?>
                <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
                  <div class="dropdown-menu">
                    <div class="dropdown-inner">
                      <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
                      <ul class="list-unstyled">
                        <?php foreach ($children as $child) { ?>
                        <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                        <?php } ?>
                      </ul>
                      <?php } ?>
                    </div>
                    <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?></a>
                  </div>
                </li>
            <?php } else { ?>
                <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
            <?php } ?>
        <?php } ?>

        <li class="dropdown pull-right menu-more" style="background:#1c85ac;position:absolute;right:0">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>More</b> <i class="fa fa-chevron-circle-down"></i></a>
          <div class="dropdown-menu">
            <div class="dropdown-inner">
              <ul class="list-unstyled"></ul>
            </div>
            <a href="index.php?route=account/<?= $logged ? 'logout' : 'login' ?>" class="see-all" style="border-radius:0">
                <?= $logged ? 'Sign out' : 'Log in / Sign up' ?>
            </a>
            <a href="index.php?route=checkout/cart" class="see-all">Cart / Checkout</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</div>
<?php } ?>
