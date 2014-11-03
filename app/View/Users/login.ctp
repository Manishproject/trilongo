<?php
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));
echo $this->Html->css(array('validationEngine.jquery'));
?>
<div id="login">
    <div class="login-content"><h1>Sign In</h1>
        <?php echo $this->Session->flash('logmsg'); ?>
        <?php echo $this->Form->create('User', array('novalidate', 'id'=>'login_form','url' => array('controller' => 'Users', 'action' => 'login'))); ?>
        <div class="form_bg">
            <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/email-icon.jpg" alt=""/></i>
                <?php echo $this->Form->input('email', array('label' => false, 'class' => 'textbox validate[required,custom[email]]', 'div' => false, 'placeholder' => 'Email')); ?>
            </div>
            <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/password-icon.png" alt=""/></i>
                <?php echo $this->Form->input('password', array('label' => false, 'div' => false,'maxlength'=>15, 'class' => 'textbox validate[required,maxSize[15]]', 'placeholder' => 'Password')); ?>
            </div>
            <div class="btn-submit">
                <?php echo $this->Form->submit('Login'); ?>
            </div>
            <p>
                <a href="<?php echo SITE_URL . "users/forgot_password"; ?>" class="fancybox">Forgot your password?</a>
                <br/>
                <a href="<?php echo SITE_URL . "users/signup"; ?>" class="fancybox">Sign-up for New user </a>
            </p>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $("#login_form").validationEngine({scroll: false});
    })
</script>
