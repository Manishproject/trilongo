<div class="my-account">
    <?php echo $this->element('provider_header') ?>

    <div class="company-info">
        <div class="form-heading no-float"><h2>Withdrawal Listing</h2>

            <div class="clear"></div>

        </div>
        <div class="driver-listing-c">
            <div class="driver_listing_btm_top ">

                <div class="fr">

                    <div class="search_listing">
                        <input type="text" value="" placeholder="Search"/>
                        <input type="submit" value=""/>
                    </div>

                    <button class="reset_btn">Reset</button>
                </div>
                <div class="clear"></div>
            </div>

            <div class="driver_listing_btm_btm">
                <div class="driver_listing_row">
                    <div class="hd_row ">
                        <div class="drv_name">Driver Name</div>
                        <div class="phone_numbr">Amount</div>
                        <div class="addrs">Date</div>
                        <div class="action_bar">Action</div>
                        <div class="clear"></div>
                    </div>

                    <?php
                    if (isset($all_withdrawal_data) && !empty($all_withdrawal_data)) {
                        foreach ($all_withdrawal_data as $key => $withdrawal_data) {
                            ?>

                            <div class="content_row ">
                                <div class="drv_name"><?php echo $withdrawal_data['Withdrawal']['name']; ?></div>
                                <div
                                    class="phone_numbr"><?php echo $withdrawal_data['Withdrawal']['mobile_no']; ?></div>
                                <div class="addrs"><?php echo $withdrawal_data['Withdrawal']['driver_address']; ?></div>
                                <div class="action_bar"><a>Edit</a> <a>Delete</a></div>
                                <div class="clear"></div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class="inbox_list">No Withdrawal Records found</div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>


</div>

