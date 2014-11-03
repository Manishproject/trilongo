<div class="spc_pgs">
    <div class="thanku">
        <?php echo $this->Session->flash('logmsg'); ?>
        <p><?php echo ucfirst($thank_you_page_data['Page']['post_data']);  ?></p>
        <a href="<?php echo SITE_URL . "riders/my_account"; ?>">My account</a>
    </div>
</div>