
<?php



?>
<div class="my-account">
    <div class="company-info">
        <div class="form-heading no-float"><h2>Reservation Information</h2>

            <div class="clear"></div>
        </div>
        <div class="offer_info">
            <div class="offer_info_box">
                <div class="about_message">
                    <h2><strong>1</strong><span>Reservation Information</span>
                        <b><i class="fa fa-clock-o"></i> <?php echo date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['created'])); ?>
                        </b>
                    </h2>

                    <div class="show_detail">
                        <div class="show_blk"><strong>Your Price</strong>
                            $<?php echo $reservation_data['Reservation']['your_price']." ". $reservation_data['Reservation']['your_price_type']; ?>
                        </div>
                        <div class="show_blk"><strong>Your Total Price</strong>
                            $<?php echo $reservation_data['Reservation']['your_total_price'];?>
                        </div>
                        <div class="show_blk"><strong>Service Start Date &
                                Time</strong> <?php echo date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_start_date_time'])); ?>
                        </div>
                        <div class="show_blk"><strong>Service End Date &
                                Time</strong> <?php echo date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_end_date_time'])); ?>
                        </div>
                        <div class="show_blk"><strong>Pickup
                                Location</strong> <?php echo $reservation_data['Reservation']['pickup_location']; ?>
                        </div>
                        <div class="show_blk"><strong>Drop off
                                Location</strong> <?php echo $reservation_data['Reservation']['drop_off_location']; ?>
                        </div>
                        <div class="clear"></div>

                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="company-info">
        <div class="form-heading no-float"><h2>Your Provider Queue</h2>

            <div class="clear"></div>
        </div>
        <div class="offer_info">
            <div class="Proposal_imfo_box">
                <div class="about_message">
                    <?php
                    if (isset($reservation_data['Proposal']) && !empty($reservation_data['Proposal'])) {
                        foreach ($reservation_data['Proposal'] as $key => $value) {
                            ?>
                            <div>
                                <h2><strong><?php echo($key + 1) ?></strong>
                                    <span class="d_icon"></span>
                                    <span>Let's go with this Provider</span>
                                    <span class="proposal_rate">$<?php echo $value['amount']." ".$value['price_type'] ;  ?></span>
                                    <span class="proposal_rate">$<?php echo $value['total_price'];  ?></span>
                                    <?php
                                    // check awarded sign or
                                    if ($reservation_data['Reservation']['provider_id'] == $value['provider_id']) {
                                        echo "<span class='aw_img'>Awarded  </span>";
                                    }
                                    // only select provider front show payment option and awarded link
                                    if ($reservation_data['Reservation']['provider_id'] == $value['provider_id']) {
                                        if ($reservation_data['Reservation']['is_payment_complete']) {
                                            echo "<span class='per_text'>Payment Completed  </span>";
                                        } else if (!empty($reservation_data['Reservation']['provider_id'])) {
                                            ?>
                                            <span class='per_text'>Payment  not Completed  </span>
                                            <div class="offer_info_btn">
                                                <?php echo $this->Html->link('Payment', array('controller' => 'reservations', 'action' => 'payment', $value['provider_id'], $reservation_data['Reservation']['id']), array('class' => 'grn', 'escape' => false)); ?>
                                            </div>
                                        <?php
                                        }
                                    }

                                    // check provider is choose or not
                                    // if choose then remove accept button
                                    if (empty($reservation_data['Reservation']['provider_id'])) {
                                        ?>
                                        <div class="offer_info_btn">
                                            <?php echo $this->Html->link('Accept', array('controller' => 'riders', 'action' => 'rider_choose_provider', $value['provider_id'], $reservation_data['Reservation']['id'], $value['id']), array('class' => 'grn', 'escape' => false)); ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </h2>
                            </div>
                        <?php
                        }
                    } else {
                        echo "No Proposal found";
                    }
                    ?>
                    <div class="offer_info_btn">
                        <?php echo $this->Html->link('Back', array('controller' => 'riders', 'action' => 'my_account'), array('class' => 'red', 'escape' => false)); ?>
                    </div>

                </div>
            </div>
        </div>


    </div>
</div>
