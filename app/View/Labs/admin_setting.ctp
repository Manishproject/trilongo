<!-- BEGIN PAGE -->
<div class="page-content">
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <h3 class="page-title"> Site setting </h3>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="tabbable tabbable-custom boxless">
                    <div class="tab-pane" id="tab_4">
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <h4> Site Setting </h4>
                            </div>
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <?php
                                echo $this->Form->create('SiteSetting', array('novalidate' => true, 'class' => 'form-horizontal form-row-seperated', 'id' => 'SettingAdminIndexForm'));
                                echo $this->Session->flash('msg');
                                ?>
                                <div class="control-group">
                                    <?php
                                    if (isset($data) && !empty($data)) {
                                        foreach ($data as $key => $value) {
                                            ?>
                                            <div class="control-group">
                                                <label
                                                    class="control-label"><?php echo $value['Setting']['label']; ?></label>

                                                <div class="controls">
                                                    <?php echo $this->Form->input($value['Setting']['slug'], array('class' => 'smallinput  validate[required]', 'type' => 'text', 'label' => false, 'value' => $value['Setting']['value'])); ?></span>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                    }
                                    ?>

                                    <div class="form-actions">
                                        <button type="submit" class="btn blue"><i class="icon-ok"></i> Save</button>
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
