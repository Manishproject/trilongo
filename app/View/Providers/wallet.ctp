<div class="my-account">
    <?php echo $this->element('provider_header') ?>

    <div class="company-info">
        <div class="form-heading no-float"><h2>Wallet</h2>

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
                        <div class="drv_name">Reservation Detail</div>
                        <div class="phone_numbr">Amount</div>
                        <div class="addrs">Description</div>
                        <div class="action_bar">Action</div>
                        <div class="clear"></div>
                    </div>

                    <?php
                    if (isset($all_transaction_log_data) && !empty($all_transaction_log_data)) {
                        foreach ($all_transaction_log_data as $key => $transaction_log_data) {
                            ?>

                            <div class="content_row ">
                                <div
                                    class="drv_name"><?php echo $transaction_log_data['TransactionLog']['reservation_id']; ?></div>
                                <div class="phone_numbr">
                                    $<?php echo $transaction_log_data['TransactionLog']['amount']; ?></div>
                                <div
                                    class="addrs"><?php echo $transaction_log_data['TransactionLog']['description']; ?></div>
                                <div class="action_bar"><a
                                        href="<?php echo SITE_URL . "providers/reservation_detail/" . $transaction_log_data['TransactionLog']['reservation_id'] ?>"
                                        class="GnResPopAjax">View More</a></div>
                                <div class="clear"></div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class="inbox_list">No Records found</div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>


</div>
<?php
echo $this->Html->css(array('magnific-popup'));
echo $this->Html->script(array('jquery.magnific-popup.min'));
?>
<script>
    // open popup
    $(document).ready(function () {
        $.ajaxSetup({cache: false});
        /* normal popup */
        /*pop up close only with close button*/
        $('.GnResPopAjax').magnificPopup({type: 'ajax', closeOnContentClick: false, closeOnBgClick: false, showCloseBtn: true, enableEscapeKey: true, closeMarkup: '<button class = "mfp-close mfp-new-close" type = "button" title = "Close (Esc)"> </button>'});

    });
</script>
