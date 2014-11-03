<div class="provider_info_box">
    <div class="provider-info-box">

        <div class="form-heading"><h2>Provider Info</h2></div>
        <div class="provider-info-box-btm">
            <div class="user-img">
               <span> <?php
                   if (isset($reservation_data['Provider']['User']['profile_pic']) && !empty($reservation_data['Provider']['User']['profile_pic'])) {
                       $provider_profile_image = $this->Custom->check_image($reservation_data['Provider']['User']['profile_pic'], 'profile_photo');
                       echo $this->Image->resize('data/profile_photo/' . $provider_profile_image, 150, 150, true, false);
                   } else {
                       echo $this->Image->resize('data/profile_photo/profile_photo_default.png', 150, 150, true, false);
                   }
                   ?></span>
            </div>
            <div class="r-profile-c">
                <div class="user-name">
                    <h2><?php echo ucfirst($reservation_data['Provider']['User']['first_name']); ?></h2></div>
                <div class="user-info">
                    <ul>
                        <?php  $address = $this->Custom->get_address_formate($reservation_data['Provider']);
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
            <div class="clear"></div>
        </div>
    </div>

    <div class="billing-info-box">

        <div class="form-heading"><h2>Billing Info</h2></div>
        <div class="Booking-info-box-btm">


            <div class="book_info"><strong>Pick Up
                    Location</strong><?php echo ucfirst($reservation_data['Reservation']['pickup_location']); ?></div>

            <div class="book_info"><strong>Drop Off
                    Location</strong><?php echo ucfirst($reservation_data['Reservation']['drop_off_location']); ?></div>

            <div class="book_info"><strong>Service Start Date &
                    Time</strong><?php echo date('Y-m-d h:i:A', strtotime($reservation_data['Reservation']['service_start_date_time'])); ?>
            </div>
            <div class="book_info"><strong>Service End Date &
                    Time</strong><?php echo date('Y-m-d h:i:A', strtotime($reservation_data['Reservation']['service_end_date_time'])); ?>
            </div>
            <div class="book_info">

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


                <strong>Service Type</strong><?php echo ucfirst($service_type); ?></div>
            <div class="book_info">
                <strong>Payment</strong>$<?php echo $reservation_data['Reservation']['total_amount']; ?></div>
            <div class="clear"></div>

            <div class="btn_new"><a href="<?php echo SITE_URL . "riders/my_account" ?>">My Account</a></div>

        </div>

        <div class="clear"></div>
    </div>
</div>


</div>