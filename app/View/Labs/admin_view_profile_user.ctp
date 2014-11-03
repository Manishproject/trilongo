<!-- BEGIN PAGE -->
<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div id="portlet-config" class="modal hide">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button"></button>
            <h3>portlet Settings</h3>
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
                <h3 class="page-title"> Consumer's Details
                    <small></small>
                </h3>
            </div>
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row-fluid">
            <div class="span12">
                <div class="tabbable tabbable-custom boxless">

                    <div class="tab-content2">
                        <?php if (!empty($data)) { ?>
                        <div class="tab-pane ">
                            <div class="portlet box blue">
                                <div class="portlet-title">
                                    <h4><i class="icon-reorder"></i><?php echo h($data['Retailer']['business_name']); ?>
                                    </h4>

                                </div>
                                <div class="portlet-body form">
                                    <?php echo $this->Session->flash('msg'); ?>
                                    <div id="lab_data"></div>
                                    <!-- BEGIN FORM-->
                                    <div class="form-horizontal form-view">
                                        <h3></h3>

                                        <h3 class="form-section">User Info</h3>

                                        <div class="row-fluid">
                                            <div class="span6 ">
                                                <div class="control-group">
                                                    <label class="control-label" for="firstName">First Name:</label>

                                                    <div class="controls">
                                                        <span
                                                            class="text"><?php echo h($data['User']['first_name']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="span6 ">
                                                <div class="control-group">
                                                    <label class="control-label" for="lastName">Last Name:</label>

                                                    <div class="controls">
                                                        <span
                                                            class="text"><?php echo h($data['User']['last_name']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row-fluid">
                                            <div class="span6 ">
                                                <div class="control-group">
                                                    <label class="control-label">Gender:</label>

                                                    <div class="controls">
                                                        <span class="text"><?php echo h($data['User']['sex']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="span6 ">
                                                <div class="control-group">
                                                    <label class="control-label">Date of Birth:</label>

                                                    <div class="controls">
                                                            <span class="text bold"><?php
                                                                if (!empty($data['User']['dob'])) {
                                                                    echo date('m/d/Y', strtotime(h($data['User']['dob'])));
                                                                }
                                                                ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row-fluid">
                                            <div class="span6 ">
                                                <div class="control-group">
                                                    <label class="control-label">Email Address:</label>

                                                    <div class="controls">
                                                        <span
                                                            class="text bold"><?php echo h($data['User']['email']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="span6 ">
                                                <div class="control-group">
                                                    <label class="control-label">Phone number:</label>

                                                    <div class="controls">
                                                        <span
                                                            class="text bold"><?php echo h($data['User']['std_code'] . " " . $data['User']['mobile']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->

                                        <!--/span-->
                                    </div>
                                    <div class="form-actions">
                                        <?php echo $this->Html->link('Back', '/admin/labs/all_user', array('class' => 'btn')); ?>
                                    </div>
                                </div>
                                <!-- END FORM-->
                            </div>
                        </div>
                    </div>

                    <script type="text/javascript">
                        function approve(id, type) {

                            var datastring = "id=" + id + "&type=" + type + "";
                            $(function () {
                                $.ajax({type: 'POST',
                                    url: '<?php echo SITEURL; ?>admin/labs/change_status/',
                                    data: datastring,
                                    success: function (data) {
                                        $("#lab_data").html(data);
                                    }, error: function (comment) {
                                        $("#lab_data").html(comment);
                                    }});
                            });

                        }

                    </script>

                    <?php } else { ?>

                        <div class="alert alert-block alert-error fade in">
                            <button data-dismiss="alert" class="close" type="button"></button>
                            <h4 class="alert-heading">Error!</h4>

                            <p>Retailer not found. </p>

                        </div>


                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
<!-- END PAGE CONTAINER-->
</div>
