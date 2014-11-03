   
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />


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

                <h3 class="page-title"><?php
                    if (isset($this->request->data['Mail']['id'])) {
                        echo "Edit";
                    } else {
                        echo "Create New";
                    }
                    ?> Email Template<small> </small>
                </h3>

            </div>
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row-fluid">
            <div class="span12">
                <div class="tabbable tabbable-custom boxless">


                    <div class="tab-pane"  id="tab_4">
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <h4><i class="icon-reorder"></i><?php
                                    if (isset($this->request->data['Mail']['id'])) {
                                        echo "Edit Email";
                                    } else {
                                        echo "New Email";
                                    }
                                    ?></h4>






                                <!--<div class="tools">
                                   <a href="javascript:;" class="collapse"></a>
                                   <a href="#portlet-config" data-toggle="modal" class="config"></a>
                                   <a href="javascript:;" class="reload"></a>
                                   <a href="javascript:;" class="remove"></a>
                                </div>
                                --></div>

                            <div class="portlet-body form">
                                <h3 style="color: red;">Please do not change bracket word</h3>
                                <!-- BEGIN FORM-->
                                <?php
                                echo $this->Form->create('Mail', array('class' => 'form-horizontal form-row-seperated'));

                                if (isset($this->request->data['Mail']['id']) && !empty($this->request->data['Mail']['id'])) {
                                    echo $this->Form->hidden('id');
                                }

                                echo $this->Session->flash('msg');
                                ?>

                                <div class="control-group">
                                    <label class="control-label">Email Type</label>
                                    <div class="controls">
                                        <?php
                                        if (isset($this->request->data['Mail']['id']) && !empty($this->request->data['Mail']['id'])) {
                                            echo $this->Form->input('type', array('readonly' => 'readonly', 'class' => 'm-wrap span12', 'label' => false));
                                        } else {
                                            echo $this->Form->input('type', array('class' => 'm-wrap span12', 'label' => false));
                                        }
                                        ?>

                                        <span class="help-inline">Enter email type</span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Sender Name</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('sender_name', array('class' => 'm-wrap span12', 'label' => false)); ?>
                                        <span class="help-inline">Enter sender name</span>
                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">Email</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('email', array('class' => 'm-wrap span12', 'label' => false)); ?>
                                        <span class="help-inline">Enter email address</span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Subject</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('subject', array('class' => 'm-wrap span12', 'label' => false)); ?>
                                        <span class="help-inline">Enter subject</span>
                                    </div>
                                </div>


                                <div class="control-group">
                                    <label class="control-label">Message Body</label>
                                    <div class="controls">
                                        <?php echo $this->Form->input('message', array('id' => 'editor1', 'readonly' => 'readonly', 'class' => 'span12 ckeditor m-wrap', 'label' => false)); ?>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn blue"><i class="icon-ok"></i> Save</button>

                                    <?php echo $this->Html->link('Back To all emails', '/admin/mails/mails/index', array('class' => 'btn')); ?>
                                </div>

                                <?php echo $this->Form->end(); ?>
                                <!-- END FORM-->  
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

<script type="text/javascript" src="<?php echo SITEURL; ?>assets/ckeditor/ckeditor.js"></script>  
