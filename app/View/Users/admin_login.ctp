<div class="panel">

    <h1><?php echo SITE_TITLE; ?></h1>

    <p class="descript">Administration Panel</p>
    <?php echo '<div style="color:red;">' . $this->Session->flash('error') . '</div>'; ?>
    <?php echo $this->Form->create('User', array('action' => 'login', 'admin' => true)); ?>
    <?php echo $this->Form->error('User.email'); ?>
    <?php echo $this->Form->input('User.email', array('type' => 'text', 'label' => false, 'required' => false, 'placeholder' => 'Email')); ?>
    <?php echo $this->Form->error('User.password'); ?>
    <?php echo $this->Form->input('User.password', array('label' => false, 'type' => 'password', 'placeholder' => 'Password')); ?>
    <?php echo $this->Form->submit('Login'); ?>
    <p class="credit"> &copy; <?php echo SITE_TITLE; ?> Admin. All rights reserved. </p>
    <?php echo $this->Form->end(); ?>
</div>