<?php
$default_photo = array(
    'profile_photo' => 'Profile photo',
);
?>



<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>assets/uniform/css/uniform.default.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>assets/chosen-bootstrap/chosen/chosen.css"/>
<link rel="stylesheet" href="<?php echo SITEURL; ?>assets/data-tables/DT_bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>assets/uniform/css/uniform.default.css"/>

<!-- BEGIN PAGE -->
<div class="page-content">

    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">

                <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                <h3 class="page-title">All Default Photo
                    <small></small>
                </h3>

                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet box light-grey">


                    <div class="portlet-title">
                        <h4><i class="icon-globe"></i>Manage</h4>

                        <div class="tools">
                            <!--<a class="collapse" href="javascript:;"></a>
                            <a class="config" data-toggle="modal" href="#portlet-config"></a>
                            <a class="reload" href="javascript:;"></a>
                            <a class="remove" href="javascript:;"></a>
                            --></div>
                    </div>

                    <div id="LabMsg"></div>
                    <div class="portlet-body">
                        <?php echo $this->Session->flash('msg'); ?>
                        <div class="dataTables_filter" id="sample_1_filter">
                            <script>
                                $(document).ready(function () {
                                    $('#LabMsg').html('');
                                    $("#labs_ajax_search_btn").click(function (e) {
                                        call($("#labs_ajax_search").val());
                                    });
                                    $('#labs_ajax_search').live('keypress', function (e) {
                                        var p = e.which;
                                        if (p == 13) {
                                            call($("#labs_ajax_search").val());
                                        }
                                    });
                                    function call(txt) {
                                        //if(txt ==""){ $('#LabMsg').html('<div class="alert alert-error"><button data-dismiss="alert" class="close"></button><strong>Error!</strong> Enter search value.</div>'); }else{
                                        var datastring = "mssg=" + txt + "&from=users";
                                        $("#LabMsg").html('');
                                        $("#labs_ajax_search_btn").text('Searching...');
                                        $(function () {
                                            $.ajax({type: 'POST',
                                                url: '<?php echo SITEURL; ?>admin/labs/deal_search/',
                                                data: datastring,
                                                success: function (data) {
                                                    $("#labs_ajax_search_btn").text('Search!');
                                                    $("#AllUsers").html(data);
                                                },
                                                error: function (comment) {
                                                }});
                                        });

                                        //	}
                                    }
                                });
                                function change_status(id, type) {
                                    if (id != "") {
                                        var datastring = "D_id=" + id + "&type=" + type;
                                        $("#LabMsg").html('');

                                        $(function () {
                                            $.ajax({type: 'POST',
                                                url: '<?php echo SITEURL; ?>admin/labs/deal_update/',
                                                data: datastring,
                                                success: function (data) {
                                                    $("#LabMsg").html(data);
                                                },
                                                error: function (comment) {
                                                }});
                                        });
                                    }
                                }
                            </script>
                        </div>


                        <div class="clearfix">
                            <div class="btn-group">
                                <i class="icon-plus"></i>
                            </div>

                        </div>
                        <div class="table_header_main">
                            <table class="table table-striped table-bordered table-hover" id="sample_1">
                                <thead>
                                <tr>
                                    <th class="hidden-480">Name</th>
                                    <th class="hidden-480">Action</th>
                                </tr>
                                </thead>
                                <tbody id="AllUsers">

                                <?php
                                if (!empty($default_photo)) {
                                    foreach ($default_photo as $key => $default_photo_data) {
                                        ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $default_photo_data; ?></td>
                                            <td>
                                                <?php
                                                echo $this->Image->resize('data/' . $key . '/' . $key . '_default.png', 75, 75, true, false);
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a data-toggle="dropdown" href="#" class="btn purple">
                                                        <i class="icon-user"></i> Settings
                                                        <i class="icon-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li><?php echo $this->Html->link('<i class="icon-plus"></i> Edit', array('controller' => 'labs', 'action' => 'default_photo_edit/' . $key), array('class' => '', 'escape' => false)); ?></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                }
                                ?>


                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>

        <!-- END PAGE CONTENT-->
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->

<script type="text/javascript" src="<?php echo SITEURL; ?>assets/uniform/jquery.uniform.min.js"></script>
