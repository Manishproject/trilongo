<?php echo $this->Html->script(array('intlTelInput'));?>
<?php echo $this->Html->css(array('intlTelInput'));?>
<div class="suggestion-left">
    <p>Your booking is almost complete. Let's get some of your information....</p>
    <div class="form-heading">
        <h2>Contact Details</h2>
    </div>
    <!--// check login user detail isset or not -->
    <?php
    echo $this->Session->flash('logmsg');
    if (isset($login_user_detail) && !empty($login_user_detail) && !empty($login_user_detail['User']['country_name'])) {
        $this->request->data['Reservation'] = $login_user_detail['User'];
    }
    ?>
    <?php echo $this->Form->create('Reservation', array('novalidate')) ?>
    <div class="address">
        <div class="field clearfix ft">
            <label class="label">Phone Number<b class="red_star">*</b></label>
            <?php echo $this->Form->input('Reservation.phone', array( 'maxlength' => 15,'id'=>'mobile-number','label' => false, 'class' => 'onlyNumber textbox validate[required,custom[phone]]', 'placeholder' => 'Phone Number')); ?>
        </div>
        <div class="field clearfix fr">
            <label class="label">County Name <b class="red_star">*</b></label>
            <?php
            echo $this->Form->input('Reservation.country_id', array('options' => $Countries, 'class' => 'selects country_select validate[required]', 'label' => false, 'div' => false));
            echo $this->Form->hidden('Reservation.country_name', array('class' => 'country_name'));
            ?>
        </div>
        <div class="field clearfix ft">
            <label class="label">State Name <b class="red_star">*</b></label>
            <?php
            if (empty($state_data)) {
                $state_data = array();
            }
            echo $this->Form->input('Reservation.state_id', array('options' => $state_data, 'class' => 'selects state_select validate[required]', 'empty' => 'Select State', 'label' => false, 'div' => false));
            echo $this->Form->hidden('Reservation.state_name', array('class' => 'state_name'));
            ?>
        </div>
        <div class="field clearfix fr">
            <label class="label">City<b class="red_star">*</b></label>
            <?php echo $this->Form->input('Reservation.city', array('label' => 'City', 'label' => false, 'class' => 'OnlyNumberLetter textbox validate[required]', 'placeholder' => 'City')); ?>
        </div>
        <div class="field clearfix ft">
            <label class="label">Address </label>
            <?php
            echo $this->Form->input('Reservation.address', array('type' => 'textarea', 'class' => 'OnlyNumberLetter  textarea', 'rows' => '3', 'placeholder' => "Jaipur, Rajasthan, IN 302021", 'label' => false, 'div' => false));
            ?>
        </div>
        <div class="field clearfix fr">
            <label class="label">Communication Option<b class="red_star">*</b></label>
            <?php echo $this->Form->input('Reservation.communication_option', array('label' => 'Prefer communication', 'type' => 'radio', 'class' => 'redio validate[required]', 'legend' => false, 'options' => array('1' => 'Sms', '2' => 'Email', '3' => 'sms & email'))); ?>
        </div>
        <div class="clear"></div>
    </div>
    <div class="choose_suggestion">
        <div class="form-heading choose-pro">
            <h2>Choose Provider</h2>
        </div>
        <div class="suggestion-right">
            <div id="suggested_provider">
                <ul>
                    <?php
                    $max_price =   $options = array();
                    foreach ($suggested_provider as $provider) {
                      $max_price[]=   number_format($provider['ServiceInformation']['rate'],2,'.', '');
                        ?>
                        <li>
                            <?php echo $this->Form->input('provider_id_radio', array('type' => 'radio', 'data-id' => base64_encode($provider['Provider']['id']), 'data-price' => base64_encode(number_format($provider['ServiceInformation']['rate'],2) ), 'hiddenField' => false, 'class' => ' redio trip_radio validate[required]', 'label' => false, 'div' => false, 'legend' => false, 'options' => array('one_way' => ''))); ?>
                            <figure><img src="<?php echo SITE_URL ?>images/driver-icon.jpg" alt="Driver image"/>
                            </figure>
                            <i>$<?php  echo number_format($provider['ServiceInformation']['rate'],2)  ?></i>
                            <?php
                            // calculated type
                            $type = "";
                            if($provider['ServiceInformation']['service_type_id'] ==1){
                                $type = "Per KM";
                            }else   if($provider['ServiceInformation']['service_type_id'] ==2){
                                $type = "Per Hour";
                            }else   if($provider['ServiceInformation']['service_type_id'] ==3){
                                $type = "Per Day";
                            }
                            echo $type;
                            ?>
                        </li>
                    <?php
                    }
                    ?>

                </ul>
                <?php
                echo $this->Form->hidden('provider_id', array());
                ?>
                <div class="clear"></div>

            </div>
        </div>
        <div class="choose-p-btn"><div class="form_btm_btn">
            <?php
            echo $this->Form->submit('Book', array('value' => 'book', 'class' => 'submit-button h_f_b',));
            ?>
            <div class="clear"></div>
        </div>
        <div class="edit-btn skip_provider"><a href="javascript:void(0)">Click to suggest a price</a></div></div>
    </div>



    <div class="put_your_price hidden">
        <div class="form-heading">
            <h2>Put Your Price</h2>
        </div>
        <div class="suggestion-right">
            <div id="suggested_provider_put_price">
                <div class="min_price">
                    <?php echo $this->Form->input('your_price', array('label' => 'Enter Your Price and Submit', 'type' => 'text', 'class' => 'onlyNumber validate[required],min[1],max['.min($max_price).']')); ?>
                    <div class="type"><?php echo $type;  ?></div>
                    <div class="form_btm_btn">
                        <?php
                        echo $this->Form->submit('Submit', array('value' => 'book', 'class' => 'submit-button h_f_b',));
                        ?>
                    </div>
                </div>
                <div class="skip_our_price"><a href="javascript:void(0)">Forget it. I'll take trilongo's price!</a></div>
                 <div class="clear"></div>

            </div>
        </div>
    </div>




    <?php echo $this->Form->end(); ?>
</div>
<?php
echo $this->Html->css(array('validationEngine.jquery'));
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));
?>
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


        $("#ReservationSelectProviderForm").validationEngine({scroll: false});

        // click on skip provider and put your price
        $(".skip_provider").click(function () {
            // remove all checked class and also unchecked all radio button
            $("#suggested_provider ul li").removeClass("checked");
            $("#suggested_provider ul li").find(".trip_radio").attr("checked", false);
            // remove value from hidden fields that means user put his price
            $("#ReservationProviderId,#price_provider").val('');
            $(".choose_suggestion").hide();
            $(".put_your_price").show();
            $(".ReservationProviderIdRadioOneWayformError").remove();
        });

        // user want skip his price
        $(".skip_our_price").click(function () {
            $(".put_your_price").hide();
            $(".choose_suggestion").show();
            $(".ReservationYourPriceformError").remove();
            $("#ReservationYourPrice").val('');
        })
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


        // li click auto c;lick on radio button
        $("#suggested_provider ul li").click(function () {
            $("#suggested_provider ul li").removeClass("checked");
            $(this).addClass("checked");
            $("#ReservationProviderId").val($(this).find("input").attr("data-id"));
            $("#price_provider").val($(this).find("input").attr("data-price"));
            $(this).find(".trip_radio").attr("checked", true)
        })

    });

</script>