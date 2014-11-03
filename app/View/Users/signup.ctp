<?php
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));
echo $this->Html->css(array('validationEngine.jquery'));

?>
<div id="register">
    <div class="login-content"><h1>Register</h1>
        <?php    echo $this->Session->flash('logmsg'); ?>
        <?php echo $this->Form->create('User', array('novalidate','id'=>'register_form', 'url' => array('controller' => 'users', 'action' => 'signup'))); ?>
        <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/user-icon.png" alt=""/> </i>
            <?php echo $this->Form->input('first_name', array('maxlength'=>15,'class' => 'OnlyNumberLetter textbox validate[required,maxSize[15]]', 'label' => false, 'required' => 'required', 'placeholder' => 'First Name', 'div' => false)); ?>
        </div>

        <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/user-icon.png" alt=""/></i>
            <?php echo $this->Form->input('last_name', array('maxlength'=>15,'class' => 'OnlyNumberLetter textbox validate[required]', 'label' => false, 'placeholder' => 'Last Name', 'div' => false)); ?>
        </div>
        <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/email-icon.png" alt=""/></i>
            <?php echo $this->Form->input('email', array('class' => 'textbox validate[required,custom[email]]', 'label' => false, 'required' => 'required', 'placeholder' => 'Email Address', 'div' => false)); ?>
        </div>
        <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/password-r-icon.png" alt=""/></i>
            <?php echo $this->Form->input('password', array('maxlength'=>15,'class' => 'textbox validate[required,maxSize[15]]', 'label' => false, 'required' => 'required', 'placeholder' => 'Password', 'div' => false)); ?>
        </div>
        <div class="input-c"><i><img src="<?php echo SITE_URL; ?>images/password-r-icon.png" alt=""/></i>
            <?php echo $this->Form->input('confirm_password', array('maxlength'=>15,'type'=>'password','class' => 'textbox validate[required,maxSize[15],equals[password]]', 'label' => false, 'required' => 'required', 'placeholder' => 'Confirm Password', 'div' => false)); ?>
        </div>
        <div class="btn-submit">
            <?php echo $this->Form->submit('Register'); ?>
        </div>
        <p><a href="<?php echo SITE_URL . "users/login"; ?>" class="fancybox">Sign In </a></p>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<script>
    $(function(){
        $("#register_form").validationEngine({scroll: false});
    });
</script>

