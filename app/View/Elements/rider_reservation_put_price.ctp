<div class="content_row put_price ">
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
    <div class="phone_numbr"><?php  echo $service_type; ?> </div>
    <div class="drv_name"><?php echo $reservation_data['Reservation']['pickup_location'] ?> </div>
    <div class="phone_numbr"><?php echo $reservation_data['Reservation']['drop_off_location'] ?></div>
    <div class="addrs"><?php echo $reservation_data['Reservation']['service_start_date_time'] ?></div>
    <div class="action_bar"><a href="<?php echo SITE_URL . "riders/reservation_info_put_price/" . $reservation_data['Reservation']['id']; ?>">Show more info</a></a> </div>
    <div class="clear"></div>
</div>