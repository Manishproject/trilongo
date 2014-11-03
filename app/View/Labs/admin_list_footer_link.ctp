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
                <h3 class="page-title">All Footer Page
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
                                        var datastring = "L_id=" + id + "&type=" + type;
                                        $("#LabMsg").html('');

                                        $(function () {
                                            $.ajax({type: 'POST',
                                                url: '<?php echo SITEURL; ?>admin/labs/update_footer_link/',
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
                                    <th><?php echo $this->Paginator->sort('id'); ?></th>
                                    <th class="hidden-480"><?php echo $this->Paginator->sort('link_name', 'Link Name'); ?></th>
                                    <th class="hidden-480"><?php echo $this->Paginator->sort('category_name', 'Category Name'); ?></th>
                                    <th class="hidden-480"><?php echo $this->Paginator->sort('position', 'Positiion'); ?></th>
                                    <th class="hidden-480"><?php echo $this->Paginator->sort('status'); ?></th>
                                    <th class="hidden-480"><?php echo $this->Paginator->sort('created'); ?></th>
                                    <th class="hidden-480">Action</th>
                                </tr>
                                </thead>
                                <tbody id="AllUsers">

                                <?php
                                if (!empty($footer_all_page)) {
                                    foreach ($footer_all_page as $footer_page) {
                                        ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $footer_page['FooterLink']['id']; ?></td>
                                            <td><?php echo ucfirst($footer_page['FooterLink']['link_name']); ?></td>
                                            <td><?php echo ucfirst($footer_page['FooterCategory']['category_name']); ?></td>
                                            <td><?php echo ucfirst($footer_page['FooterLink']['position']); ?></td>
                                            <td class="hidden-480"
                                                id="<?php echo "st_" . $footer_page['FooterLink']['id']; ?>">
                                                <?php
                                                if ($footer_page['FooterLink']['status'] == 0) {
                                                    echo "Deactivate";
                                                } elseif ($footer_page['FooterLink']['status'] == 1) {
                                                    echo "Active";
                                                } else {
                                                    echo "Not available";
                                                }
                                                ?>
                                            </td>
                                            <td class="hidden-480"><?php echo $this->Lab->ShowDate($footer_page['FooterLink']['created']); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a data-toggle="dropdown" href="#" class="btn purple">
                                                        <i class="icon-user"></i> Settings
                                                        <i class="icon-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li><?php echo $this->Html->link('<i class="icon-plus"></i> Edit', array('controller' => 'labs', 'action' => 'edit_footer_link/' . $footer_page['FooterLink']['id']), array('class' => '', 'escape' => false)); ?></li>
                                                        <?php if ($footer_page['FooterLink']['status'] == 2 || $footer_page['FooterLink']['status'] == 0) { ?>
                                                            <li id="<?php echo "sp_" . $footer_page['FooterLink']['id']; ?>">
                                                                <a href="javascript:void(0);"
                                                                   onclick="change_status(<?php echo $footer_page['FooterLink']['id']; ?>, 1)"><i
                                                                        class="icon-remove"></i> <span>Active</span>
                                                                </a></li>
                                                        <?php } elseif ($footer_page['FooterLink']['status'] == 1) { ?>
                                                            <li id="<?php echo "sp_" . $footer_page['FooterLink']['id']; ?>">
                                                                <a href="javascript:void(0);"
                                                                   onclick="change_status(<?php echo $footer_page['FooterLink']['id']; ?>, 0)"><i
                                                                        class="icon-remove"></i> <span>Deactivate</span>
                                                                </a></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="12" class="mid"> No Page found</td>
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
