<?php
// check message read or unread if unread then add a class
$un_read_class = "read";
if ($message['MessageIndex']['is_read'] == 0) {
    $un_read_class = " unread unread_notification";
}
?>
<div class="inbox_list <?php echo $un_read_class; ?>" m_id="<?php echo base64_encode($message['MessageIndex']['id']); ?>">
    <div class="message_body">
        <h2><?php echo ucfirst($message['Message']['subject']); ?></h2>
        <span><i class="fa fa-clock-o"></i><?php echo date("h:i:A Y-m-d", strtotime($message['Message']['created'])); ?></span>
        <div class="clear n_brdr"></div>
        <p><?php echo ucfirst($message['Message']['message']); ?></p>
        <div class="clear"></div>
    </div>
    <div class="message_action"><a class="delete_message" m_id="<?php echo base64_encode($message['MessageIndex']['id']); ?>" href="javascript:void(0)">Delete Message</a></div>
    <div class="clear"></div>
</div>
