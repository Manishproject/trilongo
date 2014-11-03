<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */


$cakeDescription = __d('cake_dev', 'Trilongo transport-Haggle your way to the price you want transportation booking site!');
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <META HTTP-EQUIV="Cache-control" CONTENT="no-cache">
   <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <title>
        <?php echo $cakeDescription ?>:
        <?php echo $title_for_layout; ?>
    </title>
    <link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'/>

    <script type="text/javascript">
        var ajaxpath = '<?php echo SITE_URL?>';
        var SITE_URL = ajaxpath;
        var currently_in = '<?php echo isset($_SESSION['currently_in'])?$_SESSION['currently_in']:''?>';
    </script>
    <?php
    echo $this->Html->meta('icon');
    echo $this->fetch('meta');
    echo $this->Html->css(array('/jquery-ui-1.11.0.custom/jquery-ui.min', 'font-awesome', 'skdslider', 'trilongo'));
    echo $this->Html->script(array('jquery-1.8.3.min', '/jquery-ui-1.11.0.custom/jquery-ui.min', 'cus', 'parallax', 'skdslider', 'trilongo'));
    ?>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#demo').skdslider({'delay': 5000, 'fadeSpeed': 2000});
            $("a.anchorLink").anchorAnimate();

            $(".datepicker_new").datepicker({
                inline: true,
                minDate: 0
            });

        });
    </script>
    <?php echo $this->fetch('css');
    echo $this->fetch('script');
    ?>

    <link href="<?php echo SITE_URL ?>css/trilongo-scrn.css" rel="stylesheet">
</head>
<body>
<section id="wrapper">

    <div id="container">
        <header>
            <div id="header">
                <?php echo $this->element('header'); ?>
            </div>
        </header>

        <div id="content">
            <div class="inner-mid-top">
                <div class="inner-top-h-bg">
                    <div class="main-div">
                        <h1>
                            <?php echo $title_for_layout ?>
                        </h1>
                    </div>
                </div>


            </div>


            <div class="inner-mid-bottam">
                <div class="main-div">

                    <div class="error-message">
                        <?php echo $this->Session->flash(); ?>
                        <?php echo $this->Session->flash('success'); ?>
                        <?php echo $this->Session->flash('error'); ?>
                        <?php echo $this->Session->flash('info'); ?>
                    </div>

                    <div class="inner-content">
                        <?php echo $this->fetch('content'); ?>
                    </div>
                </div>
            </div>

        </div>

        <footer>
            <div id="footer">
                <?php echo $this->element('footer'); ?>
            </div>
        </footer>

    </div>
</section>


</body>
</html>