<div class="my-account">
    <?php echo $this->element('provider_header') ?>
    <div class="company-info">
        <div class="form-heading no-float">
            <h2>Message Listing</h2>

            <div class="clear"></div>
        </div>
        <div class="driver-listing-c">
            <div class="driver_listing_btm_top ">
                <div class="fl">
<!--                    <div class="listing_filter">-->
<!--                        <label>Filter</label>-->
<!--                        <select>-->
<!--                            <option>Select</option>-->
<!--                        </select>-->
<!--                    </div>-->
                </div>
                <div class="fr">
<!--                    <div class="search_listing">-->
<!--                        <input type="text" value="" placeholder="Search"/>-->
<!--                        <input type="submit" value=""/>-->
<!--                    </div>-->
<!--                    <button class="reset_btn">Reset</button>-->
                </div>
                <div class="clear"></div>
            </div>
            <div class="message_inbox">
                <!--                // check message exist or not -->
                <?php
                if (isset($message_all_data) && !empty($message_all_data)) {
                    foreach ($message_all_data as $key => $message_data) {
                     //   echo $message_data['MessageIndex']['id'];
                        if ($message_data['MessageIndex']['message_type'] == 1) {
                            // when offer received to provider
                            echo $this->element('message_invite_provider', array('message' => $message_data));
                        } else if ($message_data['MessageIndex']['message_type'] == 2) {
                            // when a notification received to  login user
                            echo $this->element('message_notification_user', array('message' => $message_data));
                        } else if ($message_data['MessageIndex']['message_type'] == 3) {

                            // when a notification received to  login user
                            echo $this->element('message_reservation_received', array('message' => $message_data));
                        } elseif ($message_data['MessageIndex']['message_type'] == 5) {
                            // when provider received payment via reservation
                            echo $this->element('message_provider_payment_received', array('message' => $message_data));
                        } elseif ($message_data['MessageIndex']['message_type'] == 6) {
                            // when reservation  is done then also provider received  escrow payment
                            echo $this->element('message_provider_got_escrow_payment', array('message' => $message_data));
                        }

                        ?>

                    <?php
                    }
                } else {
                    ?>
                    <div class="inbox_list no_message_found">No message found</div>
                <?php
                }
                ?>

            </div>
            <?php
            if ($this->Paginator->params()['nextPage'] || $this->Paginator->params()['prevPage']) {
                ?>
                <div class="paging">
                    <ul>
                        <?php
                        if (!empty($message_all_data)) {
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
<script>
    (function ($) {
        JqueryReadMessage = {
            message_read_update_database: function (message_id, element) {
                $.post("<?php  echo SITE_URL."homes/read_message";  ?>", {message_id: message_id}, function (data) {
                    var data = $.parseJSON(data);
                    if (data['status'] == 1) {
                        JqueryReadMessage.message_read_update_current_div(element);
                        JqueryReadMessage.message_read_update_notification_div();
                    } else {
                        data['message'];
                    }
                })
            },
            message_read_update_current_div: function (element) {
                $(element).parent().removeClass("unread unread_notification").addClass("read");
            },
            message_read_update_notification_div: function () {
                // get current notification and subtract one notification and check remaining notification
                // if remaining notification is zero then remove red icon and set globe html
                var counting_notification = $(".user_w_icon ul li").eq(2).find("b").text();
                var remaining_notification = (parseInt(counting_notification) - parseInt(1));
                if (remaining_notification == 0) {
                    remaining_notification = '<a href="<?php echo SITE_URL . "riders/message_listing" ?>"><i class="fa fa-globe"></i>Notification</a>';
                    $(".user_w_icon ul li").eq(2).find("b").remove();
                    $(".user_w_icon ul li").eq(2).html(remaining_notification)
                } else {
                    $(".user_w_icon ul li").eq(2).find("b").text(remaining_notification)
                }


            },
            message_delete: function (message_id, element) {
                $.post("<?php  echo SITE_URL."homes/delete_message";  ?>", {message_id: message_id}, function (data) {
                    var data = $.parseJSON(data);
                    if (data['status'] == 1) {
                        // remove current row
                        $(element).parents(".inbox_list").remove();
                        var current_message_length = $(".message_inbox").find(".inbox_list").length;
                        if(current_message_length == 0){
                            var no_message_found = '<div class="inbox_list no_message_found">No message found</div>';
                            $(".message_inbox").html(no_message_found);
                        }
                        JqueryReadMessage.message_read_update_notification_div();
                    } else {

                    }
                })
            }
        }
    })(jQuery);
    $(function () {
        // message read or unread
        $(".message_body").click(function () {
            var message_id = $(this).parent().attr("m_id");
            var element = $(this);
            if( $(this).parent().hasClass("unread")){
            JqueryReadMessage.message_read_update_database(message_id, element);
            }
        });

        // message delete
        $(".delete_message").click(function () {
            if (confirm("Are you sure delete this message")) {
                var message_id = $(this).attr("m_id");
                var element = $(this);
                JqueryReadMessage.message_delete(message_id, element);
            }
        });


    })
</script>
