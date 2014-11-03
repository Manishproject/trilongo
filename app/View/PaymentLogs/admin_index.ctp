<!-- BEGIN PAGE -->
<div class="page-content maincontent noright">

    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid content">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">

                <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                <h3 class="page-title"><?php echo $title_for_layout ?>
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


                    <div id="LabMsg"></div>
                    <div class="portlet-body">

                        <div class="tableoptions">
                            <form id="search_form" method="post">
                                <?php echo $this->Form->input('search', array('placeholder' => 'search...', 'label' => false, 'class' => 'searchtext', 'name' => 'search', 'value' => isset($this->request->data['search']) ? $this->request->data['search'] : "")) ?>
                                <?php echo $this->Form->submit('search', array('class' => 'button searchbutton')); ?>
                                <style>
                                    #search_form input[type=text] {
                                        width: 250px;
                                    }

                                    #search_form div.text {
                                        display: inline;
                                    }

                                    #search_form div.submit {
                                        display: inline;
                                    }
                                </style>
                            </form>
                        </div>
                        <!--tableoptions-->


                        <table class="table table-striped table-bordered table-hover" id="sample_1">
                            <thead>
                            <tr>
                                <?php /*?><th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th> <?php */ ?>

                                <th><?php echo $this->Paginator->sort('id'); ?></th>
                                <th class="hidden-480"><?php echo $this->Paginator->sort('reservation_id'); ?></th>
                                <th class="hidden-480"><?php echo $this->Paginator->sort('User.first_name', 'Payee Name'); ?></th>
                                <th class="hidden-480"><?php echo $this->Paginator->sort('amount'); ?></th>
                                <th class="hidden-480"><?php echo $this->Paginator->sort('transaction_id'); ?></th>
                                <th><?php echo $this->Paginator->sort('status'); ?></th>
                                <th class="hidden-480"><?php echo $this->Paginator->sort('created'); ?></th>

                            </tr>
                            </thead>
                            <tbody id="AllMails">

                            <?php if (!empty($all)) {
                                foreach ($all as $PaymentLog) {
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $PaymentLog['PaymentLog']['id'] ?></td>

                                        <td><?php echo $this->Html->link('#' . $PaymentLog['PaymentLog']['reservation_id'], '/admin/reservation/view/' . $PaymentLog['PaymentLog']['reservation_id']); ?></td>

                                        <td><?php echo $PaymentLog['User']['first_name'] . ' ' . $PaymentLog['User']['last_name']; ?></td>
                                        <td class="hidden-480"><?php echo $PaymentLog['PaymentLog']['amount'] . ' ' . $PaymentLog['PaymentLog']['currency']; ?></td>
                                        <td class="hidden-480"><?php echo $PaymentLog['PaymentLog']['transaction_id']; ?></td>

                                        <td class="hidden-480"><?php echo $PaymentLog['PaymentLog']['status'] == 's' ? 'Success' : 'failed'; ?></td>


                                        <td class="hidden-480"><?php echo date('M-d-Y', strtotime($PaymentLog['PaymentLog']['created'])); ?></td>

                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7" class="mid"> record not found</td>
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
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>

        <!-- END PAGE CONTENT-->
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->
