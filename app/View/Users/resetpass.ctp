<?php
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));
echo $this->Html->css(array('validationEngine.jquery'));
?>
<div id="login">
    <div class="login-content"><h1>Change PAssword</h1>
        <?php    echo $this->Session->flash('logmsg'); ?>
        <?php echo $this->Form->create('User', array('id'=>'reset_pass','novalidate', 'url' => array('controller' => 'Users', 'action' => 'resetpass',$uid,$key))); ?>
        <div class="form_bg">
            <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/password-icon.png" alt=""/></i>
                <?php echo $this->Form->input('password', array('label' => false, 'div' => false, 'placeholder' => 'New Password','class'=>'textbox validate[required,maxSize[15]]')); ?>
            </div>
            <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/password-icon.png" alt=""/></i>
                <?php echo $this->Form->input('confirm_password', array('type'=>'password','label' => false, 'class'=>'textbox validate[required,maxSize[15]]','div' => false, 'placeholder' => 'Confirm Password')); ?>
            </div>
            <div class="btn-submit">
                <?php echo $this->Form->submit('Save'); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $("#reset_pass").validationEngine({scroll: false});
    })
</script>
