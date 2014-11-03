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
                <h3 class="page-title"> User's Details
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
                        <?php if (!empty($data)){
                        ec($data); ?>
                        <div class="tab-pane ">
                            <div class="portlet box blue">
                                <div class="portlet-title">
                                    <h4>
                                        <i class="icon-reorder"></i><?php echo h($data['User']['first_name'] . " " . $data['User']['last_name']); ?>
                                    </h4>

                                </div>
                                <div class="portlet-body form">
                                    <?php echo $this->Session->flash('msg'); ?>
                                    <div id="lab_data"></div>
                                    <!-- BEGIN FORM-->
                                    <div id="tab_1_2" class="tab-pane profile-classic row-fluid active">
                                        <div class="span2"><img alt=""
                                                                src="<?php echo SITEURL; ?>assets/img/profile/profile-img.png">
                                            <a class="profile-edit" href="#">edit</a></div>
                                        <ul class="unstyled span10">
                                            <li><span>User Name:</span> <?php $data['User']['username']; ?></li>
                                            <li><span>First Name:</span> <?php $data['User']['first_name']; ?></li>
                                            <li><span>Last Name:</span> <?php $data['User']['last_name']; ?></li>
                                            <li><span>Counrty:</span></li>
                                            <li>
                                                <span>Birthday:</span> <?php echo $this->Lab->ShowDate($data['User']['dob']); ?>
                                            </li>
                                            <li><span>Occupation:</span> Web Developer</li>
                                            <li><span>Email:</span> <a href="#">john@mywebsite.com</a></li>
                                            <li><span>Interests:</span> Design, Web etc.</li>
                                            <li><span>Website Url:</span> <a href="#">http://www.mywebsite.com</a></li>
                                            <li><span>Mobile Number:</span> +1 646 580 DEMO (6284)</li>
                                            <li><span>About:</span> Anim pariatur cliche reprehenderit, enim eiusmod
                                                high life accusamus terry richardson ad squid. 3 wolf moon officia aute,
                                                non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                                                laborum eiusmod pariatur cliche reprehenderit, enim eiusmod high life
                                                accusamus terry richardson ad squid. 3 wolf moon officia aute, non
                                                cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                                                eiusmod.
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="form-actions">
                                        <?php if ($data['User']['status'] == 0) { ?>
                                            <a class="btn blue" href="javascript:void(0);"
                                               onclick="approve(<?php echo $data['Retailer']['id']; ?>,1)"><i
                                                    class="icon-plus"></i> Approve</a>
                                            <button type="button" class="btn blue"
                                                    onclick="approve(<?php echo $data['Retailer']['id']; ?>,3)"><i
                                                    class="icon-pencil"></i> Decline
                                            </button>
                                        <?php
                                        } else {
                                            ?>
                                        <?php } ?>

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
                                    url: '<?php echo SITEURL;?>admin/labs/change_status/',
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
   