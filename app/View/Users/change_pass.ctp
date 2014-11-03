<?php echo $this->Form->create('User'); ?>

<?php if (!$force_change_pass) echo $this->Form->input('current_pass', array('label' => 'Current password', 'type' => 'password', 'autocomplete' => FALSE)); ?>
<?php echo $this->Form->input('password', array('type' => 'password', 'autocomplete' => FALSE)); ?>
<?php echo $this->Form->input('confirm_password', array('type' => 'password', 'autocomplete' => FALSE)); ?>
<?php echo $this->Form->submit('Change Password'); ?>
<?php echo $this->Form->end(); ?>