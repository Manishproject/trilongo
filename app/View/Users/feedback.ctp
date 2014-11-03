<?php
echo $this->Html->script(array('jquery.form', 'jquery.validationEngine', 'jquery.validationEngine-en'));
echo $this->Html->css(array('validationEngine.jquery'));
?>
<div id="feedback">
    <div class="login-content">
        <h1>Feedback</h1>
        <?php echo $this->Session->flash('logmsg'); ?>
        <div class="ServerError"></div>
        <div class="form_bg">
            <?php echo $this->Form->create('Feedback', array('novalidate', 'url' => array('controller' => 'users', 'action' => 'feedback'),'id'=>'feedback_form')); ?>
            <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/user-icon.png" alt=""/></i>
                <?php echo $this->Form->input('name', array('class' => 'OnlyNumberLetter textbox validate[required,maxSize[15]]', 'maxlength'=>15,'label' => false, 'placeholder' => 'Your Name', 'div' => false)); ?>
            </div>
            <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/email-icon.jpg" alt=""/></i>
                <?php echo $this->Form->input('email', array('class' => 'textbox validate[required,custom[email]]', 'label' => false, 'placeholder' => 'Your Email', 'div' => false)); ?>
            </div>
            <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/message-icon.jpg" alt=""/></i>
                <?php echo $this->Form->input('message', array('type' => 'textarea', 'class' => 'textbox validate[required]', 'label' => false, 'placeholder' => 'Your Message Here', 'div' => false)); ?>
            </div>
            <?php echo $this->Form->submit('Send', array('type'=>'button','id'=>'save_retailer_data','class'=>'feedback_submit','div' => array('class' => 'btn-submit'))); ?>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $("#feedback_form").validationEngine({scroll: false});
    });
</script>


