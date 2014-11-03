<section class="pg_mid add_driver">
    <div class="form_pages">

        <div class="company-edit">
            <div class="form-heading"><h2>Driver Information</h2></div>
            <div class="clear">

            </div>

            <?php
            echo $this->Form->create('Driver', array('novalidate', 'url' => array('controller' => 'providers', 'action' => 'add_driver')));
            echo $this->Session->flash('logmsg');
            ?>
            <label class="note"> <b class="red_star">Note : </b>Please fill in the information for your drivers
                below.</label></br>
            <div class="field clearfix ft">
                <label class="label">Driver Full Name <b class="red_star">*</b></label>
                <?php echo $this->Form->input('name', array('class' => 'textbox validate[required] ', 'label' => false, 'value' => false, ' placeholder' => 'Driver Full Name ', 'div' => false)); ?>
            </div>
            <div class="field clearfix fr">
                <label class="label">How many years this driver been working for you/with you? <b class="red_star">*</b></label>
                <?php echo $this->Form->input('working_with_you', array('class' => 'textbox validate[required] ', 'label' => false, 'value' => false, ' placeholder' => 'Working With You', 'div' => false)); ?>
            </div>

            <div class="field clearfix ft">
                <label class="label">Address of Driver <b class="red_star">*</b></label>
                <?php echo $this->Form->input('driver_address', array('class' => 'textbox validate[required] ', 'label' => false, 'value' => false, ' placeholder' => 'Driver Address', 'div' => false)); ?>

            </div>

            <div class="field clearfix fr">
                <label class="label">Secondary Cell Phone Number of Driver <b class="red_star">*</b></label>
                <?php echo $this->Form->input('mobile_no', array('class' => 'textbox validate[required] ', 'label' => false, 'value' => false, ' placeholder' => '+91 9784797330', 'div' => false)); ?>


            </div>
            <div class="field clearfix ft">
                <label class="label">Does this driver speak and understand English?<b class="red_star">*</b></label>
                <?php echo $this->Form->input('understand_english', array('type' => 'radio', 'label' => array('class' => 'edit-label'), 'legend' => false, 'div' => false, 'default' => 1, 'options' => array('1' => 'Yes', '0' => 'No'))); ?>
            </div>

            <div class="field clearfix fr">
                <label class="label">Is this driver licensed and insured?<b class="red_star">*</b></label>
                <?php echo $this->Form->input('licensed_insured', array('type' => 'radio', 'label' => array('class' => 'edit-label '), 'class' => 'licensed_insured_status', 'legend' => false, 'div' => false, 'default' => '1', 'options' => array('1' => 'Yes', '0' => 'No'))); ?>
            </div>

            <div class="field clearfix fl licensed_not" style="display: none;">
                <label class="label">If not licensed and insured, how can he/she be identified?
                    <bclass
                    ="red_star">*</b></label>
                <?php echo $this->Form->input('how_identified', array('class' => 'textbox validate[required] ', 'label' => false, 'value' => false, ' placeholder' => 'If not licensed and insured, how can he/she be identified?', 'div' => false)); ?>

            </div>

            <div class="field clearfix ft licensed_yes">
                <label class="label">If they are licensed and insured, what is their license number? <b
                        class="red_star">*</b></label>
                <?php echo $this->Form->input('license_number', array('class' => 'textbox validate[required] ', 'label' => false, 'value' => false, ' placeholder' => 'If they are licensed and insured, what is their license number?', 'div' => false)); ?>

            </div>

            <div class="field clearfix fr">
                <label class="label">Please provide any additional information that we should know about the driver?
                    <b class="red_star">*</b>
                </label>
                <?php echo $this->Form->input('additional_information', array('class' => 'textbox validate[required] ', 'rows' => 2, 'label' => false, 'type' => 'textarea', ' placeholder' => 'Additional Information', 'div' => false)); ?>
            </div>
            <div class="clear"></div>
            <div class="edit-btn">
                <?php
                echo $this->Form->submit("Add", array('class' => 'submit-button h_f_b '));
                echo $this->Html->link("Cancel ", SITEURL . "providers/driver_listing", array('class' => 'link-text'));

                ?>
                <div class="clear"></div>
            </div>

            <?php echo $this->Form->end(); ?>
            <div class="clear"></div>
        </div>
    </div>
    </div>
</section>
<?php echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en')); ?>
<?php echo $this->Html->css(array('validationEngine.jquery')); ?>

<script>

    $(function () {
        $("#DriverAddDriverForm").validationEngine({scroll: false});
        $(".licensed_insured_status").change(function () {
            var current_value = $(this).val();
            if (current_value == 1) {
                $(".licensed_yes").show();
                $(".licensed_not").hide();
            } else {
                $(".licensed_yes").hide();
                $(".licensed_not").show();
            }
        })


    })
</script>