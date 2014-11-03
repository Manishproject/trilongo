<div class="my-account">
    <?php echo $this->element('provider_header') ?>
    <div class="company-info">
        <div class="form-heading">
            <h2>Reservation Information</h2>
        </div>
        <div class="clear"></div>
        <?php
        if (isset($reservation_data) && !empty($reservation_data)) {
        ?>
        <div class="offer_info">
            <div class="offer_info_box">
                <div class="about_message">
                    <h2><strong>1</strong><span>Reservation Information</span>
                        <?php
                       /* if (isset($message_section['Message']['message']) && !empty($message_section['Message']['message'])) {
                            echo $message_section['Message']['message'];
                        } else {
                            echo "No message available";
                        }*/
                        //pr($reservation_data);
                        ?>
                        <b><i class="fa fa-clock-o"></i> <?php echo date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['created'])); ?></b>
                    </h2>

                    <div class="show_detail">
                        <?php
                        // get service type
                        $service_type = "";
                        if($reservation_data['Reservation']['service_id']==1){
                            $service_type = "Hire a driver";
                        }else  if($reservation_data['Reservation']['service_id']==2){
                            $service_type = "Book A taxi";
                        }else  if($reservation_data['Reservation']['service_id']==3){
                            $service_type = "Rent a vehicle";
                        }
                        ?>
                        <div class="show_blk"><strong>Price</strong>$<?php echo $reservation_data['Reservation']['provider_show_amount']; ?></div>
                        <div class="show_blk"><strong>Service Type</strong><?php echo ucfirst($service_type); ?></div>
                        <div class="show_blk"><strong>Service Start Date & Time</strong> <?php echo date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_start_date_time'])); ?></div>
                        <div class="show_blk"><strong>Service End Date & Time</strong> <?php echo date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_end_date_time'])); ?></div>
                        <div class="show_blk"><strong>Pickup Location</strong> <?php echo $reservation_data['Reservation']['pickup_location']; ?></div>
                        <div class="show_blk"><strong>Drop off Location</strong> <?php echo $reservation_data['Reservation']['drop_off_location']; ?></div>
                        <div class="clear"></div>
                        <?php
                        if ($reservation_data['Reservation']['provider_status'] == 0) {
                            ?>
                            <div class="offer_info_btn">
                                <?php
                                echo $this->Html->link('Accept', array('controller' => 'providers', 'action' => 'accept_cancel_after_selection_reservation/' . $reservation_data['Reservation']['id'] . "/1"), array('class' => 'grn', 'escape' => false, 'onclick' => "return confirm('Are you sure accept this invitation?')"));
                                echo $this->Html->link('Decline', array('controller' => 'providers', 'action' => 'accept_cancel_after_selection_reservation/' . $reservation_data['Reservation']['id'] . "/0"), array('class' => 'red', 'escape' => false, 'onclick' => "return confirm('Are you sure cancel this invitation?')"));
                                ?>
                            </div>

                        <?php
                        } else {
                            echo '<div class="offer_info_btn">';
                            echo $this->Html->link('Back', array('controller' => 'providers', 'action' => 'message_listing'), array('class' => 'red', 'escape' => false));
                            echo '</div>';
                        } ?>
                    </div>
                </div>

                <div class="clear"></div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
