<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>assets/uniform/css/uniform.default.css" />
<link rel="stylesheet" href="<?php echo SITEURL; ?>assets/data-tables/DT_bootstrap.css" />


<!-- BEGIN PAGE -->
<div class="page-content">

    <!-- BEGIN PAGE CONTAINER-->			
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">

                <!-- BEGIN PAGE TITLE & BREADCRUMB-->			
                <h3 class="page-title">All Pages List <small></small>
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
                        <div class="tools"></div>
                    </div>

                    <div id="LabMsg"></div>
                    <div class="portlet-body">
                        <div class="dataTables_filter" id="sample_1_filter">
                            <script>
                                $(document).ready(function() {
                                    $('#LabMsg').html('');
                                    $("#labs_ajax_search_btn").click(function(e) {
                                        call($("#labs_ajax_search").val());
                                    });
                                    $('#labs_ajax_search').live('keypress', function(e) {
                                        var p = e.which;
                                        if (p == 13) {
                                            call($("#labs_ajax_search").val());
                                        }
                                    });
                                    function call(txt)
                                    {
                                        //if(txt ==""){ $('#LabMsg').html('<div class="alert alert-error"><button data-dismiss="alert" class="close"></button><strong>Error!</strong> Enter search value.</div>'); }else{
                                        var datastring = "mssg=" + txt + "&from=users";
                                        $("#LabMsg").html('');
                                        $("#labs_ajax_search_btn").text('Searching...');
                                        $(function() {
                                            $.ajax({type: 'POST',
                                                url: '<?php echo SITEURL; ?>admin/pages/homes/search_page',
                                                data: datastring,
                                                success: function(data) {
                                                    $("#labs_ajax_search_btn").text('Search!');
                                                    $("#AllPages").html(data);
                                                },
                                                error: function(comment) {
                                                }});
                                        });

                                        //	}
                                    }
                                });
                                function change_status(id)
                                {
                                    if (id != "")
                                    {
                                        var datastring = "mssg=" + id + "&from=users";
                                        $("#Page_list_" + id).text('updating...');
                                        $(function() {
                                            $.ajax({type: 'POST',
                                                url: '<?php echo SITEURL; ?>admin/pages/homes/updated_status',
                                                data: datastring,
                                                success: function(data) {
                                                    $("#Page_list_" + id).html(data);
                                                },
                                                error: function(comment) {
                                                }});
                                        });

                                    }
                                }

                            </script>
                            <label>Search: <input type="text" class="m-wrap" id="labs_ajax_search"><button id="labs_ajax_search_btn" type="button" class="btn green">Search!</button></label>

                        </div>


                        <div class="clearfix">
                            <?php echo $this->Html->link('Add New Page', '/admin/pages/homes/new', array('class' => 'btn green')); ?>
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
                                        <th><?php echo $this->Paginator->sort('title'); ?></th>
                                        <th class="hidden-480"><?php echo $this->Paginator->sort('url'); ?></th>
                                        <th class="hidden-480"><?php echo $this->Paginator->sort('description'); ?></th>
                                        <th class="hidden-480"><?php echo $this->Paginator->sort('keywords'); ?></th>
                                        <th class="hidden-480"><?php echo $this->Paginator->sort('views'); ?></th>
                                        <th class="hidden-480"><?php echo $this->Paginator->sort('status'); ?></th>
                                        <th class="hidden-480"><?php echo $this->Paginator->sort('created'); ?></th>
                                        <th class="hidden-480"><?php echo $this->Paginator->sort('updated'); ?></th>
                                        <th ></th>
                                    </tr>
                                </thead>
                                <tbody id="AllPages">

                                    <?php
                                    if (!empty($all)) {
                                        foreach ($all as $list) {
                                            ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo $list['Page']['id']; ?></td>
                                                <td><?php echo substr($list['Page']['title'], 0, 50); ?></td>
                                                <td class="hidden-480"><?php echo substr($list['Page']['url'], 0, 50); ?></td>
                                                <td class="hidden-480"><?php echo substr($list['Page']['description'], 0, 50); ?></td>
                                                <td class="hidden-480"><?php echo substr($list['Page']['keywords'], 0, 50); ?></td>
                                                <td class="hidden-480"><?php echo $list['Page']['views']; ?></td>
                                                <td class="hidden-480 is_cursor" >
                                                    <a href="javascript:void(0);" id="Page_list_<?php echo $list['Page']['id']; ?>" onclick="change_status(<?php echo $list['Page']['id']; ?>)" title="Click here to change status"><?php
                                                        if ($list['Page']['status'] == 1) {
                                                            echo "Published";
                                                        } else {
                                                            echo "Draft";
                                                        }
                                                        ?></a></td>
                                                <td class="hidden-480"><?php if (!empty($list['Page']['created'])) echo date('M-d-Y', strtotime($list['Page']['created'])); ?></td>
                                                <td class="hidden-480"><?php if (!empty($list['Page']['updated'])) echo date('M-d-Y', strtotime($list['Page']['updated'])); ?></td>
                                                <td class="hidden-480">

                                                    <?php echo $this->Html->link('<i class="icon-edit"></i> Edit', array('controller' => 'homes', 'action' => 'new/' . $list['Page']['id']), array('class' => 'btn mini purple', 'admin' => true, 'escape' => false));
                                                    ?>

                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }else {
                                        ?>
                                        <tr><td colspan="10" class="mid"> record not found </td></tr>
                                    <?php } ?>


                                </tbody>
                            </table>
                        </div>

                        <div class="span6"><div class="pagination pagination-large">
                                <?php echo $this->Paginator->numbers(array('first' => 2, 'last' => 2)); ?>
                            </div></div>	

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
