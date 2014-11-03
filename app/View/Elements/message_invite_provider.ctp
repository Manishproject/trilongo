<?php
// check message read or unread if unread then add a class

$un_read_class = "read";
if ($message['MessageIndex']['is_read'] == 0) {
    $un_read_class = " unread unread_notification";
}
?>
<div class="inbox_list <?php echo $un_read_class; ?>"
     m_id="<?php echo base64_encode($message['MessageIndex']['id']); ?>">
    <div class="message_body">
        <h2><?php echo ucfirst($message['Message']['subject']); ?></h2>
        <span><i class="fa fa-clock-o"></i><?php echo date("h:i:A Y-m-d", strtotime($message['Message']['created'])); ?></span>

        <div class="clear n_brdr"></div>
        <p><?php echo ucfirst($message['Message']['message']); ?></p>

        <div class="type_btn">
            <?php
            if (isset($message['Reservation']['Proposal']) && !empty($message['Reservation']['Proposal'])) {
                if (array_filter($message['Reservation']['Proposal'])) {
                    if ($message['Reservation']['Proposal']['provider_status'] == 0) {
                        echo '<div class="type-1"><i class="fa fa-bullhorn"></i>Pending</div>';
                    } else if ($message['Reservation']['Proposal']['provider_status'] == 1 && $message['Reservation']['Proposal']['rider_status'] == 0) {
                        echo ' <div class="type-2" style="display:block;"><i class="fa fa-thumbs-up"></i>Wait for Feedback</div>';
                    } else if ($message['Reservation']['Proposal']['provider_status'] == 2) {
                        echo '  <div class="type-3" style="display:block;"><i class="fa fa-thumbs-down"></i>Declined</div>';
                    } else if ($message['Reservation']['Proposal']['provider_status'] == 1 && $message['Reservation']['Proposal']['rider_status'] == 1) {
                        echo '<div class="type-4" style="display:block;"><i class="fa fa-check"></i>Awarded</div>';
                    } else if ($message['Reservation']['Proposal']['provider_status'] == 1 && $message['Reservation']['Proposal']['rider_status'] == 2) {
                        echo '<div class="type-5" style="display:block;"><i class="fa fa-times"></i>Not Awarded</div>';
                    }
                } else {
                    if (!array_filter($message['Reservation']['Proposal'])) {
                        echo '<div class="type-1"><i class="fa fa-bullhorn"></i>Pending</div>';
                    }
                }
            } else {
                echo '<div class="type-1"><i class="fa fa-bullhorn"></i>Pending</div>';
            }
            ?>

        </div>
        <div class="clear"></div>
    </div>
    <div class="message_action">
        <a href="<?php echo SITE_URL . "providers/offer_detail/" . $message['Message']['reservation_id']; ?>">View More info</a></div>
    <div class="clear"></div>
</div>