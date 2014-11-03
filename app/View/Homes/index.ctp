<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
<div id="demo" class="skdslider">
    <ul>
        <li><img src="<?php echo SITE_URL ?>images/bannerpic1.jpg"/></li>
        <li><img src="<?php echo SITE_URL ?>images/bannerpic2.jpg"/></li>
        <li><img src="<?php echo SITE_URL ?>images/bannerpic3.jpg"/></li>
        <li><img src="<?php echo SITE_URL ?>images/bannerpic4.jpg"/></li>
        <li><img src="<?php echo SITE_URL ?>images/bannerpic5.jpg"/></li>
    </ul>
    <div class="main-div">
        <div class="slide-desc">
            <h1>Get any ride, anytime from <span> local providers</span> around the world.</h1>

            <div class="slider_info"><span>Hire a Driver</span> <span>Book a Taxi</span> <span>Rent a Vehicle</span></div>
            <div class="download-link">

                <?php echo $this->Form->create('SendAppLink', array('url' => array('controller' => 'users', 'action' => 'send_app_link'), 'id' => 'send_app_form')); ?>
                <div>
                    <div class="e-phone-n hm_input">
                        <?php echo $this->Form->input('phone_number', array('placeholder' => 'Enter Phone Number', 'label' => false, 'id' => 'mobile-number', 'div' => false, 'class' => 'validate[required]')); ?>
                    </div>
                    <div class="e-download-n hm_input">
                        <?php echo $this->Form->submit("GET trilongo's PHONE APP", array('id' => 'send_app_link', 'type' => 'button')); ?>
                    </div>
                </div>
                <div class="ServerError"></div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<div id="book-taxi-bg">

    <div class="main-div">
        <?php echo $this->Session->flash('logmsg'); ?>
        <div class="book-taxi-c">
            <div id="tabs">
                <ul>
                    <li class="tab1"><a href="javascript:void(0)" id="taber1" onClick="taber1(this.id);" class="active"><i>&nbsp;</i> Hire a Driver</a></li>
                    <li class="tab2"><a href="javascript:void(0)" id="taber2" onClick="taber1(this.id);" class=""><i>&nbsp;</i>Book a Taxi</a></li>
                    <li class="tab3"><a href="javascript:void(0)" id="taber3" onClick="taber1(this.id);" class=""><i>&nbsp;</i>Rent a Vehicle</a></li>
                </ul>
                <section>
                    <div id="taber11" class="tax1-c">
                        <h2>Get A Driver
                            <?php
                            if (isset($country_name) && !empty($country_name)) {
                                ?>
                                <span>Your current location is"<?php echo $country_name; ?>".Please <a
                                        href="javascript:void(0);" class="top_drop_down">click here</a> to change the country</span>
                            <?php } ?>
                        </h2>
                        <?php
                        echo $this->Form->create('SearchDriver', array('url' => array('controller' => 'reservations', 'action' => 'preprocess_booking', '1'), 'id' => 'HireDriver'));
                        ?>
                        <div class="pickup"><span>Pickup Location</span> <br/>
                            <?php echo $this->Form->input('pick_up', array('placeholder' => 'Start typing...Eg: MI Road', 'label' => false, 'div' => false, 'class' => 'OnlyNumberLetter validate[required,custom[NotOnlyNumber]]')); ?>
                        </div>
                        <div class="pickup"><span>Drop-off Location</span> <br/>
                            <?php echo $this->Form->input('drop_off', array('placeholder' => 'Start typing...Eg: MI Road', 'label' => false, 'div' => false, 'class' => 'OnlyNumberLetter validate[required]')); ?>
                        </div>
                        <div class="pickup-d"><span>Service Start Date & Time</span> <br/>
                            <?php echo $this->Form->input('service_start_date', array('readonly' => 'true', 'placeholder' => 'Eg. 15-Aug-2014', 'label' => false, 'div' => false, 'class' => 'start_date_time_picker validate[required]')); ?>
                        </div>
                        <div class="pickup-d"><span>Service End Date & TIme</span> <br/>
                            <?php echo $this->Form->input('service_end_date', array('readonly' => 'true', 'placeholder' => 'Eg. 15-Aug-2014', 'label' => false, 'div' => false, 'class' => 'end_date_time_picker validate[required]')); ?>
                        </div>

                        <div class="btn-submit">
                            <?php echo $this->Form->submit('Next Step', array('name' => 'Submit')); ?>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                    <div id="taber21" class="tax1-c" style="display:none;">
                        <h2>Search for a Taxi
                            <?php
                            if (isset($country_name) && !empty($country_name)) {
                                ?>
                                <span>Your current location is"<?php echo $country_name; ?>".Please <a
                                        href="javascript:void(0);" class="top_drop_down">click here</a> to change the country</span>
                            <?php } ?>
                        </h2>
                        <?php
                        echo $this->Form->create('SearchTaxi', array('url' => array('controller' => 'reservations', 'action' => 'preprocess_booking', '2'), 'id' => 'HireTaxi'));
                        ?>

                        <div class="pickup"><span>Pickup Location</span> <br/>
                            <?php echo $this->Form->input('pick_up', array('placeholder' => 'Start typing...Eg: MI Road', 'label' => false, 'div' => false, 'class' => 'OnlyNumberLetter validate[required]')); ?>
                        </div>
                        <div class="pickup"><span>Drop-off Location</span> <br/>
                            <?php echo $this->Form->input('drop_off', array('placeholder' => 'Start typing...Eg: MI Road', 'label' => false, 'div' => false, 'class' => 'OnlyNumberLetter validate[required]')); ?>
                        </div>
                        <div class="pickup-d"><span>Service Start Date & Time</span> <br/>
                            <?php echo $this->Form->input('service_start_date', array('readonly' => 'true', 'placeholder' => 'Eg. 15-Aug-2014', 'label' => false, 'div' => false, 'class' => 'start_date_time_picker validate[required]')); ?>
                        </div>

                        <div class="pickup-d"><span>Service End Date & Time</span> <br/>
                            <?php echo $this->Form->input('service_end_date', array('readonly' => 'true', 'placeholder' => 'Eg. 15-Aug-2014', 'label' => false, 'div' => false, 'class' => ' end_date_time_taxi_picker  validate[required]')); ?>
                        </div>

                        <div class="btn-submit">
                            <?php echo $this->Form->submit('Next Step', array('name' => 'Submit')); ?>
                        </div>


                        <?php echo $this->Form->end(); ?>
                    </div>
                    <div id="taber31" class="tax1-c" style="display:none;">
                        <h2>Search for a Vehicle
                            <?php
                            if (isset($country_name) && !empty($country_name)) {
                                ?>
                                <span>Your current location is"<?php echo $country_name; ?>".Please <a
                                        href="javascript:void(0);" class="top_drop_down">click here</a> to change the country</span>
                            <?php } ?>

                        </h2>
                        <?php
                        echo $this->Form->create('SearchVehicle', array('url' => array('controller' => 'reservations', 'action' => 'preprocess_booking', '3'), 'id' => 'HireVehicle'));
                        ?>
                        <div class="pickup"><span>Pickup Location</span> <br/>
                            <?php echo $this->Form->input('pick_up', array('placeholder' => 'Start typing...Eg: MI Road', 'label' => false, 'div' => false, 'class' => 'OnlyNumberLetter validate[required]')); ?>
                        </div>
                        <div class="pickup"><span>Drop-off Location</span> <br/>
                            <?php echo $this->Form->input('drop_off', array('placeholder' => 'Start typing...Eg: MI Road', 'label' => false, 'div' => false, 'class' => 'OnlyNumberLetter validate[required]')); ?>
                        </div>
                        <div class="pickup-d"><span>Service Start Date & TIme</span> <br/>
                            <?php echo $this->Form->input('service_start_date', array('readonly' => 'true', 'placeholder' => 'Eg. 15-Aug-2014', 'label' => false, 'div' => false, 'class' => 'start_date_time_picker validate[required]')); ?>
                        </div>

                        <div class="pickup-d"><span>Service End Date & Time</span> <br/>
                            <?php echo $this->Form->input('service_end_date', array('readonly' => 'true', 'placeholder' => 'Eg. 15-Aug-2014', 'label' => false, 'div' => false, 'class' => 'end_date_time_picker validate[required]')); ?>
                        </div>

                        <div class="btn-submit">
                            <?php echo $this->Form->submit('Next Step', array('name' => 'Submit')); ?>
                        </div>


                        <?php echo $this->Form->end(); ?>
                    </div>
                </section>
            </div>
            <div class="box-r-arrow"><img src="<?php echo SITE_URL ?>images/book-r-arrow.png" alt=""/></div>
            <div class="box-cartoon">
                <div class="hide_bg"></div>
                <img src="<?php echo SITE_URL ?>images/d-cartoon.png" alt=""/></div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div id="howtrilongo" class="bg1 parallax ">
    <div class="main-div">
        <div class="how-trilongo-c">
            <div class="heading">
                <h2><i><img src="<?php echo SITE_URL ?>images/video-icon.png" alt=""/></i>How trilongo works</h2>
            </div>
            <div class="video">
                <iframe width="982" height="540" src="http://www.youtube-nocookie.com/embed/trq4RL4YbAg" frameborder="0"
                        allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
<div id="our-content">
    <div class="main-div">
        <div class="o-providers">
            <div class="heading">
                <h3><i style="margin-top: -6px;"><img src="<?php echo SITE_URL ?>images/Providers-icon.png" alt=""/></i>Providers
                </h3>
            </div>
            <div class="o-p-c"><span>Our Providers are some of the best in the business and because of this trilongo provides</span>

                <div class="p-r-c">
                    <!--                    <p>In publishing and graphic design, lorem ipsum is a filler text commonly used to demonstrate the-->
                    <!--                        graphic elements of a document or visual presentation.</p>-->
                    <ul>
                        <li>No change to your current pricing structure</li>
                        <li>Easy to use interface for customer notification</li>
                        <li>Access to Riders around the world</li>
                    </ul>
                    <div class="btn" style="display: none;"><a
                            href="<?php echo SITE_URL . "page/provider-more-stories"; ?>">more of trilongo</a></div>
                </div>
                <div class="p-r-i"><img src="<?php echo SITE_URL ?>images/providers-img.jpg" alt=""/></div>
            </div>
        </div>
        <div class="o-providers o-riders">
            <div class="heading">
                <h3><i><img src="<?php echo SITE_URL ?>images/our-riders-icon.png" alt=""/></i>Riders</h3>
            </div>
            <div class="o-p-c"><span>For the Riders trilongo provides you with</span>

                <div class="p-r-c">
                    <ul>
                        <li>Multiple services</li>
                        <li>Affordable pricing</li>
                        <li>Professional Providers</li>
                        <li>Access in multiple cities and countries worldwide</li>
                        <li>Suggest your own pricing</li>
                        <li>No base pricing or surge pricing</li>
                    </ul>
                    <div class="btn" style="display: none;"><a href="<?php echo SITE_URL . "page/rider-more-story"; ?>">more
                            stories</a></div>
                </div>
                <div class="p-r-i"><img src="<?php echo SITE_URL ?>images/riders-img.jpg" alt=""/></div>
            </div>
        </div>
        <div></div>
    </div>
</div>
<div class="clear"></div>
<div id="need-ride" class="bg2 parallax">
    <h3> Need A Ride?<br/>
        We'll Be In Your Neighborhood Soon...</h3>
</div>
<div class="clear"></div>
<?php echo $this->Html->script(array('jquery.form', 'intlTelInput', 'jquery-ui-timepicker-addon', '/timepicker/jquery.timepicker', 'jquery.validationEngine', 'jquery.validationEngine-en'));
echo $this->Html->css(array('intlTelInput', '/timepicker/jquery.timepicker', 'validationEngine.jquery'));

?>

<script>

    $(function () {
// enter form submit

        $('#send_app_form').keypress(function (e) {
            if (e.which === 13) {
                $("#send_app_link").click();
                return false;
            }
        });


        // send sms via ajax form submit
        $('#send_app_link').live('click', function () {
            $("#send_app_form").ajaxForm({
                beforeSubmit: function () {
                    $(".ServerError").html('');
                    var mobile_number = $.trim($("#mobile-number").val());
                    if (!mobile_number) {
                        $(".ServerError").html("Please enter mobile number");
                        return false;
                    }
                    $('#send_app_link').attr("disabled", true);
                    $('#send_app_link').val("Please wait...");
                },
                success: function (response) {
                    $('#send_app_link').val("GET trilongo's PHONE APP");
                    var data = $.parseJSON(response);
                    if (data['status'] == 1) {
                        $(".ServerError").css({color: 'green'});
                        $(".ServerError").html(data['message']);
                        $("#send_app_form")[0].reset();

                    } else {
                        $(".ServerError").html(data['message']);
                    }
                    $('#send_app_link').attr("disabled", false);
                }
            }).submit();
        });


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


        // header drop down
        $(".top_drop_down").click(function () {
            var body = $("html, body");
            $("#country").css({'background': '#ffffe6'});
            body.animate({scrollTop: 0}, '500', 'swing', function () {
            });

        })


        // for taxi
        $('.end_date_time_taxi_picker').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'hh:mm TT',

            minDate: getFormattedDate(new Date()),
            onClose: function (selectedDate) {
                var start_date = $(this).closest("form").find(".start_date_time_picker").val();
                if (start_date == "") {
                    $(".end_date_time_taxi_picker").val('');
                    alert("Please fill start date");
                } else {
                    compare_date_for_taxi(selectedDate, start_date);
                }
            }
        });


        $('.end_date_time_picker').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'hh:mm TT',
//            showButtonPanel:false,
            minDate: getFormattedDate(new Date()),

            onClose: function (selectedDate) {
                var start_date = $(this).closest("form").find(".start_date_time_picker").val();
                if (start_date == "") {
                    $(".end_date_time_picker").val('');
                    alert("Please fill start date");
                } else {
                    compare_date(selectedDate, start_date);
                }
            }
        });


        $('.start_date_time_picker').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'hh:mm TT',
            minDate: getFormattedDate(new Date()),
            onSelect: function (selectedDate) {

            },
            beforeShow: function () {

            }
        });

        $(".start_date_time_picker").change(function () {
            $(".end_date_time_picker,.end_date_time_taxi_picker").val('');
        })

        $("#HireDriver,#HireVehicle,#HireTaxi,#send_app_link").validationEngine({scroll: false});
        google.maps.event.addDomListener(window, 'load', get_address_header('SearchDriverPickUp'), get_address_header('SearchDriverDropOff'), get_address_header('SearchTaxiPickUp'), get_address_header('SearchTaxiDropOff'), get_address_header('SearchVehiclePickUp'), get_address_header('SearchVehicleDropOff'));
    });

    function get_address_header(id) {
        var input1 = document.getElementById(id);
        var options = {
            componentRestrictions: {country: '<?php  echo $country_code ?>'}
        };
        var autocomplete = new google.maps.places.Autocomplete(input1, options);
        google.maps.event.addDomListener(autocomplete, 'place_changed', function (e) {
            var place = autocomplete.getPlace();
            if (typeof place.formatted_address != 'undefined') {
                $('#' + id).val(place.formatted_address);
            }
        });
    }
</script>

