<?php
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));
?>

    <section class="pg_mid">
        <div class="form_pages">
            <div class="login-pannel signup-pannel form_bg">

                <?php
                echo $this->Form->create('User', array('novalidate', 'enctype' => 'multipart/form-data', 'url' => array('controller' => 'users', 'action' => 'update_profile')));
                echo $this->Session->flash('logmsg');
                echo $this->Form->input('id');
                ?>


                <div class="ProfilePicture">
                    <div class="form-heading"><h2>Update Profile</h2></div>
                    <div class="clear"></div>
                    <div class="field clearfix ft">
                        <label class="label">profile pictures</label>
                        <?php echo $this->Form->input('User.profile_pic', array('type' => 'file', 'name' => 'user_profile_pic', 'class' => 'browse', 'label' => false));
                        echo $this->Form->hidden('User.profile_pic');

                        if (isset($userData['User']['profile_pic_data']) && $userData['User']['profile_pic_data']) {
                            echo '<div class="imgwrapper">';
                            echo '<img width="100" height="50" src="' . SITE_URL . $userData['User']['profile_pic_data']['path'] . '" alt=""/>';
                            echo '<span class="removeProfilePic">X</span>';
                            echo '</div>';

                        }
                        ?>
                    </div>
                </div>

                <div class="field clearfix fr">
                    <label class="label">Email Address <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('email', array('class' => 'textbox validate[required,custom[email]]', 'label' => false, 'placeholder' => 'Email Address', 'div' => false)); ?>
                </div>
                <div class="field clearfix ft">
                    <label class="label">First Name <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('first_name', array('class' => 'textbox validate[required]', 'label' => false, 'placeholder' => 'First Name', 'div' => false)); ?>
                </div>
                <div class="field clearfix fr">
                    <label class="label">Last Name <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('last_name', array('class' => 'textbox validate[required]', 'label' => false, 'placeholder' => 'Last Name', 'div' => false)); ?>
                </div>
                <div class="field clearfix ft">
                    <label class="label">Gender <b class="red_star">*</b></label> <select class="selects">
                        <option>Female</option>
                        <option>Male</option>
                    </select>

                </div>

                <div class="field clearfix fr">
                    <label class="label">Contact No. <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('last_name', array('class' => 'textbox validate[required]', 'label' => false, 'placeholder' => 'Contact No.', 'div' => false)); ?>
                </div>
                <div class="field clearfix ft">
                    <label class="label">Address <b class="red_star">*</b></label>
                    <textarea class="textarea" placeholder="Jaipur, Rajastha, IN 302021"></textarea>
                </div>
                <div class="field clearfix fr">
                    <label class="label">Driver Counting <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('last_name', array('class' => 'textbox validate[required]', 'label' => false, 'placeholder' => '10', 'div' => false)); ?>
                </div>
                <div class="clear"></div>



                <?php /*
     <fieldset id="company_info">
          <legend>Compnay information</legend>
       
         <?php echo $this->Form->input('Company.name',array('label'=>'Company Name')); ?>
           <?php echo $this->Form->input('Company.address1',array('label'=>'Address')); ?>
          <?php echo $this->Form->input('Company.address2',array('label'=>'Address2')); ?>
          <?php echo $this->Form->input('Company.city',array('label'=>'City')); ?> 
          <?php echo $this->Form->input('Company.zip',array('label'=>'Zip Code')); ?> 
      </fieldset>      
*/
                ?>
                <div class="clearfix">
                    <div class="btn-submit">
                        <?php
                        echo $this->Form->submit("Update", array('class' => 'submit-button h_f_b '));
                        ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>

    </section>

    <script>
        $(function () {

            $(document).ready(function () {
                $('.removeProfilePic').click(function () {


                    $(this).parents('.imgwrapper').hide();
                    $('input#UserProfilePic').val('');
                });

            });

            // client side validation jquery valiadtion engine
            // $("#UserSignUpForm").validationEngine({scroll: false});
        });

    </script>
<?php
echo $this->Html->css(array('validationEngine.jquery'));
?>