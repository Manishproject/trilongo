<?php if (!empty($all)) {
    foreach ($all as $list) { //ec($list);
        ?>
        <tr class="odd gradeX">
            <td><?php echo $list['Reservation']['id']; ?></td>
            <td><?php echo $list['Reservation']['pick_up_code']; ?></td>

            <td class="hidden-480"><?php echo $list['Retailer']['business_name']; ?></td>
            <td class="hidden-480"><?php echo $list['User']['first_name']; ?></td>
            <td class="hidden-480"><?php echo $list['Product']['name']; ?></td>
            <td class="hidden-480"><?php echo $list['Reservation']['qty']; ?></td>

            <td class="hidden-480"><?php
                if ($list['Reservation']['status'] == 1) {
                    echo __('Reserve');
                } elseif ($list['Reservation']['status'] == 2) {
                    echo __('Approved');
                } elseif ($list['Reservation']['status'] == 3) {
                    echo __('Declined by Retailers');
                } elseif ($list['Reservation']['status'] == 4) {
                    echo __('Cancelled By Consumer');
                } elseif ($list['Reservation']['status'] == 5) {
                    echo __('Declined By Admin');
                } else {
                    echo __('Pending');
                } ?></td>
            <td class="hidden-480"><?php echo $this->Lab->ShowDate($list['Reservation']['created']); ?></td>
            <td><?php
                if ($list['Reservation']['status'] == 0 || $list['Reservation']['status'] == 1 || $list['Reservation']['status'] == 2) {
                    echo $this->Html->link('Decline', array('controller' => 'labs', 'action' => 'reservtion_cancel/' . $list['Reservation']['id']), array('class' => '', 'escape' => false));
                }
                ?></td>
        </tr>
    <?php
    }
} else {
    ?>
    <tr>
        <td colspan="9" class="mid">record not found</td>
    </tr>
<?php } ?>