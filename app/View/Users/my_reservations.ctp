<table class="table table-striped table-bordered table-hover" id="sample_1">
    <thead>
    <tr>
        <?php /*?><th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th> <?php */ ?>


        <th class="hidden-480"><?php echo $this->Paginator->sort('id'); ?></th>
        <th class="hidden-480"><?php echo $this->Paginator->sort('User.first_name', 'Payee Name'); ?></th>
        <th class="hidden-480"><?php echo $this->Paginator->sort('amount'); ?></th>
        <th class="hidden-480"><?php echo $this->Paginator->sort('transaction_id'); ?></th>
        <th><?php echo $this->Paginator->sort('status'); ?></th>
        <th class="hidden-480"><?php echo $this->Paginator->sort('created'); ?></th>

    </tr>
    </thead>
    <tbody id="AllMails">

    <?php if (!empty($all)) {
        foreach ($all as $Reservation) {
            ?>
            <tr class="odd gradeX">


                <td><?php echo $this->Html->link($Reservation['Reservation']['id'], '/reservations/view/' . $Reservation['Reservation']['id']); ?></td>

                <td><?php echo $Reservation['User']['first_name'] . ' ' . $Reservation['Reservation']['pickup_location']; ?></td>
                <td class="hidden-480"><?php echo $Reservation['Reservation']['amount'] . ' ' . $Reservation['Reservation']['currency']; ?></td>
                <td class="hidden-480"><?php echo $Reservation['Reservation']['transaction_id']; ?></td>

                <td class="hidden-480"><?php echo $Reservation['Reservation']['status'] == 's' ? 'Success' : 'failed'; ?></td>


                <td class="hidden-480"><?php echo date('M-d-Y', strtotime($Reservation['Reservation']['created'])); ?></td>

            </tr>
        <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="7" class="mid"> record not found</td>
        </tr>
    <?php } ?>


    </tbody>
</table>