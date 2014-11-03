<?php $this->params['plugin'] = ''; ?>
<!-- start of secondary bar -->
<section id="secondary_bar">
    <div class="user">
        <p><?php echo isset($user_info) ? $user_info['User']['first_name'] . " " . $user_info['User']['last_name'] : ""; ?></p>
        <a class="logout_user" href="<?php echo SITE_URL . "users/adminlogout" ?>" title="Logout">LOGOUT</a>
    </div>
    <div class="breadcrumbs_container">
        <article class="breadcrumbs"><a href="<?php echo SITE_URL . "admin/" ?>">Website Admin</a>

            <div class="breadcrumb_divider"></div>
            <a class="current">Dashboard</a></article>
        <div style="float:right;padding:10px 15px 0 0;">
            Last Login On
            :- <?php echo isset($user_info['User']['lastlogin']) ? $user_info['User']['lastlogin'] : "No Record"; ?>
        </div>
    </div>
</section>
<!-- end of secondary bar -->

<!-- start of sidebar -->
<aside id="sidebar" class="column">
    <h3><?php echo $this->Html->link('Users', array('controller' => 'users', 'action' => 'index', 'admin' => true)); ?></h3>

    <h3><?php echo $this->Html->link('Categories', '/admin/taxonomy/vocabularies'); ?></h3>

    <h3><?php echo $this->Html->link('Contries', array('controller' => 'reservations', 'action' => 'countries', 'admin' => true)); ?></h3>

    <h3><?php echo $this->Html->link('Email Templates', array('controller' => 'email_template', 'action' => 'index', 'admin' => true, 'plugin' => 'email_template')); ?></h3>

    <h3><?php echo $this->Html->link('Payment Logs', array('controller' => 'payment_logs', 'action' => 'index', 'admin' => true)); ?></h3>
</aside>
<!-- end of sidebar -->

