<div class="my-profile providers_profile">
    <?php /*?><div class="form-heading"><h2>My Profile</h2></div><?php */ ?>
    <?php echo $this->Session->flash('logmsg'); ?>
    <div class="user-img">
      <span>   
        <?php
            $provider_profile_image = $this->Custom->check_image($provider_info['User']['profile_pic'], 'profile_photo');
            echo $this->Image->resize('data/profile_photo/' . $provider_profile_image, 150, 150, true, false);
        ?>
    </span>
    </div>
    <div class="r-profile-c">
        <div class="user-name"><h2>Hi <?php echo ucfirst(substr($provider_info['User']['first_name'],0,10)); ?></h2>

            <div class="user_w_icon">
                <ul>
                    <?php
                    $total_unread_message =  $this->Lab->getUnreadMessage();
                    if($total_unread_message<=0){
                        $total_unread_message_icon = '<i class="fa fa-globe"></i>';
                    }else{
                        $total_unread_message_icon =  "<b>".$total_unread_message."</b>";
                    }
                    ?>
                    <li><a href="<?php echo SITE_URL . "providers/my_account" ?>"><i class="fa fa-user"></i> MyAccount</a></li>
                    <li><i class="fa fa-globe"></i> Total balance:<?php echo " $" . number_format($provider_info['User']['balance'], 2); ?></li>
                    <li><a href="<?php echo SITE_URL . "providers/message_listing" ?>">  <?php   echo $total_unread_message_icon; ?>Notification</a></li>
<!--                    <li><a href="--><?php //echo SITE_URL . "providers/driver_listing" ?><!--"><i class="fa fa-user"></i> Driver</a></li>-->
                </ul>
            </div>
            <div class="edit-btn"><a href="<?php echo SITE_URL . "providers/info_edit" ?>">Edit</a></div>
        </div>
        <div class="user-info">
            <ul>
                <?php $address = $this->Custom->get_address_formate($provider_info);
                if (isset($address) && !empty($address)) {
                    echo '<li><i class="fa fa-globe"></i> ' . $address . '</li>';
                }
                if (isset($provider_info['User']['gender']) && !empty($provider_info['User']['gender'])) {
                    echo '<li><i class="fa fa-user"></i> ' . ucfirst($provider_info['User']['gender']) . '</li>';
                }
                if (isset($provider_info['User']['phone']) && !empty($provider_info['User']['phone'])) {
                    echo '<li><i class="fa fa-phone"></i> ' . ucfirst($provider_info['User']['phone']) . '</li>';
                }
                if (isset($provider_info['User']['email']) && !empty($provider_info['User']['email'])) {
                    echo '<li><i class="fa fa-envelope"></i> ' . ucfirst($provider_info['User']['email']) . '</li>';
                }
                ?>
            </ul>
            <?php
            if (isset($provider_info['User']['about']) && !empty($provider_info['User']['about'])) {
                echo '<p style="font-size: 16px; margin: 20px 0px 0px;word-wrap: break-word;">' . ucfirst(substr(htmlentities($provider_info['User']['about']), 0, 250)) . '</p>';
            }
            ?>
        </div>
    </div>
</div>
