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

                <h3 class="page-title"> Edit Footer Category</h3>

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
                                <h4><i class="icon-reorder"></i> Edit Footer Category</h4>
                            </div>
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <?php
                                echo $this->Form->create('FooterCategory', array('type' => 'file', 'novalidate', 'class' => 'form-horizontal form-row-seperated'));
                                echo $this->Session->flash('msg');
                                ?>
                                <div class="control-group">
                                    <label class="control-label">Footer Category Name</label>

                                    <div class="controls">
                                        <?php echo $this->Form->input('category_name', array('class' => 'm-wrap span12', 'label' => false)); ?>
                                        <span class="help-inline">Enter Footer Category Name</span>
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

                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
    <!-- END PAGE CONTAINER-->
</div>
<!-- END PAGE -->  


