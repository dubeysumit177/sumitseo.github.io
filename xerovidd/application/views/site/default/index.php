<?php
/*
Theme Name: Default 
Unique Name: default
Theme URI: https://xerovidd.com
Author: Xerone IT
Author URI: https://xeroneit.net
Version: 1.0
Description: This is a default theme provided by the Author of XeroVidd. We highly recommend not to change core files for your customization needs. For your own customization, create your own theme as per our <a href="https://xeroneit.net/blog/xerochat-front-end-theme-development-manual" target="_BLANK">documentation</a>. 
*/
?>
<!DOCTYPE html>
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title><?php echo $this->config->item('product_name'); if($this->config->item('slogan')!='') echo " | ".$this->config->item('slogan')?></title>
	<meta name="description" content="<?php echo $this->config->item('slogan'); ?>">
	<meta name="author" content="<?php echo $this->config->item('institute_address1');?>">

	<!-- Mobile Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo base_url();?>assets/img/favicon.png">

    <!--====== STYLESHEETS ======-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/site_new/css/normalize.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/site_new/css/animate.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/site_new/css/modal-video.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/site_new/css/stellarnav.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/site_new/css/owl.carousel.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/site_new/css/slick.css">
    <link href="<?php echo base_url();?>assets/site_new/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/v4-shims.min.css">
    <link href="<?php echo base_url();?>assets/site_new/css/material-icons.css" rel="stylesheet">

    <!--====== MAIN STYLESHEETS ======-->
    <?php include("application/views/site/default/css/style.php"); ?>
    <link href="<?php echo base_url();?>assets/site_new/css/responsive.css" rel="stylesheet">

    <?php include(APPPATH."views/include/js_variables_front.php");?>

    <script src="<?php echo base_url();?>assets/site_new/js/vendor/modernizr-2.8.3.min.js"></script>
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="home-two" data-spy="scroll" data-target=".mainmenu-area" data-offset="90">

    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!--- PRELOADER -->
    <div class="preeloader">
        <div class="preloader-spinner"></div>
    </div>

    <!--SCROLL TO TOP-->
    <a href="#home" class="scrolltotop"><i class="fas fa-arrow-circle-up"></i></a>

    <!--START TOP AREA-->
    <header class="top-area" id="home">
        <div class="header-top-area">
            <!--MAINMENU AREA-->
            <div class="mainmenu-area" id="mainmenu-area">
                <div class="mainmenu-area-bg"></div>
                <nav class="navbar">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a href="#home" class="navbar-brand"><img class="max_height_45px" src="<?php echo base_url();?>assets/img/logo.png" alt="<?php echo $this->config->item('product_name');?>"></a>
                        </div>
                        <div id="main-nav" class="stellarnav">
                            <div class="search-and-signup-button white pull-right hidden-sm hidden-xs">
                                <a href="<?php echo site_url('home/login'); ?>" class="sign-up"><?php echo $this->lang->line('Login'); ?></a>
                            </div>
                            <ul id="nav" class="nav">
                                <li class="active">
                                    <a href="#home"><?php echo $this->lang->line('home'); ?></a>
                                </li>
                                <li>
                                    <a href="#features"><?php echo $this->lang->line('Features');?></a>
                                </li>
                                <li>
                                    <a href="#download"><?php echo $this->lang->line('Pricing'); ?></a>
                                </li>
                                <li <?php if($this->config->item('display_video_block') == '0') echo "class='hidden'"; ?>>
                                    <a href="#tutorial"><?php echo $this->lang->line('Tutorial');?></a>
                                </li>
                                <li>
                                    <a href="#contact"><?php echo $this->lang->line('Contact'); ?></a>
                                </li>
                                <li class="hidden-md hidden-lg">
                                    <a href="<?php echo site_url('home/login'); ?>"><?php echo $this->lang->line('Login'); ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <!--END MAINMENU AREA END-->
        </div>
        <div class="welcome-text-area white">
            <div class="area-bg"></div>
            <div class="welcome-area">
                <div class="container">
                    <div class="row flex-v-center">
                        <div class="col-md-7 col-lg-7 col-sm-12 col-12">
                            <div class="welcome-mockup center">
                                <img src="<?php echo base_url();?>assets/site_new/img/home/watch-mockup.png" alt="">
                            </div>
                        </div>
                        <div class="col-md-7 col-lg-7 col-sm-12 col-12">
                            <div class="welcome-text">
                                <h1><span><?php echo $this->config->item('product_name'); ?></span></h1>
                                <p><?php echo $this->lang->line("Most complete marketing solution for YouTube ever created."); ?></p>
                                <div class="home-button">
                                    <a href="#features"><?php echo $this->lang->line("detailed features"); ?></a>

                                    <a <?php if($this->config->item('enable_signup_form') =='0') echo "class='hidden'"; ?> href="<?php echo site_url('home/sign_up'); ?>"><?php echo $this->lang->line("Sign up now"); ?></a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--END TOP AREA-->

    <!--FEATURES TOP AREA-->
    <section class="features-top-area padding-100-50" id="features">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo $this->lang->line("Key Features").' : '.$this->config->item('product_name'); ?></h2>
                        <p><?php echo $this->lang->line("It's not just any marketing software for YouTube, it's the ultimate marketing software for YouTube."); ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-4 col-12">
                    <div class="qs-box relative mb50 center wow fadeInUp" data-wow-delay="0.2s">
                        <div class="qs-box-icon">
                            <i class="fas fa-reply"></i>
                        </div>
                        <h3><?php echo $this->lang->line("Comment Reply"); ?></h3>
                        <p><?php echo $this->lang->line("Has the feature of replying video comments."); ?></p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-4 col-12">
                    <div class="qs-box relative mb50 center wow fadeInUp" data-wow-delay="0.4s">
                        <div class="qs-box-icon">
                            <i class="far fa fa-trophy"></i>
                        </div>
                        <h3><?php echo $this->lang->line("Rank Tracking"); ?></h3>
                        <p><?php echo $this->lang->line("Tracks your contents rank on YouTube easily."); ?></p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-4 col-12">
                    <div class="qs-box relative mb50 center wow fadeInUp" data-wow-delay="0.5s">
                        <div class="qs-box-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3><?php echo $this->lang->line("Like, Comment & Subscription"); ?></h3>
                        <p><?php echo $this->lang->line("Helps you to like and comment on others video and auto subscribe/unsubscribe channels."); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--FEATURES TOP AREA END-->
 

    <!--FEATURES AREA-->
    <section class="features-area relative padding-100-50 gray-bg">
        <div class="container">
            <div class="row">
                <?php if($this->is_ad_enabled2 || $this->is_ad_enabled3)
                {
                    if($this->is_ad_enabled2) 
                    echo '<div class="col-12 col-md-3 add-300-250">'.$this->ad_content2.'</div>';
                    else 
                    echo '<div class="col-12 col-md-3 add-300-250">'.$this->ad_content3.'</div>';
                } ?>
                <div class="col-md-6 col-lg-6 <?php if(!$this->is_ad_enabled2 && !$this->is_ad_enabled3) echo "col-md-offset-3 col-lg-offset-3";?> col-sm-12 col-12">
                    <div class="area-title text-center wow fadeIn">                        
                        <?php if($this->is_ad_enabled2 || $this->is_ad_enabled3) echo '<div class="hidden-xs hidden-sm margin_top_50px"></div>';?>
                        <h2><?php echo $this->lang->line("detailed features"); ?></h2>
                        <p><?php echo $this->config->item('product_name').' '.$this->lang->line("can outrun any other YouTube marketing tools existing in the market."); ?></p>
                    </div>
                </div>
                <?php if($this->is_ad_enabled2 && $this->is_ad_enabled3)
                {
                    if($this->is_ad_enabled3) 
                    echo '<div class="col-12 col-md-3 add-300-250">'.$this->ad_content3.'</div>';
                } ?>
            </div>
            <?php if($this->is_ad_enabled2 || $this->is_ad_enabled3) echo '<div class="margin_top_50px"></div>';?>
            <br><br>
            <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12 col-12">
                    <div class="features-img center wow fadeIn mb50">
                        <img src="<?php echo base_url();?>assets/site_new/img/mockups/home-two-features-mockup.png" alt="">
                    </div>
                </div>
                <div class="col-md-8 col-lg-8 col-sm-12 col-12">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                            <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.2s">
                                <div class="qs-box-icon">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                                <h4><?php echo $this->lang->line("Comment Reply"); ?></h4>
                                <p><?php echo $this->lang->line("Has the feature of replying video comments."); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                            <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.2s">
                                <div class="qs-box-icon">
                                    <i class="fab fa-playstation"></i>
                                </div>
                                <h4><?php echo $this->lang->line("Playlist Manager"); ?></h4>
                                <p><?php echo $this->lang->line("Has the feature of managing your playlists much easier than ever."); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                            <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="far fa fa-trophy"></i>
                                </div>
                                <h4><?php echo $this->lang->line("Rank Tracking"); ?></h4>
                                <p><?php echo $this->lang->line("Has the feature of tracking your contents rank on YouTube easily."); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                            <div class="qs-box relative mb50 pos-icon-left  wow fadeInUp" data-wow-delay="0.2s">
                                <div class="qs-box-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <h4><?php echo $this->lang->line("Channel & Video Analytics"); ?></h4> 
                                <p><?php echo $this->lang->line("Has the feature of analytics of channels & videos."); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                            <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="far fa-comments"></i>
                                </div>
                                <h4><?php echo $this->lang->line("Like & Comment"); ?></h4>
                                <p><?php echo $this->lang->line("Helps you to like and comment on others video by searchig with keywords or channel."); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                            <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <h4><?php echo $this->lang->line("Subscription"); ?></h4>
                                <p><?php echo $this->lang->line("Helps to subscirbe your desired channels by searching with provided keywords."); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                            <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h4><?php echo $this->lang->line("Search Engine"); ?></h4>
                                <p><?php echo $this->lang->line("A customized search engine that will help you to search for video, playlists, channels."); ?></p>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                            <div class="qs-box relative mb50 pos-icon-left wow fadeInUp" data-wow-delay="0.3s">
                                <div class="qs-box-icon">
                                    <i class="fas fa-video"></i>
                                </div>
                                <h4><?php echo $this->lang->line("Video Upload"); ?></h4>
                                <p><?php echo $this->lang->line("Allows you to upload video to your channel easily."); ?></p>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </section>
    <!--FEATURES AREA END-->

   


    <?php if($this->is_ad_enabled && $this->is_ad_enabled1) : ?>    
        <div class="add-970-90 hidden-xs hidden-sm text-center bg_f5f4f4"><?php echo $this->ad_content1; ?></div> 
        <div class="add-320-100 hidden-md hidden-lg text-center bg_f5f4f4"><?php echo $this->ad_content1_mobile; ?></div> 
    <?php endif; ?> 

    <!--INTRO AREA-->
    <section class="intro-area section-padding relative">
        <div class="area-bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12 col-12">
                    <div class="intro-image wow fadeIn text-center">
                        <h3 class="hidden">Just Used For Validation</h3>
                        <img src="<?php echo base_url();?>assets/site_new/img/mockups/home-two-promo-mockup.png" alt="" class="just_validation">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--INTRO AREA END-->

    <!--WORK AREA-->
    <section class="work-area section-padding padding_bottom_50px" id="work">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo $this->lang->line("Comment Reply"); ?></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo $this->lang->line("Comment Reply is one of the key features that makes this software so special"); ?></p>
                    </div>
                </div>
            </div>
            <div class="row flex-v-center">
                <div class="col-md-4 col-lg-4 col-sm-6 col-12">
                    <div class="qs-box pos-icon-right mb100 wow fadeIn">
                        <div class="qs-box-icon">
                            <img src="<?php echo base_url();?>assets/site_new/img/icon/icon-1.png" alt="">
                        </div>
                        <h4><?php echo $this->lang->line("Import Channel"); ?></h4>
                        <p><?php echo $this->lang->line("Import your YouTube channel just click on Login With YouTube button."); ?></p>
                    </div>
                    <div class="qs-box  pos-icon-right wow fadeIn xs-mb50">
                        <div class="qs-box-icon">
                            <img src="<?php echo base_url();?>assets/site_new/img/icon/icon-2.png" alt="">
                        </div>
                        <h4><?php echo $this->lang->line("Comment Template"); ?></h4>
                        <p><?php echo $this->lang->line("You can reply to your videos comment using comment template."); ?></p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-4 col-12 hidden-xs hidden-sm">
                    <div class="service-image text-center wow fadeIn xs-mb50">
                        <img src="<?php echo base_url();?>assets/site_new/img/mockups/home-two-work-mockup.png" alt="">
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-6 col-12 pull-left">
                    <div class="qs-box  pos-icon-left mb100 wow fadeIn">
                        <div class="qs-box-icon">
                            <img src="<?php echo base_url();?>assets/site_new/img/icon/icon-3.png" alt="">
                        </div>
                        <h4><?php echo $this->lang->line("Create Campaigns"); ?></h4>
                        <p><?php echo $this->lang->line("You can create 'Comment Reply' campaign for all your YouTube videos."); ?></p>
                    </div>
                    <div class="qs-box pos-icon-left wow fadeIn">
                        <div class="qs-box-icon">
                            <img src="<?php echo base_url();?>assets/site_new/img/icon/icon-4.png" alt="">
                        </div>
                        <h4><?php echo $this->lang->line("Full Delivery Reports"); ?></h4>
                        <p><?php echo $this->lang->line("You will able to see the full campaign delivery reports,where you will find the final report."); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--ABOUT AREA-->
    <section class="about-area gray-bg section-padding" id="app">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo $this->lang->line("About Our App"); ?></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo $this->lang->line("Boost your business leveraging the power of YouTube."); ?></p>
                    </div>
                </div>
            </div>
            <div class="row flex-v-center">
                <div class="col-md-6 col-lg-6 col-sm-12 col-12">
                    <div class="about-content sm-mb50 sm-center">
                        <h4 class="mb30"><?php echo $this->config->item('product_name').' '." - ". $this->lang->line("Most complete video marketing solution ever created."); ?></h4>
                        <p><?php echo $this->lang->line("Is a very new and surprising invention for internet marketing. YouTube is providing some technique for marketing that are hidden and nobody implemented yet. YouTube doesn’t provide any front-end interface to use these powerful features. It’s only possible by using some good programming knowledge. The most interesting part is, it’s totally no cost marketing that is far far effective than other social marketing."); ?></p>
                        <a href="#video" class="video-button mt30 inline-block"><i class="fas fa-play"></i> <?php echo $this->lang->line("Watch Promo Video"); ?></a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-12">
                    <div class="about-mockup center wow fadeIn xs-mt50">
                        <img src="<?php echo base_url();?>assets/site_new/img/mockups/home-two-about-mockup.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--ABOUT AREA END-->

    <!--PROMO AREA-->
    <section class="<?php if($this->config->item('display_video_block') == '0' || $this->config->item('promo_video') == '') echo 'hidden';?> promo-area relative section-padding" id="video">
        <div class="area-bg"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-12">
                    <div class="area-title center white wow fadeIn">
                        <h2><?php echo $this->lang->line("Explore The Best Promo Video"); ?></h2>
                        <p><?php echo $this->lang->line("See the super promo video"); ?></p>
                    </div>
                </div>
            </div>
            <?php 
                $link = $this->config->item('promo_video');
                $final = trim(str_replace('https://www.youtube.com/watch?v=','',$link));
             ?>
            <div class="row">
                <div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-12">
                    <div class="promo-area-content center white wow fadeIn">
                        <div class="video-promo-slider">
                            <div class="single-video-promo-slide">
                                <img src="<?php echo base_url();?>assets/site_new/img/promo/video-promo-slide-1.png" alt="">
                                <div class="video-play-button">
                                    <button data-video-id="<?php echo $final; ?>" class="video-area-popup"><i class="fas fa-play-circle"></i></button>
                                </div>
                            </div>
                            <div class="single-video-promo-slide">
                                <img src="<?php echo base_url();?>assets/site_new/img/promo/video-promo-slide-1.png" alt="">
                                <div class="video-play-button">
                                    <button data-video-id="<?php echo $final; ?>" class="video-area-popup"><i class="fas fa-play-circle"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--PROMO AREA END-->

    <!--SCREENSHOT AREA-->
    <section class="screenshot-area section-padding" id="screenshot">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo $this->lang->line("App Screenshots"); ?></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo $this->lang->line("Here are some screenshots of how it looks. See the amazing shots and enjoy."); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row flex-v-center">
                <div class="col-md-6 col-lg-6 col-sm-12 col-12">
                    <div class="screenshot-slider-area wow fadeIn xs-mb50">
                        <div class="screenshot-slider-2">
                            <div class="single-screenshot">
                                <img src="<?php echo base_url("assets/site_new/img/screenshot/screenshot-1.jpg");?>" alt="">
                            </div>
                            <div class="single-screenshot">
                                <img src="<?php echo base_url("assets/site_new/img/screenshot/screenshot-2.jpg");?>" alt="">
                            </div>
                            <div class="single-screenshot">
                                <img src="<?php echo base_url("assets/site_new/img/screenshot/screenshot-3.jpg");?>" alt="">
                            </div>
                            <div class="single-screenshot">
                                <img src="<?php echo base_url("assets/site_new/img/screenshot/screenshot-4.jpg");?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="<?php if($this->is_ad_enabled4) echo 'col-md-3 col-lg-3'; else echo 'col-md-4 col-lg-4';?> col-sm-12 col-12">
                    <div class="screenshot-content xs-center sm-center xs-mt50 sm-mt50">
                        <h2><?php echo $this->lang->line("Awesome App"); ?></h2>
                        <p><?php echo $this->lang->line("World's very first and very fast comment replier to your video's comment. We explored and implemented all YouTube APIs available and brought to you the best out of the best. Everything you wanted to do with your channel is now possible."); ?></p>
                    </div>
                </div>
                <?php 
                if($this->is_ad_enabled4) echo '<div class="col-12 col-sm-12 col-md-3 col-lg-3 add-300-600">'.$this->ad_content4.'</div>';
                ?>
            </div>
        </div>
    </section>
    <!--SCREENSHOT AREA END-->

    <!--DOWNLOAD AREA-->
    <section class="download-area section-padding relative white" id="download">
        <div class="area-bg" data-stellar-background-ratio="0.6"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-6 col-12">
                    <div class="download-content sm-center xs-center xs-mb50 xs-font wow fadeIn">
                        <h2><?php echo $this->lang->line("Get the greatest app !"); ?></h2>
                        <p><?php echo $this->lang->line("We are providing trial package so you can try first. Click on the button and explore it."); ?></p>
                        <?php if(isset($default_package[0])) : ?>
                        	<a href="<?php echo site_url('home/sign_up'); ?>" class="download-button wow shake <?php if($this->config->item('enable_signup_form') == '0') echo "hidden"; ?>"><i class="fas fa-shopping-cart"></i><?php echo $this->lang->line("Free Trial"); ?> <span><?php echo $default_package[0]["validity"] ?> <?php echo $this->lang->line("Days"); ?></span></a>
                    	<?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-md-offset-4 col-lg-offset-4 col-sm-6 col-12">
                    <div class="download-content sm-center xs-center wow fadeIn">
                        <h2><?php echo $this->lang->line("Amazing Prices"); ?></h2>
                        <p><?php echo $this->lang->line("Greatest marketing software for YouTube with very reasonable prices! Explore the plans and get the best before price goes high."); ?></p>
                        <a href="#pricing" class="download-button wow shake"><i class="fas fa-dollar"></i><?php echo $this->lang->line("Get the app"); ?> <span><?php echo $this->lang->line("Price Plans"); ?></span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--DOWNLOAD AREA END-->

	<!--PRICING AREA-->
    <?php
    if(!empty($pricing_table_data)) : 
    ?>
	<section class="price-area padding-100-70 sky-gray-bg" id="pricing">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo $this->lang->line("Pricing"); ?> <span><?php echo $this->lang->line("Table"); ?></span></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo $this->lang->line("Get the World’s very first,most powerful and complete marketing software with very reasonable price."); ?></p>
                    </div>
                </div>
            </div>

		<!-- starting of table row -->
        <div class="row">
            <?php 
                $i=0;
                $classes=array(1=>"tiny",2=>"small",3=>"medium",4=>"pro");
                foreach($pricing_table_data as $pack) :    
                $i++;   
            ?>

            <div class="col-md-4 col-lg-4 col-sm-6 col-12">
                <div class="single-price center wow fadeInUp" data-wow-delay="0.2s">
                    <div class="price-hidding">
                        <h4><?php echo $pack["package_name"]; ?></h4>
                    </div>
                    <div class="price-rate" <?php if($pack["highlight"]=='1') echo 'style="background:'.$THEMECOLORCODE.'"'; ?>>
                        <h3 <?php if($pack["highlight"]=='1') echo 'class="white_color"'; ?>>
                            <br>
                            <sup><?php echo $curency_icon; ?></sup><?php echo $pack["price"]?>
                            <sub><?php echo $pack["validity"]?> <?php echo $this->lang->line("days"); ?></sub>
                            <br><br>
                        </h3>
                        
                    </div>
                    <div class="price-details scrollit text-left price_table_scroll_css">
                        <ul>
                            <?php 
                                $module_ids=$pack["module_ids"];
                                $monthly_limit=json_decode($pack["monthly_limit"],true);
                                $module_names_array=$this->basic->execute_query('SELECT module_name,id FROM modules WHERE FIND_IN_SET(id,"'.$module_ids.'") > 0  ORDER BY module_name ASC');

                                foreach ($module_names_array as $row) : ?>
                                <li>
                                    <i class="fas fa-circle"></i>&nbsp;
                                    <?php 
                                        $limit=0;
                                        $limit=$monthly_limit[$row["id"]];

                                        if($limit=="0") 
                                            $limit2="<b>".$this->lang->line("unlimited")."</b>";
                                        else 
                                            $limit2=$limit;

                                        if($row["id"]!="1" && $limit!="0") 
                                            
                                            $limit2="<b>".$limit2."/".$this->lang->line("month")."</b>";
                                            echo $this->lang->line($row["module_name"]);

                                        if($row["id"]!="13" && $row["id"]!="14" && $row["id"]!="16") 
                                            echo " : <b>". $limit2."</b>"."<br>";
                                        else 
                                            echo "<br>";
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="buy-now-button <?php if($this->config->item('enable_signup_form') == '0') echo "hidden"; ?>">
                        <a href="<?php echo site_url('home/sign_up'); ?>" class="read-more"><?php echo $this->lang->line('sign up'); ?></a>
                    </div>
                </div>
            </div>
    
            <?php
                // if($i%4==0) break;
                endforeach;
            ?>
        </div> <!-- end of table row -->
        </div>
    </section>
     <?php endif; ?>
    <!--PRICING AREA END-->

    <!--Review AREA-->
    <section class="<?php if($this->config->item('display_review_block') == '0') echo 'hidden';?> video-area section-padding style-two" id="team">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-12">
                    <div class="area-title text-center wow fadeIn visibility_animation">
                        <h2><span><?php echo $this->lang->line("Reviews"); ?></span></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                    </div>
                </div>
            </div>
            <div class="row flex-v-center">
                <!-- Demo video section -->
                <?php 
                    $demo = $this->config->item('customer_review_video');
                    $customer_review_video = trim(str_replace('https://www.youtube.com/watch?v=','',$demo));
                ?>
                <div class="col-md-6 col-lg-6 col-sm-12 col-12 <?php if($this->config->item('customer_review_video') == '') echo 'hidden';?>">
                    <div class="video-area-content wow fadeIn sm-mb50 xs-mb50">
                        <img src="<?php echo base_url();?>assets/site_new/img/video/review-bg.jpg" alt="">
                        <button data-video-id="<?php echo $customer_review_video; ?>" class="video-area-popup"><i class="fas fa-play"></i></button>
                        <h4 class="demo-title-area customer_review_css"><?php echo $this->lang->line('Customer review Video'); ?></h4>
                    </div>
                </div>
                <!-- End of demo video section -->

                <div class="<?php if($this->config->item('customer_review_video') == '') echo 'col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-12'; else echo 'col-md-6 col-lg-6 col-sm-12 col-12';?>">
                    <div class="team-member-content wow fadeIn">
                        <div class="team-member-list team-slider">
	                        <?php 
                                $customerReview = $this->config->item('customer_review');
                                $ct=0;
							    foreach($customerReview as $singleReview) : 
                                $ct++;
                                $original = $singleReview[2];
                                $base     = base_url();

                                if (substr($original, 0, 4) != 'http') {
                                    $img = $base.$original;
                                } else {
                                   $img = $original;
                                }

                            ?>
                                <div class="single-team height_200px">
                                    <div class="member-image">
                                        <img src="<?php echo $img; ?>" alt="reviewer">
                                    </div>
                                    <div class="name-and-designation">
                                        <h4><?php echo $singleReview[0]; ?></h4>
                                        <p><?php echo $singleReview[1]; ?></p>
                                        <p class="justify_normal">
                                            <?php 
                                                if(strlen($singleReview[3]) > 200 ) {
                                                    $str = substr($singleReview[3],0,180);
                                                    echo $str.". . ."."<a class='exe' type='button' data-toggle='modal' data-target=#myModal".$ct.">see more</a>";
                                                
                                                } else {
                                                    echo $str = $singleReview[3];
                                                }
                                                
                                            ?>
                                        </p>
                                    </div>
                                    <div class="member-details">
                                    </div>
                                </div>
	                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Review AREA END-->

    <!--Tutorial AREA-->
    <section class="<?php if($this->config->item('display_video_block') == '0') echo 'hidden';?> blog-feed-area padding-100-70 gray-bg" id="tutorial">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-12">
                    <div class="area-title text-center wow fadeIn">
                        <h2><?php echo $this->lang->line("Video Walkthrough"); ?></h2>
                        <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                        <p><?php echo $this->lang->line('Watch the latest videos of our app which may help you to get going.'); ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
            	<?php 
                $custom_videos = $this->config->item('custom_video'); 
        		foreach($custom_videos as $customVideo) : 
                    $original_video = $customVideo[0];
                    $baseurl        = base_url();

                    if (substr($original_video,0,4) != 'http') {
                        $thumb = $baseurl.$original_video;
                    } else {
                        $thumb = $original_video;
                    }
                ?>
                <div class="col-md-3 col-lg-3 col-sm-6 col-12">
                    <div class="single-blog mb30 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="blog-thumb width100">
                            <a target="_blank" href="<?php echo $customVideo[2]; ?>"><img class="height_150px" src="<?php echo $thumb;?>" alt="">
                            	<button class="video-area-popup-new"><i class="fas fa-play-circle"></i></button></a>

                        </div>
                        <div class="blog-details padding-30 border height_100px">
                            <h4  class="text-center" title="<?php echo $customVideo[1]; ?>">
                            	<a target="_blank" href="<?php echo $customVideo[2]; ?>">
                            		<?php 
                            			$videotitle = $customVideo[1];
                            			if(strlen($videotitle) > 50) {
                            				$substring = substr($videotitle,0,48);
                            				echo $substring."...";
                            			} else {
                            				echo $videotitle;
                            			}
                            		?>
                            		
                            	</a>
                            </h4>
                        </div>
                    </div>
                </div>
        		<?php endforeach; ?>
            </div>
        </div>
    </section>
    <!--Tutorial AREA END-->

    <!--CONTACT US AREA-->
    <section class="contact-area relative padding-100-50 <?php if($this->config->item('display_video_block') == '0' && $this->config->item('display_review_block') == '0' ) echo 'bg_ffffff'; elseif($this->config->item('display_video_block') == '0') echo 'bg_f5f4f4'; else echo 'bg_ffffff'; ?>" id="contact">
        <div class="contact-form-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3 col-sm-12 col-12">
                        <div class="area-title text-center wow fadeIn">
                            <h2><?php echo $this->lang->line('Contact Us');?></h2>
                            <span class="icon-and-border"><i class="material-icons">phone_android</i></span>
                            <p><?php echo $this->lang->line('Feel free to contact with us.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-12">
                    	<div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-12">
                                <div class="form-group" id="name-field">
                                    <?php 
										if($this->session->userdata('mail_sent') == 1) {
										echo "<div class='alert alert-success text-center'>".$this->lang->line("we have received your email. we will contact you through email as soon as possible")."</div>";
										$this->session->unset_userdata('mail_sent');
										}
									?>
                                </div>
                            </div>
                    	</div>
                        <div class="contact-form mb50 wow fadeIn">
                            <form action="<?php echo site_url("home/email_contact"); ?>" method="post">
                                <div class="row">
                                    <div class="col-md-5 col-lg-5 col-sm-12 col-12">
                                        <div class="form-group" id="email-field">
                                            <div class="form-input">
                                                <input type="email" class="form-control" required id="email" <?php echo set_value("email"); ?> placeholder="<?php echo $this->lang->line("email");?>" name="email">
                                            </div>
                                            <span class="red"><?php echo form_error("email"); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-lg-5 col-sm-12 col-12">
                                        <div class="form-group" id="phone-field">
                                            <div class="form-input">
                                                <input type="text" class="form-control" required id="subject" <?php echo set_value("subject"); ?> placeholder="<?php echo $this->lang->line("message subject");?>" name="subject">
                                            </div>
                                            <span class="red"><?php echo form_error("subject"); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-12 col-12">
                                        <div class="form-group" id="message-field">
                                            <div class="form-input">
                                                <input type="number" class="form-control" step="1" required id="captcha" <?php echo set_value("captcha"); ?> placeholder="<?php echo $contact_num1. "+". $contact_num2." = ?"; ?>" name="captcha">
													<span class="red">
														<?php 
														if(form_error('captcha')) 
															echo form_error('captcha'); 
														else  
														{ 
															echo $this->session->userdata("contact_captcha_error"); 
															$this->session->unset_userdata("contact_captcha_error"); 
														} 
														?>
													</span>
                                            	</div>
                                            <span class="red"><?php echo form_error("message") ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-12">
                                        <div class="form-group" id="message-field">
                                            <div class="form-input">
                                                <textarea class="form-control" rows="3" required id="message" <?php echo set_value("message"); ?> placeholder="<?php echo $this->lang->line("message");?>" name="message"></textarea>
                                            </div>
                                            <span class="red"><?php echo form_error("message") ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12 col-lg-12 col-sm-12 col-12">
                                        <div class="form-group center">
                                            <button type="submit"><?php echo $this->lang->line("Send Message");?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-12 col-md-2 padding_top_10">
                        <h4><?php echo $this->config->item("institute_address1"); ?></h4>
                        <h5><?php echo $this->config->item("institute_address2"); ?></h5>
                        <h5><?php echo $this->lang->line("Email")." : ".$this->config->item("institute_email"); ?></h5>
                        <h5><?php echo $this->lang->line("Mobile")." : ".$this->config->item("institute_mobile"); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--CONTACT US AREA END-->

    <!--FOOER AREA-->
    <footer class="footer-area white relative">
        <div class="area-bg"></div>
        <div class="footer-bottom-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3 col-sm-12 col-12">
                        <div class="footer-social-bookmark text-center section-padding wow fadeIn">
                            <div class="footer-logo mb50 hidden-xs">
                                <a href="#"><img src="<?php echo base_url();?>assets/img/logo.png" alt="logo" class="max_height_70px"></a>
                            </div>
                            <p><?php echo $this->lang->line("Most complete marketing solution for YouTube ever created."); ?></p>
                            <?php if(!empty($this->config->item('institute_address1'))) echo "<br><br><br><h3>".$this->config->item('institute_address1').'</h3>';?>
                            <?php if(!empty($this->config->item('institute_address2'))) echo "<i class='fas fa-map-marker-alt'></i> ".$this->config->item('institute_address2').'<br>';?>
                            <?php if(!empty($this->config->item('institute_email'))) echo "<i class='fas fa-envelope'></i> ".$this->config->item('institute_email');?>
                            <?php if(!empty($this->config->item('institute_mobile'))) echo " <i class='fas fa-phone'></i> ".$this->config->item('institute_mobile').'<br>';?>
                            <?php 
                                $facebook = $this->config->item('facebook');
                                $twitter  = $this->config->item('twitter');
                                $linkedin = $this->config->item('linkedin');
                                $youtube  = $this->config->item('youtube');

                                if($facebook=='' && $twitter=='' && $linkedin=='' && $youtube=='') $cls='hidden';
                            ?>
                            <ul class="social-bookmark mt50 <?php if(isset($cls)) echo $cls; ?>">
                                <li <?php if($facebook=='') echo "class='hidden'"; ?>>
                                	<a title="Facebook" target="_blank" class="facebook" href="<?php echo $facebook; ?>"><i class="fab fa-facebook"></i>
                                	</a>
                                </li>
                                <li <?php if($twitter=='') echo "class='hidden'"; ?>>
                                	<a title="Twitter" target="_blank" class="twitter" href="<?php echo $twitter; ?>"><i class="fab fa-twitter"></i>
                                	</a>
                                </li>
                                <li <?php if($linkedin=='') echo "class='hidden'"; ?>>
                                	<a title="Linkedin" target="_blank" class="linkedin" href="<?php echo $linkedin; ?>"><i class="fab fa-linkedin"></i>
                                	</a>
                                </li>
                                <li <?php if($youtube=='') echo "class='hidden'"; ?>>
                                	<a title="Youtube" target="_blank" class="youtube" href="<?php echo $youtube; ?>"><i class="fab fa-youtube"></i>
                                	</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-12">
                        <div class="footer-copyright text-center wow fadeIn padding_bottom_55px">
                            <p>
                            	<?php echo $this->config->item("product_short_name")." ".$this->APP_VERSION; ?>  | <?php echo $this->lang->line("Copyright"); ?> &copy; <a target="_blank" href="<?php echo site_url(); ?>"><?php echo $this->config->item("institute_address1"); ?></a></p>
                        	<p class="text-center font_size_10px">
								<a href="<?php echo base_url('home/privacy_policy'); ?>" target="_blank"><?php echo $this->lang->line("Privacy Policy"); ?></a> | <a href="<?php echo base_url('home/terms_use'); ?>" target="_blank"><?php echo $this->lang->line("Terms of Service"); ?></a> | <a href="<?php echo base_url('home/gdpr'); ?>" target="_blank"><?php echo $this->lang->line("GDPR Compliant"); ?></a>
							</p>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </footer>
    <!-- COOKIES -->
    <?php if($this->session->userdata('allow_cookie')!='yes') : ?>
        <div class="text-center cookiealert">
            <div class="cookiealert-container">
                <a class="cookie_content_css" href="<?php echo base_url('home/privacy_policy#cookie_policy');?>">
                    <?php echo $this->lang->line("This site requires cookies in order for us to provide proper service to you.");?>
                </a>
                <a type="button" href="#" class="btn btn-warning btn-sm acceptcookies black_color" aria-label="Close">
                    <?php echo $this->lang->line("Got it !"); ?>
                </a>

            </div>
        </div>
    <?php endif; ?>
    <!-- /COOKIES -->
    <!--FOOER AREA END-->


    <!--====== SCRIPTS JS ======-->
    <script src="<?php echo base_url('assets/site_new/js/vendor/jquery-1.12.4.min.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/vendor/bootstrap.min.js');?>"></script>

    <!--====== PLUGINS JS ======-->
    <script src="<?php echo base_url('assets/site_new/js/vendor/jquery.easing.1.3.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/vendor/jquery-migrate-1.2.1.min.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/vendor/jquery.appear.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/owl.carousel.min.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/slick.min.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/stellar.js');?>"></script>
    <script src="<?php echo base_url('');?>assets/site_new/js/wow.min.js"></script>
    <script src="<?php echo base_url('assets/site_new/js/jquery-modal-video.min.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/stellarnav.min.js');?>"></script>
    <script src="<?php echo base_url('assets/site_new/js/contact-form.js');?>"></script>
    <script src="<?php echo base_url('');?>assets/site_new/js/jquery.ajaxchimp.js"></script>
    <script src="<?php echo base_url('assets/site_new/js/jquery.sticky.js');?>"></script>
    <script src="<?php echo base_url(); ?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>

    <!--===== ACTIVE JS=====-->
    <script src="<?php echo base_url();?>assets/site_new/js/main.js"></script>

    <!-- cookiealert section -->

    <?php $this->load->view("include/fb_px"); ?> 
    <?php $this->load->view("include/google_code"); ?> 
    
</body>
</html>

<script src="<?php echo base_url('assets/js/system/site_default.js');?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/css/system/site_default.css');?>">


<!-- Modal -->
<?php   
    $ct=0;
    foreach($customerReview as $singleReview) : 
        $ct++;
        $original = $singleReview[2];
        $base     = base_url();

        if (substr($original, 0, 4) != 'http') {
            $img = $base.$original;
        } else {
           $img = $original;
        }
?>

    <div id="myModal<?php echo $ct; ?>" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title font_weight_bold"><?php echo $this->lang->line('Full Review'); ?></h4>
            </div>
            <div class="single-item text-center margin_top_10px">
                <div class="member-image">
                    <img class="img-circle img-thumbnail" src="<?php echo $img; ?>" alt="reviewer">
                </div>
                <div class="modal-body name-and-designation margin_top_10px">
                    <h4><?php echo $singleReview[0]; ?></h4>
                    <p><?php echo $singleReview[1]; ?></p>
                    <p class="justify_normal_black_padded"><?php echo $singleReview[3]; ?></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
            </div>
        </div>

      </div>
    </div>
<?php endforeach; ?>
<!-- End of Modal -->

<link rel="stylesheet" href="<?php echo base_url('assets/css/system/inline.css');?>">