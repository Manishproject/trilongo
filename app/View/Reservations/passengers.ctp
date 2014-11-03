<section class="pg_mid">
    <div class="form_pages">
        <?php

        echo $this->Form->Create('Passenger');
        echo $this->Session->flash('logmsg');
        for ($i = 0;
        $i < $no_of_pass;
        $i++) {
        echo ' <div class="form-heading"><h2>Passenger :' . ($i + 1) . '</h2></div>';
        echo $this->Form->hidden("Passenger.$i.reservation_id", array('value' => $reservation['Reservation']['id']));
        ?>
        <div class="field clearfix ft">
            <label class="label">Passenger First Name<b class="red_star">*</b></label>
            <?php echo $this->Form->input("Passenger.$i.fname", array('class' => 'resevationlb textbox validate[required]', 'label' => false, 'div' => false,)); ?>
        </div>
        <div class="field clearfix fr">
            <label class="label">Passenger Last Name<b class="red_star">*</b></label>
            <?php echo $this->Form->input("Passenger.$i.lname", array('class' => 'resevationlb textbox validate[required]', 'label' => false, 'div' => false,)); ?>
        </div>

        <div class="field clearfix ft">
            <label class="label">Passenger Mobile<b class="red_star">*</b></label>
            <?php echo $this->Form->input("Passenger.$i.mobile", array('class' => 'resevationlb textbox validate[required,custom[phone]]', 'label' => false, 'div' => false,)); ?>
        </div>

        <div class="field clearfix fr">
            <label class="label">Passenger Handicap<b class="red_star">*</b></label>
            <?php echo $this->Form->input("Passenger.$i.handicap", array('type' => 'radio', 'class' => 'redio', 'value' => '0', 'legend' => false, 'options' => array('0' => 'No', '1' => 'Yes'))); ?>
        </div>


        <div class="btn-submit">
            <?php
            }
            echo $this->Form->submit('Calculate Fee');
            echo $this->Form->end();
            ?>
        </div>
    </div>
</section>
<?php
echo $this->Html->css(array('validationEngine.jquery'));
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));
?>
<script>
    $(function () {
        $("#PassengerPassengersForm").validationEngine({scroll: false});
    });
</script>