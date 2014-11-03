<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL ?>css/jquery.fancybox.css" media="screen"/>
<script type="text/javascript" src="<?php echo SITE_URL ?>js/jquery.fancybox.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.fancybox').fancybox({ type: 'ajax'});
    });
</script>
<div class="main-div">
    <div class="logo  animated fadeInDownBig">
        <a href="<?php echo SITE_URL ?>">
            <img src="<?php echo SITE_URL ?>images/logo.png" alt="Logo"/>
        </a>
    </div>
    <div class="right-top">
        <div class="top-r-link">
            <ul>
                <?php if (!$current_user['id']) { ?>
                    <li class="book-now"><a href="<?php echo SITE_URL . "users/login"; ?>" class="fancybox">Login </a></li>
                    <li class="book-now"><a href="<?php echo SITE_URL ?>#taber1">Book Now</a></li>
                    <li class="book-now  bacome_btn"><a href="<?php echo SITE_URL . "users/agent_signup"; ?>">Become A Provider</a></li>

                <?php
                } else {
                    if (ROLE == 3) {
                        ?>
                        <li class="book-now"><a href="<?php echo SITE_URL ?>providers/my_account" class="fancybox1">My Account</a></li>
                        <li class="book-now"><a href="<?php echo SITE_URL ?>users/logout" class="fancybox1">Logout</a></li>
                    <?php
                    } elseif (ROLE == 2) {
                        ?>
                        <li class="book-now"><a href="<?php echo SITE_URL ?>riders/my_account" class="fancybox1">My
                                Account</a></li>
                        <li class="book-now"><a href="<?php echo SITE_URL ?>users/logout" class="fancybox1">Logout</a></li>
                    <?php
                    } elseif (ROLE == 1) {
                        ?>
                        <li class="book-now"><a href="<?php echo SITE_URL ?>users/logout" class="fancybox1">Logout</a>
                        </li>
                    <?php
                    }
                    ?>

                <?php } ?>
                <li>
                    <div class="s-country">
                        <?php echo $this->Form->input('country', array('options' => $country_array, 'div' => false, 'class' => 'change_location select', 'label' => false));?>
                    </div>
                </li>
            </ul>
        </div>
        <div class="clear"></div>

        <nav>
          <span class="mobile_menu" id="click_menu">
             <i class="fa fa-align-justify"></i>
          </span>
            <ul id="open_menu">
                <li><a href="<?php echo SITE_URL."page/how-it-works"; ?>" class="introlink">How Trilongo Works </a></li>
                <li><a href="<?php echo SITE_URL."page/safety"; ?>" class="introlink">Safety </a></li>
                <li><a href="<?php echo SITE_URL ."page/about-trilongo";?>" class="introlink">About Trilongo</a></li>
                <li><a href="<?php echo SITE_URL."page/services"; ?>" class="introlink">Services</a></li>
                <li><a href="<?php echo SITE_URL."page/join-the-network"; ?>" class="introlink">Join the Network</a></li>
            </ul>
        </nav>
        <div class="clear"></div>
    </div>
</div>
<div class="feedback-btn">
    <a href="<?php echo SITE_URL . "users/feedback"; ?>" class="fancybox">
        <img src="<?php echo SITE_URL ?>images/feedback-btn.png" alt=""/>
    </a>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL ?>css/animate.css" media="screen"/>
<div class="clear"></div>
<script>
    $(function(){
        var url = window.location.href;
        var page = url.substr(url.lastIndexOf('/') + 1);
        $('ul#open_menu li a[href$="' + page + '"]').parent().addClass('active');
    })
</script>
