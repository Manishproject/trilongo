<div class="my-account">
    <?php echo $this->element('rider_header') ?>
    <div class="company-info">
        <div class="form-heading no-float"><h2>Reservation Listing</h2>

            <div class="clear"></div>
        </div>
        <div class="driver-listing-c">
            <div class="driver_listing_btm_top ">

                <div class="different_reservation">
                    <div class="rider_put_price"><strong></strong>Put price</div>
                    <div class="rider_choose_provider"><strong></strong>Choose provider</div>
                </div>

<!--                <div class="fr">-->
<!--                    <div class="search_listing">-->
<!--                        <input type="text" value="" placeholder="Search"/>-->
<!--                        <input type="submit" value=""/>-->
<!--                    </div>-->
<!--                    <button class="reset_btn">Reset</button>-->
<!--                </div>-->
                <div class="clear"></div>
            </div>

            <div class="driver_listing_btm_btm new_srvs_list">
                <div class="driver_listing_row message_inbox">
                    <div class="hd_row ">
                        <div class="phone_numbr">Service Type</div>
                        <div class="drv_name">Pickup Location</div>
                        <div class="phone_numbr">Drop Off Location</div>
                        <div class="addrs">Service start date time</div>
                        <div class="action_bar">Action</div>
                        <div class="clear"></div>
                    </div>
                    <?php
                    if (isset($all_reservation_data) && !empty($all_reservation_data)) {

                        foreach ($all_reservation_data as $key => $reservation_data) {

                            if ($reservation_data['Reservation']['your_price'] != 0) {
                                // when user create a reservation with put price
                                echo $this->element('rider_reservation_put_price', array('reservation_data' => $reservation_data));
                            } else if ($reservation_data['Reservation']['provider_id'] != 0) {
                                // when user create a reservation and choose provider
                                echo $this->element('rider_reservation_choose_provider', array('reservation_data' => $reservation_data));
                            }
                        }
                    } else {
                        ?>
                        <div class="inbox_list no_message_found">No Reservation found</div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <?php
            if ($this->Paginator->params()['nextPage'] || $this->Paginator->params()['prevPage']) {
                ?>
                <div class="paging">
                    <ul>
                        <?php
                        if (!empty($all_reservation_data)) {
                            echo $this->Paginator->numbers(array('first' => 5, 'last' => 5, 'separator' => false, 'class' => 'pagginate', 'tag' => 'li'));
                        } ?>
                    </ul>
                </div>
            <?php
            }
            ?>
        </div>

    </div>
</div>
