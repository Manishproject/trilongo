<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
<?php echo $this->Html->script(array('res_booking')); ?>



<div class="reservation-from">
    <div class="form-heading"><h2>Basic Information</h2></div>
    <?php echo $this->Form->create('Reservation', array('novalidate'));
    echo $this->Session->flash('logmsg');
    ?>

    <?php echo $this->Form->hidden('service_id', array('value' => $service_id)); ?>
    <?php echo $this->Form->hidden('id'); ?>

    <div class="field clearfix ft">
        <label class="label">You are currently in<b class="red_star">*</b></label>
        <?php echo $this->Form->input('currently_in', array('class' => 'resevationlb selects validate[required]', 'options' => $Countries, 'label' => false, 'div' => false)); ?>
    </div>


    <div class="field clearfix fr">
        <label class="label">Are You Traveling As A <b class="red_star">*</b></label>
        <?php echo $this->Form->input('bookingtype', array('class' => 'resevationlb selects validate[required]', 'label' => false, 'div' => false, 'options' => $bookingtype)); ?>
    </div>


    <div id="ReservationTotalPassenger_wrapp">


        <div class="field clearfix ft">
            <label class="label">No. of Passenger<b class="red_star">*</b></label>
            <?php echo $this->Form->input('no_of_passengers', array('class' => 'textbox validate[required]', 'label' => false, 'placeholder' => 'No. of Passenger', 'div' => false)); ?>
        </div>
    </div>

    <div class="clear"></div>


    <div class="form-heading"><h2>Travel Option</h2></div>
    <?php echo $this->Form->input('travel_option', array('type' => 'radio', 'class' => 'redio trip_radio', 'value' => 'one_way', 'legend' => false, 'options' => array('one_way' => 'One way', 'round_trip' => 'Round Trip'))); ?>

    <div class="field clearfix ft">
        <label class="label">Departure Date<b class="red_star">*</b></label>

        <?php echo $this->Form->input('departure_date', array('placeholder' => 'Eg. 15-Aug-2014', 'label' => false, 'div' => false, 'class' => 'datepicker validate[required]')); ?>
    </div>
    <div class="field clearfix fr">

        <label class="label">Departure Time<b class="red_star">*</b></label>
        <?php echo $this->Form->input('departure_time', array('placeholder' => 'Eg. 09:30:00 AM', 'label' => false, 'div' => false, 'class' => 'timepicker validate[required]')); ?>
    </div>
    <div class="hidden round_trip">
        <div class="field clearfix ft">
            <label class="label">Return Date <b class="red_star">*</b></label>
            <?php echo $this->Form->input('return_date', array('placeholder' => 'Eg. 15-Aug-2014', 'label' => false, 'div' => false, 'class' => 'datepicker validate[required]')); ?>

        </div>
        <div class="field clearfix fr">
            <label class="label">Return Time <b class="red_star">*</b></label>
            <?php echo $this->Form->input('return_time', array('placeholder' => 'Eg. 09:30:00 AM', 'label' => false, 'div' => false, 'class' => 'timepicker validate[required]')); ?>
        </div>
    </div>

    <div class="clear"></div>
    <div class="form-heading"><h2>Let's Get Your Trip Details Below</h2></div>
    <div class="field clearfix ft">
        <label class="label">Pickup location</label>
        <?php echo $this->Form->input('pickup_location', array('class' => 'validate[required]', 'required' => true, 'label' => false, 'div' => false)); ?>
        <?php /* echo $this->Form->hidden('pickup_loc_lat');?>
<?php echo $this->Form->hidden('pickup_loc_long'); */
        ?> </div>
    <div class="field clearfix fr">
        <label class="label">Drop-off Location </label>
        <?php echo $this->Form->input('drop_off_location', array('class' => 'validate[required]', 'required' => true, 'label' => false, 'div' => false)); ?>
    </div>


    <div class="field clearfix ft">
        <label class="label">Vehicle Type<b class="red_star">*</b></label>
        <?php echo $this->Form->input('driver_vehicle_type', array('class' => 'resevationlb selects validate[required]', 'label' => false, 'div' => false, 'options' => $vehicletype)); ?>
    </div>
    <div class="clear"></div>

    <div class="form-heading"><h2>Driver Option</h2></div>

    <div>Does this driver speak and understand English?</div>
    <?php echo $this->Form->input('HireDriver.understand_english', array('type' => 'radio', 'class' => 'radio validate[required]', 'legend' => false, 'options' => array('1' => 'Yes', '0' => 'No'))); ?>

    <div>Is this driver licensed and insured?</div>
    <?php echo $this->Form->input('HireDriver.licensed', array('type' => 'radio', 'class' => 'radio validate[required]', 'legend' => false, 'options' => array('1' => 'Yes', '0' => 'No'))); ?>


    <div class="clear"></div>
    <?php
    echo $this->Form->submit('Next', array('div' => array('class' => 'btn-submit')));
    ?>

    <?php echo $this->Form->end(); ?>


</div>
<?php
echo $this->Html->css(array('validationEngine.jquery'));
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));

?>
<script>
    $(function () {
        $("#ReservationBookForm").validationEngine({scroll: false});
        $(".trip_radio").change(function () {
            if ($(this).val() == "round_trip") {
                $(".round_trip").removeClass("hidden");
            } else {
                $(".round_trip").addClass("hidden");
            }
        })
    });
</script>


   

