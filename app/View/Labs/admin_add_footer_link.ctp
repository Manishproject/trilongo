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

        <h3 class="page-title"> Add New Footer Link</h3>

    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row-fluid">
<div class="span12">
<div class="tabbable tabbable-custom boxless">


<div class="tab-pane" id="tab_4">
    <div class="portlet box blue">
        <div class="portlet-title">
            <h4><i class="icon-reorder"></i> Add New Link </h4>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <?php
            echo $this->Form->create('FooterLink', array('type' => 'file', 'novalidate', 'class' => 'form-horizontal form-row-seperated'));
            echo $this->Session->flash('msg');
            ?>
            <div class="control-group">
                <label class="control-label">Link Name</label>

                <div class="controls">
                    <?php echo $this->Form->input('link_name', array('class' => 'm-wrap span12', 'label' => false)); ?>
                    <span class="help-inline">Enter Link Name</span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Select Link Page</label>

                <div class="controls">
                    <?php
                    echo $this->Form->input('link_url', array('options' => $all_page, 'class' => 'm-wrap span12', 'label' => false));
                    ?>
                    <span class="help-inline">Select Link Page</span>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Link Position</label>

                <div class="controls">
                    <?php
                    $position_array = array();
                    for ($count_position = 1; $count_position <= $total_footer_link_count; $count_position++) {
                        $position_array[$count_position] = $count_position;
                    }

                    echo $this->Form->input('position', array('options' => $position_array, 'class' => 'm-wrap span12', 'label' => false));
                    echo $this->Form->hidden('total_current_child', array('class' => 'm-wrap span12', 'value' => $total_footer_link_count));
                    ?>
                    <span class="help-inline">Enter Link Position Here</span>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn blue"><i class="icon-ok"></i> Save</button>
                <!--<button type="button" class="btn">Cancel</button>
                --></div>
            <?php echo $this->Form->end(); ?>
            <!-- END FORM-->
        </div>
    </div>
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

    </div>


    <div class="clearfix">
        <div class="btn-group">
            <i class="icon-plus"></i>
        </div>

    </div>

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
                    <td class="hidden-480" id="<?php echo "st_" . $footer_page['FooterLink']['id']; ?>">
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
                                <?php if ($footer_page['FooterLink']['status'] == 0) { ?>
                                    <li id="<?php echo "sp_" . $footer_page['FooterLink']['id']; ?>"><a
                                            href="javascript:void(0);"
                                            onclick="change_status(<?php echo $footer_page['FooterLink']['id']; ?>, 1)"><i
                                                class="icon-remove"></i> <span>Active</span> </a></li>
                                <?php } elseif ($footer_page['FooterLink']['status'] == 1) { ?>
                                    <li id="<?php echo "sp_" . $footer_page['FooterLink']['id']; ?>"><a
                                            href="javascript:void(0);"
                                            onclick="change_status(<?php echo $footer_page['FooterLink']['id']; ?>, 0)"><i
                                                class="icon-remove"></i> <span>Deactivate</span> </a></li>
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

    <div class="span6">
        <div class="pagination pagination-large">
            <?php echo $this->Paginator->numbers(array('first' => 2, 'last' => 2)); ?>
        </div>
    </div>

</div>

</div>
</div>
</div>
<!-- END PAGE CONTENT-->
</div>
<!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->  


