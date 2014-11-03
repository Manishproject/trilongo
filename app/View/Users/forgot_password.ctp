<?php
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));
echo $this->Html->css(array('validationEngine.jquery'));
?>
<div id="forgot-pass">

    <div class="login-content">
        <h1>Forgot Password</h1>
        <?php
        echo $this->Session->flash('logmsg');
        echo $this->Form->create('User', array('id'=>'forgot_pass','novalidate', 'url' => array('controller' => 'Users', 'action' => 'forgot_password')));
        echo $this->Session->flash('logmsg');
        ?>
        <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/email-icon.png" alt=""/></i>
            <?php echo $this->Form->input('email', array('class' => 'textbox validate[required, custom[email]]', 'label' => false, 'placeholder' => 'Email Address', 'div' => false)); ?>
        </div>
        <div class="btn-submit">
            <?php echo $this->Form->submit("Send", array('class' => 'submit-button h_f_b')); ?>
        </div>
        <p><a href="<?php echo SITE_URL . "users/login"; ?>" class="fancybox">Sign In</a></p>

        <?php echo $this->Form->end(); ?>
        
    </div>
</div>
<script>
    $(function(){
        $("#forgot_pass").validationEngine({scroll: false});
    })
</script>

