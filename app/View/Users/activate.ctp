<?php echo $this->Form->create('User'); ?>
<?php echo $this->Form->input('email'); ?>
<?php echo $this->Form->input('activation_key', array('required' => true)); ?>
<?php echo $this->Form->submit('Activate'); ?>
<?php echo $this->Form->end(); ?>