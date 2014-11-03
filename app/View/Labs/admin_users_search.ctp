<?php
if (!empty($users)) {
    foreach ($users as $user) {
        ?>
        <tr class="odd gradeX">
            <td><?php echo $user['User']['id']; ?></td>
            <td><?php echo $user['User']['username']; ?></td>
            <td class="hidden-480">
                <?php echo $this->Html->link($user['User']['email'], array('controller' => 'labs', 'action' => 'create_new_user/' . $user['User']['id'])); ?></td>
            <td class="hidden-480"><?php echo $user['User']['first_name']; ?></td>
            <td class="hidden-480"><?php echo $user['User']['last_name']; ?></td>
            <td class="hidden-480"><?php echo $user['User']['gender']; ?></td>
            <td class="hidden-480"><?php echo $user['User']['phone']; ?></td>

            <td class="hidden-480" id="<?php echo "st_" . $user['User']['id']; ?>">
                <?php
                if ($user['User']['status'] == 0) {
                    echo "Inactive";
                } elseif ($user['User']['status'] == 1) {
                    echo "Active ";
                } elseif ($user['User']['status'] == 2) {
                    echo "Unapproved";
                } elseif ($user['User']['status'] == 3) {
                    echo "Hidden";
                } elseif ($user['User']['status'] == 4) {
                    echo "Deactivate";
                } else {
                    echo "Not available";
                }
                ?>
            </td>
            <td class="hidden-480"><?php echo $this->Lab->ShowDate($user['User']['created']); ?></td>
            <td>
                <div class="btn-group">
                    <a data-toggle="dropdown" href="#" class="btn purple">
                        <i class="icon-user"></i> Settings
                        <i class="icon-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if ($user['User']['status'] == 2) { ?>
                            <li id="<?php echo "sp_" . $user['User']['id']; ?>"><a href="javascript:void(0);"
                                                                                   onclick="change_status(<?php echo $user['User']['id']; ?>, 1)"><i
                                        class="icon-remove"></i> <span>Active</span> </a>
                            </li>
                        <?php } elseif ($user['User']['status'] == 0) { ?>
                            <li id="<?php echo "sp_" . $user['User']['id']; ?>"><a href="javascript:void(0);"
                                                                                   onclick="change_status(<?php echo $user['User']['id']; ?>, 1)"><i
                                        class="icon-remove"></i> <span>Active</span> </a>
                            </li>
                        <?php } elseif ($user['User']['status'] == 1) { ?>
                            <li id="<?php echo "sp_" . $user['User']['id']; ?>"><a href="javascript:void(0);"
                                                                                   onclick="change_status(<?php echo $user['User']['id']; ?>, 0)">
                                    <iclass
                                    ="icon-remove"></i> <span>Deactivate</span></a></li>
                        <?php } ?>
                        <li class="divider"></li>
                        <li><a href="<?php echo SITEURL . "admin/labs/view_profile_user/" . $user['User']['id'] ?>"><i
                                    class="i"></i> Full Profile</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    <?php
    }
} else {
    ?>
    <tr>
        <td colspan="12" class="mid"> Record not found</td>
    </tr>
<?php } ?>
