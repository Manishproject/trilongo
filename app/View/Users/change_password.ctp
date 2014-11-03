<section class="pg_mid">
    <div class="form_pages">
        <div class="landing_logo" id="elm"><a href="<?php echo SITEURL; ?>"><img
                    src="<?php echo SITEURL; ?>images/landing_logo.png" alt="FindKindBud"/></a></div>
        <h1>Change Password</h1>

        <div class="login-pannel form_bg">
            <?php
            echo $this->Form->create('User', array('novalidate'));
            echo $this->Session->flash('logmsg');
            ?>
            <div class="field clearfix">
                <label class="label"> Old Password</label>
                <?php echo $this->Form->input('oldpassword', array('type' => 'password', 'class' => 'textbox', 'label' => false, 'placeholder' => 'Old Passowrd', 'div' => false)); ?>
            </div>
            <div class="field clearfix">
                <label class="label"> New Password</label>
                <?php echo $this->Form->input('password', array('class' => 'textbox', 'label' => false, 'placeholder' => 'New Passowrd', 'div' => false)); ?>
            </div>
            <div class="field clearfix">
                <label class="label">Confirm Password</label>
                <?php echo $this->Form->input('confirm_password', array('type' => 'password', 'class' => 'textbox', 'label' => false, 'placeholder' => 'Confirm Password', 'div' => false)); ?>
            </div>
            <div class="clearfix">
                <?php
                echo $this->Form->submit("Send", array('class' => 'submit-button h_f_b'));
                ?>
            </div>

            <?php echo $this->Form->end(); ?>

        </div>
    </div>

</section>
