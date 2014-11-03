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
                <h3 class="page-title">Payment Details
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
                                        var datastring = "mssg=" + txt + "&from=retailers&type=all";
                                        $("#LabMsg").html('');
                                        $("#labs_ajax_search_btn").text('Searching...');
                                        $(function () {
                                            $.ajax({type: 'POST',
                                                url: '<?php echo SITEURL; ?>admin/labs/payments_search/',
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
                            </script>
                            <label>Search: <input type="text" class="m-wrap" id="labs_ajax_search">
                                <button id="labs_ajax_search_btn" type="button" class="btn green">Search!</button>
                            </label>

                        </div>

                        <div class="table_header_main">
                            <table class="table table-striped table-bordered table-hover" id="sample_1">
                                <thead>
                                <tr>
                                    <?php /* ?><th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th> <?php */ ?>

                                    <th><?php echo $this->Paginator->sort('Payment.id'); ?></th>
                                    <th><?php echo $this->Paginator->sort('Retailer.business_name', 'Business Name'); ?></th>
                                    <th class="hidden-480"><?php echo $this->Paginator->sort('Subscription.name', 'Plan Name'); ?></th>
                                    <th class="hidden-480"><?php echo $this->Paginator->sort('Payment.amount', 'Amount'); ?></th>
                                    <th class="hidden-480"><?php echo $this->Paginator->sort('Payment.type', 'Type'); ?></th>
                                    <th class="hidden-480"><?php echo $this->Paginator->sort('Paymetn.start_date', 'start Date'); ?></th>
                                    <th class="hidden-480"><?php echo $this->Paginator->sort('Payment.end_date', 'End Date'); ?></th>
                                    <th class="hidden-480"><?php echo $this->Paginator->sort('created'); ?></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="AllUsers">

                                <?php
                                if (!empty($all)) {
                                    foreach ($all as $list) { //ec($list);
                                        ?>
                                        <tr class="odd gradeX">
                                            <td> <?php echo $list['Payment']['id']; ?></td>
                                            <td> <?php echo $list['Retailer']['business_name']; ?></td>

                                            <td class="hidden-480"><?php echo $list['Subscription']['name']; ?></td>
                                            <td class="hidden-480"><?php echo $list['Payment']['amount']; ?></td>
                                            <td class="hidden-480"><?php
                                                if ($list['Payment']['type'] == 1) {
                                                    echo __('Initial');
                                                } elseif ($list['Payment']['type'] == 2) {
                                                    echo __('Schedule');
                                                } else {
                                                    echo __('Recurring');
                                                }
                                                ?></td>
                                            <td class="hidden-480"><?php echo $this->Lab->ShowDate($list['Payment']['start_date']); ?></td>
                                            <td class="hidden-480"><?php echo $this->Lab->ShowDate($list['Payment']['end_date']); ?></td>
                                            <td class="hidden-480"><?php echo $this->Lab->ShowDate($list['Payment']['created']); ?></td>
                                            <td>
                                                <!--<a href="<?php echo SITEURL . "admin/labs/review_retailers/" . $list['Payment']['id'] ?>" class="btn mini green-stripe">View</a>
                                                    --></td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="9" class="mid"> record not found</td>
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

