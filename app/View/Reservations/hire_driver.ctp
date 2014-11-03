<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places"></script>
<script>
    var country_code = '<?php  echo $country_code ?>';
</script>
<?php echo $this->Html->script(array('CarRental')); ?>
<div class="reservation-from">
<!--    <div class="form-heading"><h2>Basic Information</h2></div>-->
    <?php echo $this->Form->create('Reservation', array('novalidate'));
    echo $this->Session->flash('logmsg');
    ?>

    <!--    <div class="field clearfix ft">-->
    <!--        <label class="label">You are currently in<b class="red_star">*</b></label>-->
    <!--        --><?php //echo $this->Form->input('currently_in', array('class' => ' change_location resevationlb selects validate[required]', 'options' => $Countries, 'label' => false, 'div' => false)); ?>
    <!--    </div>-->

<!---->
<!--    <div class="field clearfix ft">-->
<!--        <label class="label">Are You Traveling As A <b class="red_star">*</b></label>-->
<!--        --><?php //echo $this->Form->input('bookingtype', array('class' => 'resevationlb selects validate[required]', 'label' => false, 'div' => false, 'options' => $bookingtype)); ?>
<!--    </div>-->


    <div id="ReservationTotalPassenger_wrapp" style="display: none;">
        <div class="field clearfix fr">
            <label class="label">No. of Passenger<b class="red_star">*</b></label>
            <?php echo $this->Form->input('total_passenger', array('class' => 'textbox validate[required]', 'label' => false, 'placeholder' => 'No. of Passenger', 'div' => false)); ?>
        </div>
    </div>

    <div class="clear"></div>


    <div class="form-heading"><h2>Travel Option</h2></div>
    <?php //echo $this->Form->input('travel_option', array('type' => 'radio', 'class' => 'redio trip_radio', 'value' => 'one_way', 'legend' => false, 'options' => array('one_way' => 'One way', 'round_trip' => 'Round Trip'))); ?>

    <div class="field clearfix ft">
        <label class="label">Service Start Date & Time <b class="red_star">*</b></label>

        <?php echo $this->Form->input('service_start_date', array('id' => 'service_start_date', 'placeholder' => 'Eg. 15-Aug-2014', 'label' => false, 'div' => false, 'class' => 'start_date_time_picker validate[required]')); ?>
    </div>

    <div class="field clearfix fr">
        <label class="label">Service End Date & Time <b class="red_star">*</b></label>

        <?php echo $this->Form->input('service_end_date', array('id' => 'service_end_date', 'placeholder' => 'Eg. 15-Aug-2014', 'label' => false, 'div' => false, 'class' => 'end_date_time_picker validate[required]')); ?>
    </div>




    <div class="clear"></div>
    <div class="form-heading"><h2>Let's Get Your Trip Details Below</h2></div>

    <div class="map_view_top">
        <div class="field clearfix ft">
            <label class="label">Pickup location</label>
            <?php echo $this->Form->input('pickup_location', array('class' => 'validate[required]', 'id' => 'FromTextBox', 'required' => true, 'label' => false, 'div' => false)); ?>
            <?php /* echo $this->Form->hidden('pickup_loc_lat');?>
<?php echo $this->Form->hidden('pickup_loc_long'); */
            ?> </div>
        <div class="field clearfix fr">
            <label class="label">Drop-off Location </label>
            <?php echo $this->Form->input('drop_off_location', array('class' => 'validate[required]', 'id' => 'ToTextBox', 'required' => true, 'label' => false, 'div' => false)); ?>
        </div>
        <?php
        echo $this->Form->hidden('map_estimated_hours', array('id' => 'map_estimated_hours_hidden', 'value' => ''));
        echo $this->Form->hidden('map_estimated_min', array('id' => 'map_estimated_min_hidden', 'value' => ''));
        echo $this->Form->hidden('map_estimated_distance', array('id' => 'map_estimated_distance_hidden', 'value' => ''));
        ?>
        <!--    <div class="fr go_btn"><a id="GoButton" href="javascript:void(0)">Go</a></div>-->
        <div class="clear"></div>
    </div>


    <div class="clear"></div>
<!--    <div style="height:100%; width:100%;">-->
<!--        <div id="map-canvas"></div>-->
<!--    </div>-->
    <div class="map_view_mid">
        <div id="TimeDisplay" class="ft"></div>
        <div id="DistanceDisplay" class="fr"></div>
        <div class="clear"></div>
    </div>
    <div id="TotalDistanceValue"></div>
<!--    <div id="DirectionsDetails"></div>-->
    <?php
    echo $this->Form->submit('Next', array('div' => array('class' => 'btn-submit','id'=>'next_reservation')));
    echo $this->Form->submit('Please wait', array('type'=>'button','div' => array('class' => 'btn-submit','id'=>'wait_next_reservation')));
    echo $this->Form->end();
    ?>
</div>
<?php
echo $this->Html->css(array('validationEngine.jquery', '/timepicker/jquery.timepicker'));
echo $this->Html->script(array('jquery-ui-timepicker-addon','/timepicker/jquery.timepicker', 'jquery.validationEngine', 'jquery.validationEngine-en'));
?>
<script>



    $(function () {
        $("#ReservationBookForm").validationEngine({scroll: false});
    });
</script>
<style>
    #directions tr:nth-child(even) {background-color: #E0E0E0;}
    #directions tr:nth-child(odd) {background-color: #F0F0F0;}
</style>

   
