<?php header("Content-type: text/css"); ?>
<?php header("Content-type: application/javascript"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xmlns:og="http://ogp.me/ns#"
      xmlns:fb="https://www.facebook.com/2008/fbml" data-placeholder-focus="false">
<head><!--
            <meta http-equiv="Cache-control" content="public">
            <meta http-equiv="imagetoolbar" content="false"/>
            <meta name="distribution" content="Global"/>
            <meta name="language" content="en-us"/>
            <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
            <META HTTP-EQUIV="Expires" CONTENT="-1">
    
        -->
    <meta http-equiv="Cache-control" content="public">
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1">
    <title><?php
        echo ucwords(strtolower($title_for_layout));
        if (isset($web_SEO['WebSetting']['web_name'])) {
            echo " : " . $web_SEO['WebSetting']['web_name'];
        }
        ?>
    </title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <?php
    echo $this->Html->css(array(
        'admin_css/labs',
        'admin_css/cake.generic',
        'admin_css/bootstrap.min',
        'admin_css/metro',
        'admin_css/bootstrap-responsive.min',
        'admin_css/font-awesome',
        'admin_css/style',
        'admin_css/style_responsive',
        'admin_css/style_default',
    ));
    echo $this->Html->script(array('admin_js/jquery-1.8.3.min.js'));
    echo $this->Js->writeBuffer(array('catch' => TRUE)); ?>
</head>

<body class="fixed-top">
<?php echo $this->element('admin_header'); ?>
<!-- BEGIN HEADER -->
<div class="page-container row-fluid">
    <?php echo $this->element('admin_menu'); ?>
    <?php //echo $this->Session->flash();   ?>
    <?php echo $this->fetch('content'); ?>
</div>
<!-- END HEADER -->
<?php echo $this->element('admin_footer');
echo $this->Html->script(array(
    'admin_js/excanvas',
    'admin_js/respond',
    'admin_js/breakpoints',
    'admin_js/jquery-ui-1.10.1.custom.min',
    'admin_js/jquery.slimscroll.min',
    'admin_js/fullcalendar.min',
    'admin_js/bootstrap.min',
    'admin_js/jquery.blockui',
    'admin_js/jquery.cookie',
    'admin_js/jquery.fancybox.pack',
    'admin_js/app',
));
?>
<script>
    jQuery(document).ready(function () {
        // initiate layout and plugins
        App.setPage("table_managed");
        App.init();
    });
</script>

</body>
<?php //echo $this->element('sql_dump');    ?>
</html>
