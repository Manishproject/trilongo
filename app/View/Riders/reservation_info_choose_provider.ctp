<div class="my-account">
    <div class="company-info">
        <div class="form-heading no-float"><h2>Provider Information</h2>

            <div class="clear"></div>
        </div>
        <div class="my-profile">
            <?php /*?><div class="form-heading"><h2>My Profile</h2></div><?php */ ?>
            <?php echo $this->Session->flash('logmsg'); ?>
            <div class="user-img">
      <span>
        <?php
        $provider_profile_image = $this->Custom->check_image($reservation_data['Provider']['User']['profile_pic'], 'profile_photo');
        echo $this->Image->resize('data/profile_photo/' . $provider_profile_image, 150, 150, true, false);
        ?>
    </span>
            </div>
            <div class="r-profile-c">
                <div class="user-name"><h2>
                        <?php echo ucfirst($reservation_data['Provider']['User']['first_name']) ?></h2>

                    <div class="current_st"><label>Current Status:-</label>
                        <?php
                        if (isset($reservation_data['Reservation']['provider_status'])) {
                            if ($reservation_data['Reservation']['provider_status'] == 0) {
                                echo '<div class="type-1"><i class="fa fa-bullhorn"></i>Pending</div>';
                            } else if ($reservation_data['Reservation']['provider_status'] == 1) {
                                echo ' <div class="type-2" style="display:block;"><i class="fa fa-thumbs-up"></i>Accepted</div>';
                            } else if ($reservation_data['Reservation']['provider_status'] == 2) {
                                echo '  <div class="type-3" style="display:block;"><i class="fa fa-thumbs-down"></i>Declined</div>';
                            }
                        }
                        ?>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="user-info">
                    <ul>
                        <?php $address = $this->Custom->get_address_formate($reservation_data['Provider']);
                        if (isset($address) && !empty($address)) {
                            echo '<li><i class="fa fa-globe"></i> ' . $address . '</li>';
                        }
                        if (isset($reservation_data['Provider']['User']['gender']) && !empty($reservation_data['Provider']['User']['gender'])) {
                            echo '<li><i class="fa fa-user"></i> ' . ucfirst($reservation_data['Provider']['User']['gender']) . '</li>';
                        }
                        if (isset($reservation_data['Provider']['User']['phone']) && !empty($reservation_data['Provider']['User']['phone'])) {
                            echo '<li><i class="fa fa-phone"></i> ' . ucfirst($reservation_data['Provider']['User']['phone']) . '</li>';
                        }
                        if (isset($reservation_data['Provider']['User']['email']) && !empty($reservation_data['Provider']['User']['email'])) {
                            echo '<li><i class="fa fa-envelope"></i> ' . ucfirst($reservation_data['Provider']['User']['email']) . '</li>';
                        }
                        ?>
                    </ul>
                    <?php
                    if (isset($reservation_data['Provider']['User']['about']) && !empty($reservation_data['Provider']['User']['about'])) {
                        echo '<p style="font-size: 16px; margin: 20px 0px 0px;">' . ucfirst(substr(htmlentities($reservation_data['Provider']['User']['about']), 0, 250)) . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>


    </div>
    <div class="company-info">
        <div class="form-heading no-float"><h2>Reservation Information</h2>
            <div class="clear"></div>
        </div>
        <div class="show_detail">

            <div class="show_blk"><strong>Price</strong>
                $<?php echo $reservation_data['Reservation']['total_amount']; ?></div>

            <?php
            $service_type = "";
            if($reservation_data['Reservation']['service_id']==1){
                $service_type = "Hire a driver";
            }else  if($reservation_data['Reservation']['service_id']==2){
                $service_type = "Book A taxi";
            }else  if($reservation_data['Reservation']['service_id']==3){
                $service_type = "Rent a vehicle";
            }

            ?>


            <div class="show_blk"><strong>Service Type</strong> <?php echo ucfirst($service_type); ?></div>
            <div class="show_blk"><strong>Service Start Date &
                    Time</strong> <?php echo date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_start_date_time'])); ?>
            </div>
            <div class="show_blk"><strong>Service End Date &
                    Time</strong> <?php echo date("Y-m-d h:i A", strtotime($reservation_data['Reservation']['service_end_date_time'])); ?>
            </div>
            <div class="show_blk"><strong>Pickup  Location</strong> <?php echo $reservation_data['Reservation']['pickup_location']; ?></div>
            <div class="show_blk"><strong>Drop off
                    Location</strong> <?php echo $reservation_data['Reservation']['drop_off_location']; ?></div>
            <div class="clear"></div>

        </div>
        <div class="offer_info_btn">
            <?php echo $this->Html->link('Back', array('controller' => 'riders', 'action' => 'my_account'), array('class' => 'red', 'escape' => false)); ?>
        </div>
    </div>
</div>
