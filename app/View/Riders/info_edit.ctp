<?php echo $this->Html->script(array('intlTelInput')); ?>
<?php echo $this->Html->css(array('intlTelInput')); ?>
    <section class="pg_mid">
        <div class="form_pages">
            <div class="login-pannel signup-pannel form_bg">
                <?php
                echo $this->Form->create('User', array('novalidate', 'type' => 'file'));
                echo $this->Session->flash('logmsg');
                echo $this->Form->input('id');
                ?>
                <div class="ProfilePicture">
                    <div class="form-heading"><h2>Update Basic Info</h2></div>
                    <div class="clear"></div>
                </div>
                <div class="field clearfix ft">
                    <label class="label">First Name <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('first_name', array('maxlength' => 15, 'class' => 'OnlyNumberLetter textbox validate[required,custom[onlyLetterNumber],max[15]]', 'label' => false, 'placeholder' => 'First Name', 'div' => false)); ?>
                </div>
                <div class="field clearfix fr">
                    <label class="label">Last Name <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('last_name', array('maxlength' => 15, 'class' => 'OnlyNumberLetter textbox validate[required,custom[onlyLetterNumber],max[15]]', 'label' => false, 'placeholder' => 'Last Name', 'div' => false)); ?>
                </div>
                <div class="field clearfix ft">
                    <label class="label">Gender <b class="red_star">*</b></label>
                    <?php
                    $gender = array('male' => 'Male', 'female' => 'Female');
                    echo $this->Form->input('gender', array('options' => $gender, 'class' => 'selects validate[required]', 'empty' => 'Select Gender', 'label' => false, 'div' => false));
                    ?>

                </div>
                <div class="field clearfix fr">
                    <label class="label">Profile pictures</label>
                    <?php
                    echo $this->Form->input('User.profile_pic', array('type' => 'file', 'class' => 'browse', 'label' => false));
                    if (isset($rider_info['User']['profile_pic']) && !empty($rider_info['User']['profile_pic'])) {
                        $rider_profile_image = $this->Custom->check_image($rider_info['User']['profile_pic'], 'profile_photo');
                        echo $this->Image->resize('data/profile_photo/' . $rider_profile_image, 125, 125, true, false);
                    } else {
                        echo $this->Image->resize('data/profile_photo/profile_photo_default.png', 125, 125, true, false);
                    }
                    ?>
                </div>

                <div class="field clearfix ft">
                    <label class="label">Contact No. <b class="red_star">*</b></label>
                    <?php echo $this->Form->input('phone', array('maxlength' => 15, 'id' => 'mobile-number', 'class' => 'onlyNumber textbox validate[required,custom[phone]]', 'label' => false, 'placeholder' => 'Contact No.', 'div' => false)); ?>
                </div>
                <div class="field clearfix fr">
                    <label class="label">Country Name <b class="red_star">*</b></label>
                    <?php
                    echo $this->Form->input('country_id', array('options' => $country_data, 'class' => 'selects country_select validate[require]', 'label' => false, 'div' => false));
                    echo $this->Form->hidden('country_name', array('class' => 'country_name'));
                    ?>


                </div>
                <div class="field clearfix ft">
                    <label class="label">State Name <b class="red_star">*</b></label>
                    <?php

                    if (empty($state_data)) {
                        $state_data = array();
                    }
                    echo $this->Form->input('state_id', array('options' => $state_data, 'class' => 'selects state_select validate[required]', 'empty' => 'Select State', 'label' => false, 'div' => false));
                    echo $this->Form->hidden('state_name', array('class' => 'state_name'));
                    ?>
                </div>
                <div class="field clearfix fr">
                    <label class="label">City<b class="red_star">*</b></label>
                    <?php echo $this->Form->input('city', array('class' => 'textbox validate[required]', 'label' => false, 'placeholder' => 'City', 'div' => false)); ?>
                </div>
                <div class="field clearfix ft">
                    <label class="label">Address </label>
                    <?php
                    echo $this->Form->input('address', array('type' => 'textarea', 'class' => ' textarea', 'rows' => '3', 'placeholder' => "Jaipur, Rajasthan, IN 302021", 'label' => false, 'div' => false));
                    ?>
                </div>
                <div class="field clearfix fr">
                    <label class="label">Postal Code<b class="red_star">*</b></label>
                    <?php echo $this->Form->input('zip', array('class' => 'textbox validate[required,custom[onlyNumberSp]]', 'type' => 'text', 'maxlength' => 6, 'label' => false, 'placeholder' => 'Zip Code', 'div' => false)); ?>
                </div>

                <div class="field clearfix ft about-pro">
                    <label class="label">About</label>
                    <?php
                    echo $this->Form->input('about', array('type' => 'textarea', 'class' => ' textarea', 'rows' => '3', 'placeholder' => "About your self", 'label' => false, 'div' => false));
                    ?>
                </div>
                <div class="clear"></div>

                <div class="info-edit-f">

                    <div class="btn-submit">
                        <?php
                        echo $this->Form->submit("Update", array('class' => 'submit-button h_f_b '));
                        ?>
                    </div>


                    <div class="a-link-btn"><a href="<?php echo SITE_URL . "riders/my_account"; ?>">Cancel</a></div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </section>
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


            // client side validation jquery valiadtion engine
            $("#UserInfoEditForm").validationEngine({scroll: false});
            // country change set state
            $(".country_select").live("change", function () {
                var element = $(this);
                var element_id = $(this).attr("id");
                var id = $(this).val();
                var datastring = "id=" + id + "";
                $.ajax({type: 'POST', async: false, dataType: 'json',
                    url: '<?php echo SITEURL; ?>users/state/',
                    data: datastring,
                    success: function (data) {
                        $(".state_select option").remove();
                        $.each(data, function (index, value) {
                            $(".state_select")
                                .append($("<option> Select State</option>")
                                    .attr("value", index)
                                    .text(value));

                        });
                        var country_name = $("#" + element_id + " option:selected").text();
                        $(".country_name").val(country_name)
                    },
                    error: function (comment) {
                    }
                });

            })


            // state change fill value in state select fields
            $(".state_select").live("change", function () {
                var element_id = $(this).attr("id");
                var state_name = $("#" + element_id + " option:selected").text();
                $(".state_name").val(state_name);
            });
        });
    </script>
<?php
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));
echo $this->Html->css(array('validationEngine.jquery'));
?>