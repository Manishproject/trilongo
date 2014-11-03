<div class="my-account">
    <?php echo $this->element('provider_header') ?>

    <div class="company-info">
        <div class="form-heading no-float"><h2>Driver Listing</h2>

            <div class="clear"></div>

        </div>
        <div class="driver-listing-c">
            <div class="driver_listing_btm_top ">
                <a href="<?php echo SITE_URL . "providers/add_driver"; ?>" class="reset_btn">Add Driver</a>

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
                        <div class="phone_numbr">Phone Number</div>
                        <div class="addrs">Address</div>
                        <div class="action_bar">Action</div>
                        <div class="clear"></div>
                    </div>

                    <?php
                    if (isset($all_driver_data) && !empty($all_driver_data)) {
                        foreach ($all_driver_data as $key => $driver_data) {
                            ?>

                            <div class="content_row ">
                                <div class="drv_name"><?php echo $driver_data['Driver']['name']; ?></div>
                                <div class="phone_numbr"><?php echo $driver_data['Driver']['mobile_no']; ?></div>
                                <div class="addrs"><?php echo $driver_data['Driver']['driver_address']; ?></div>
                                <div class="action_bar"><a>Edit</a> <a>Delete</a></div>
                                <div class="clear"></div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class="inbox_list">No Driver found</div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>


</div>

