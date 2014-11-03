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

                <h3 class="page-title"> Edit</h3>

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
                                <h4><i class="icon-reorder"></i>Edit Default Image </h4>
                            </div>
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <?php
                                echo $this->Form->create('DefaultImage', array('type' => 'file', 'novalidate', 'class' => 'form-horizontal form-row-seperated'));
                                echo $this->Session->flash('msg');
                                ?>

                                <div class="control-group">
                                    <label class="control-label">Image</label>

                                    <div class="controls">
                                        <?php
                                        echo $this->Image->resize('data/' . $photo_name . '/' . $photo_name . '_default.png', 75, 75, true, false);
                                        ?>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">Upload Default Image</label>

                                    <div class="controls">
                                        <?php echo $this->Form->input('image', array('type' => "file", 'label' => false, 'class' => 'm-wrap span12')); ?>
                                        <span class="help-block">Upload Default Image</span>
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


