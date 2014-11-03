<?php
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));
?>

    <section class="pg_mid">
        <div class="form_pages">

            <div class="bdr"></div>

            <div class="login-pannel signup-pannel form_bg">
                <?php
                echo $this->Form->create('User', array('action' => 'update_profile'));
                echo $this->Session->flash('logmsg');
                echo $this->Form->input('id')
                ?>

                <div class="ProfilePicture">
                    <?php echo $this->Form->input('User.profile_pic', array('type' => 'file')) ?>
                </div>
                <div class="field clearfix fl">
                    <label class="label">Username <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('username', array('class' => 'textbox validate[required]', 'readonly' => true, 'label' => false, 'placeholder' => 'User Name', 'div' => false)); ?>
                </div>

                <div class="field clearfix fr">
                    <label class="label">Email Address <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('email', array('class' => 'textbox validate[required,custom[email]]', 'label' => false, 'placeholder' => 'Email Address', 'div' => false)); ?>
                </div>
                <div class="field clearfix fl">
                    <label class="label">First Name <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('first_name', array('class' => 'textbox validate[required]', 'label' => false, 'placeholder' => 'First Name', 'div' => false)); ?>
                </div>
                <div class="field clearfix fl">
                    <label class="label">Last Name <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('last_name', array('class' => 'textbox validate[required]', 'label' => false, 'placeholder' => 'Last Name', 'div' => false)); ?>
                </div>

                <div class="field clearfix fl">
                    <label class="label">Gender</label>
                    <?php
                    echo $this->Form->input('gender', array('class' => 'sex ', 'div' => false, 'options' => array('male' => 'Male', 'female' => 'Female', 'other' => 'Other'), 'empty' => '--Select--', 'label' => false));
                    ?>
                </div>


                <div class="clearfix">
                    <?php
                    echo $this->Form->submit("Update", array('class' => 'submit-button h_f_b'));
                    ?>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>

    </section>

    <script>
        $(function () {

            // client side validation jquery valiadtion engine
            // $("#UserSignUpForm").validationEngine({scroll: false});
        });

    </script>
<?php
echo $this->Html->css(array('validationEngine.jquery'));
?>