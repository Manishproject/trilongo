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
            <h3 class="page-title">All users list
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
                <?php echo $this->Session->flash('msg'); ?>
                <div id="LabMsg"></div>
                <div class="portlet-body">
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
                                            url: '<?php echo SITEURL; ?>admin/labs/users_search/',
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
                                    var datastring = "uid=" + id + "&type=" + type + "&from=users";
                                    $("#LabMsg").html('');

                                    $(function () {
                                        $.ajax({type: 'POST',
                                            url: '<?php echo SITEURL; ?>admin/labs/users_update/',
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
                        <label>Search: <input type="text" class="m-wrap" id="labs_ajax_search">
                            <button id="labs_ajax_search_btn" type="button" class="btn green">Search!</button>
                        </label>

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
                                <?php /* ?><th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th> <?php */ ?>

                                <th><?php echo $this->Paginator->sort('id'); ?></th>
                                <th><?php echo $this->Paginator->sort('username'); ?></th>
                                <th class="hidden-480"><?php echo $this->Paginator->sort('email'); ?></th>
                                <th class="hidden-480"><?php echo $this->Paginator->sort('first_name'); ?></th>
                                <th class="hidden-480"><?php echo $this->Paginator->sort('last_name'); ?></th>
                                <th class="hidden-480"><?php echo $this->Paginator->sort('sex'); ?></th>
                                <th class="hidden-480"><?php echo $this->Paginator->sort('mobile'); ?></th>
                                <th class="hidden-480"><?php echo $this->Paginator->sort('status'); ?></th>
                                <th class="hidden-480"><?php echo $this->Paginator->sort('created'); ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="AllUsers">

                            <?php
                            if (!empty($users)) {
                                foreach ($users as $user) {
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $user['User']['id']; ?></td>
                                        <td><?php echo $user['User']['username']; ?></td>
                                        <td class="hidden-480"> <?php echo $user['User']['email']; ?></td>
                                        <td class="hidden-480"><?php echo $user['User']['first_name']; ?></td>
                                        <td class="hidden-480"><?php echo $user['User']['last_name']; ?></td>
                                        <td class="hidden-480"><?php echo $user['User']['gender']; ?></td>
                                        <td class="hidden-480"><?php echo $user['User']['phone']; ?></td>

                                        <td class="hidden-480" id="<?php echo "st_" . $user['User']['id']; ?>">
                                            <?php
                                            if ($user['User']['status'] == 0) {
                                                echo "Inactive";
                                            } elseif ($user['User']['status'] == 1) {
                                                echo "Active ";
                                            } elseif ($user['User']['status'] == 2) {
                                                echo "Unapproved";
                                            } elseif ($user['User']['status'] == 3) {
                                                echo "Hidden";
                                            } elseif ($user['User']['status'] == 4) {
                                                echo "Deactivate";
                                            } else {
                                                echo "Not available";
                                            }
                                            ?>
                                        </td>
                                        <td class="hidden-480"><?php echo $this->Lab->ShowDate($user['User']['created']); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a data-toggle="dropdown" href="#" class="btn purple">
                                                    <i class="icon-user"></i> Settings
                                                    <i class="icon-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <?php if ($user['User']['status'] == 2) { ?>
                                                        <li id="<?php echo "sp_" . $user['User']['id']; ?>"><a
                                                                href="javascript:void(0);"
                                                                onclick="change_status(<?php echo $user['User']['id']; ?>, 1)"><i
                                                                    class="icon-remove"></i> <span>Active</span> </a>
                                                        </li>
                                                    <?php } elseif ($user['User']['status'] == 0) { ?>
                                                        <li id="<?php echo "sp_" . $user['User']['id']; ?>"><a
                                                                href="javascript:void(0);"
                                                                onclick="change_status(<?php echo $user['User']['id']; ?>, 1)"><i
                                                                    class="icon-remove"></i> <span>Active</span> </a>
                                                        </li>
                                                    <?php } elseif ($user['User']['status'] == 1) { ?>
                                                        <li id="<?php echo "sp_" . $user['User']['id']; ?>"><a
                                                                href="javascript:void(0);"
                                                                onclick="change_status(<?php echo $user['User']['id']; ?>, 0)">
                                                                <iclass
                                                                ="icon-remove"></i> <span>Deactivate</span></a></li>
                                                    <?php } ?>
                                                    <li class="divider"></li>
<!--                                                    <li>-->
<!--                                                        <a href="--><?php //echo SITEURL . "admin/labs/view_profile_user/" . $user['User']['id'] ?><!--"><i-->
<!--                                                                class="i"></i> Full Profile</a>-->
<!--                                                    </li>-->
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="12" class="mid"> record not found</td>
                                </tr>
                            <?php } ?>


                            </tbody>
                        </table>
                    </div>

                    <div class="span6">
                        <div class="pagination pagination-large">
                            <?php echo $this->Paginator->numbers(array('first' => 2, 'last' => 2)); ?>
                        </div>
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
