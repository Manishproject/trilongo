<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo SITE_TITLE ?>
    </title>
    <?php
    echo $this->Html->meta('icon');
    echo $this->Html->css('admin_css/admin_login_styles');
    echo $this->Html->script('admin_js/jquery.min.js');
    echo $this->fetch('meta');

    ?>
</head>


<body class="login">
<div id="wraper_log"><img src="<?php echo SITE_URL ?>img/admin_img/ppl.png" alt="" class="ppl"/>
    <?php
    echo $this->fetch('content');
    ?>
</div>
</body>
</html>
