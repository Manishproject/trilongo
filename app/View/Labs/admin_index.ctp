<!-- BEGIN PAGE -->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div id="portlet-config" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button"></button>
            <h3>Widget Settings</h3>
        </div>
        <div class="modal-body">
            <p>Here will be a configuration form</p>
        </div>
    </div>
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">

                <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                <h3 class="page-title">
                    Dashboard
                    <small>statistics and more</small>
                </h3>
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home"></i>
                        <a href="index.html">Home</a>
                        <i class="icon-angle-right"></i>
                    </li>
                    <li><a href="#">Dashboard</a></li>
                    <li class="pull-right no-text-shadow">
                        <div id="dashboard-report-range"
                             class="dashboard-date-range tooltips no-tooltip-on-touch-device responsive" data-tablet=""
                             data-desktop="tooltips" data-placement="top"
                             data-original-title="Change dashboard date range">
                            <i class="icon-calendar"></i>
                            <span></span>
                            <i class="icon-angle-down"></i>
                        </div>
                    </li>
                </ul>
                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>

        <!-- END PAGE HEADER-->
        <div id="dashboard">
            <!-- BEGIN DASHBOARD STATS -->
            <div class="row-fluid">
                <div class="span3 responsive" data-tablet="span6" data-desktop="span3">
                    <div class="dashboard-stat blue">
                        <div class="visual">
                            <i class="icon-comments"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <?php echo @$consumer; ?>
                            </div>
                            <div class="desc">
                                All User
                            </div>
                        </div>
                        <a class="more" href="<?php echo SITEURL . "admin/labs/all_user" ?>">
                            View more <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>

                <div class="span3 responsive" data-tablet="span6" data-desktop="span3">
                    <div class="dashboard-stat yellow">
                        <div class="visual">
                            <i class="icon-bar-chart"></i>
                        </div>
                        <div class="details">
                            <div class="number"></div>
                            <div class="desc">All Pages</div>
                        </div>
                        <a class="more" href="<?php echo SITEURL . "admin/pages/homes/" ?>">
                            View more <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>

                <div class="span3 responsive" data-tablet="span6" data-desktop="span3">
                    <div class="dashboard-stat blue">
                        <div class="visual">
                            <i class="icon-comments"></i>
                        </div>
                        <div class="details">
                            <div class="number">
                                <?php echo $mail; ?>
                            </div>
                            <div class="desc">
                                Email Template
                            </div>
                        </div>
                        <a class="more" href="<?php echo SITEURL . "admin/mails/mails/index" ?>">
                            View more <i class="m-icon-swapright m-icon-white"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->

