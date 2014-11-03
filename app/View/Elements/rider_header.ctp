<div class="my-profile providers_profile">
    <?php /*?><div class="form-heading"><h2>My Profile</h2></div><?php */ ?>
    <?php echo $this->Session->flash('logmsg'); ?>
    <div class="user-img">
     <span>   
        <?php
        $rider_profile_image = $this->Custom->check_image($rider_info['User']['profile_pic'], 'profile_photo');
        echo $this->Image->resize('data/profile_photo/' . $rider_profile_image, 150, 150, true, false);
        ?>
        </span>
    </div>
    <div class="r-profile-c">
        <div class="user-name"><h2>Hi <?php echo  ucfirst(substr($rider_info['User']['first_name'],0,10)); ?></h2>

            <div class="user_w_icon">
                <?php
                $total_unread_message =  $this->Lab->getUnreadMessage();
                if($total_unread_message<=0){
                    $total_unread_message_icon = '<i class="fa fa-globe"></i>';
                }else{
                    $total_unread_message_icon =  "<b>".$total_unread_message."</b>";
                }
                ?>
                <ul>
                    <li><a href="<?php echo SITE_URL . "riders/my_account" ?>"><i class="fa fa-user"></i>My Account</a></li>
                    <li><a href="<?php echo SITE_URL . "riders/message_listing" ?>">   <?php   echo $total_unread_message_icon; ?>Notification</a></li>
                </ul>
            </div>
            <div class="edit-btn"><a href="<?php echo SITE_URL . "riders/info_edit" ?>">Edit</a></div>
        </div>
        <div class="user-info">
            <ul>
                <?php
                $address = $this->Custom->get_address_formate($rider_info);
                if (isset($address) && !empty($address)) {
                    echo '<li><i class="fa fa-globe"></i> ' . $address . '</li>';
                }
                if (isset($rider_info['User']['gender']) && !empty($rider_info['User']['gender'])) {
                    echo '<li><i class="fa fa-user"></i> ' . ucfirst($rider_info['User']['gender']) . '</li>';
                }
                if (isset($rider_info['User']['phone']) && !empty($rider_info['User']['phone'])) {
                    echo '<li><i class="fa fa-phone"></i> ' . ucfirst($rider_info['User']['phone']) . '</li>';
                }
                if (isset($rider_info['User']['email']) && !empty($rider_info['User']['email'])) {
                    echo '<li><i class="fa fa-envelope"></i> ' . ucfirst($rider_info['User']['email']) . '</li>';
                }
                ?>
            </ul>



            <?php
            if (isset($rider_info['User']['about']) && !empty($rider_info['User']['about'])) {
                echo '<p style="font-size: 16px; margin: 20px 0px 0px;word-wrap: break-word;">' . ucfirst(substr(htmlentities($rider_info['User']['about']), 0, 250)) . '</p>';
            }
            ?>


        </div>
    </div>
</div>