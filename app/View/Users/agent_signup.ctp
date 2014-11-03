<?php
echo $this->Html->css(array('intlTelInput'));
echo $this->Html->script(array('intlTelInput','jquery.validationEngine', 'jquery.validationEngine-en'));
?>

    <section class="pg_mid">
        <div class="form_pages">

            <div class="bdr"></div>

            <div class="login-pannel signup-pannel form_bg">
                <div class="form-heading"><h2>Basic Information</h2></div>
                <div class="clear"></div>
                <?php
                echo $this->Form->create('User', array('novalidate', 'url' => array('controller' => 'users', 'action' => 'agent_signup'), 'id' => 'UserAgentSignUpForm'));
                echo $this->Session->flash('logmsg');
                ?>


                <div class="field clearfix ft">
                    <label class="label">Email Address <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('email', array('class' => 'textbox validate[required,custom[email]]', 'label' => false, 'placeholder' => 'Email Address', 'div' => false)); ?>
                </div>
                <div class="field clearfix fr">
                    <label class="label">First Name <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('first_name', array('maxlength'=>15,'class' => 'OnlyNumberLetter textbox validate[required,custom[onlyLetterNumber]]', 'label' => false, 'placeholder' => 'First Name', 'div' => false)); ?>
                </div>
                <div class="field clearfix ft">
                    <label class="label">Last Name <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('last_name', array('maxlength'=>15,'class' => 'OnlyNumberLetter textbox validate[required,custom[onlyLetterNumber]]', 'label' => false, 'placeholder' => 'Last Name', 'div' => false)); ?>
                </div>
                <div class="field clearfix fr">
                    <label class="label">Mobile <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('User.phone', array('maxlength' => 15,'id'=>'mobile-number','class' => 'textbox validate[required]', 'label' => false, 'placeholder' => '000-000-0000')); ?>
                </div>


                <div class="field clearfix ft">
                    <label class="label">Password <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('password', array('maxlength'=>15,'class' => 'textbox validate[required]', 'label' => false, 'placeholder' => 'Password', 'div' => false)); ?>
                </div>
                <div class="field clearfix fr">
                    <label class="label">Confirm Password <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('confirm_password', array('maxlength'=>15,'class' => 'textbox validate[required]', 'label' => false, 'placeholder' => 'Confirm Password', 'div' => false, 'type' => 'password')); ?>
                </div>

                <div class="clearfix">
                    <div class="btn-submit">
                        <?php
                        echo $this->Form->submit("Register", array('class' => 'submit-button h_f_b'));
                        ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>

    </section>
    <style>
        .hideme {
            display: none
        }
    </style>
    <script>
        $(function () {


            // mobile number validation
            $("#mobile-number").intlTelInput({
                autoFormat: true,
                //autoHideDialCode: false,
                defaultCountry: "us",
                //nationalMode: true,
                //numberType: "MOBILE",
                //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                //preferredCountries: ['cn', 'jp'],
                responsiveDropdown: true,
                utilsScript: "<?php  echo SITE_URL."js/utils.js"; ?>"
            });


            // client side validation jquery validation engine
            $("#UserAgentSignUpForm").validationEngine({scroll: false});
        });

    </script>
<?php
echo $this->Html->css(array('validationEngine.jquery'));
?>
