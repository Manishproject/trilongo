<div class="page-sidebar nav-collapse collapse">
    <ul>
        <li>
            <div class="sidebar-toggler hidden-phone"></div>
        </li>


        <li class="start admin_labs">
            <a href="<?php echo SITEURL; ?>admin/labs">
                <i class="icon-home"></i>
                <span class="title">Dashboard</span>

            </a>
        </li>

        <li class="has-sub ">
            <a href="javascript:;">
                <i class="icon-bookmark-empty"></i>
                <span class="title">Users</span>
                <span class="arrow "></span>
            </a>
            <ul class="sub">
                <li> <?php echo $this->Html->link('All', '/admin/labs/all_user', array('escape' => false)); ?></li>
            </ul>
        </li>


        <li class="has-sub ">
            <a href="javascript:;">
                <i class="icon-table"></i>
                <span class="title">Email Templates</span>
                <span class="arrow "></span>
            </a>
            <ul class="sub">
                <li> <?php echo $this->Html->link('New', '/admin/mails/mails/new'); ?></li>
                <li> <?php echo $this->Html->link('All', '/admin/mails/mails/index', array('escape' => false)); ?></li>

            </ul>
        </li>

        <li class="has-sub ">
            <a href="javascript:;">
                <i class="icon-th-list"></i>
                <span class="title">Page/Category</span>
                <span class="arrow "></span>
            </a>
            <ul class="sub">
                <li> <?php echo $this->Html->link('New Page', '/admin/pages/homes/new'); ?></li>
                <li> <?php echo $this->Html->link('All Page', '/admin/pages/homes/'); ?></li>
                <li> <?php echo $this->Html->link('Footer Page category', '/admin/labs/footer_cat'); ?></li>
                <li> <?php echo $this->Html->link('All Footer page List', '/admin/labs/list_footer_link'); ?></li>
            </ul>
        </li>

        <li class="has-sub ">
            <a href="javascript:;">
                <i class="icon-th-list"></i>
                <span class="title">Site Default Image</span>
                <span class="arrow "></span>
            </a>
            <ul class="sub">
                <li> <?php echo $this->Html->link('All', '/admin/labs/default_photo', array('escape' => false)); ?></li>
            </ul>
        </li>

<!--                <li class="has-sub ">-->
<!--                    <a href="javascript:;">-->
<!--                        <i class="icon-bar-chart"></i>-->
<!--                        <span class="title">Payments</span>-->
<!--                        <span class="arrow "></span>-->
<!--                    </a>-->
<!--                    <ul class="sub">-->
<!--                            <li> --><?php //echo $this->Html->link('All', '/admin/labs/payments'); ?><!--</li>-->
<!--                            <li> --><?php //echo $this->Html->link('Reservation Listing', '/admin/labs/payments'); ?><!--</li>-->
<!--                            <li> --><?php //echo $this->Html->link('Total Fees', '/admin/labs/payments'); ?><!--</li>-->
<!--                    </ul>-->
<!--            </li>-->

        <li class="has-sub ">
            <a href="javascript:;">
                <i class="icon-briefcase"></i>
                <span class="title">Extra</span>
                <span class="arrow "></span>
            </a>
            <ul class="sub">
                <li>
                <li> <?php echo $this->Html->link('Web Settings', '/admin/labs/setting'); ?></li>
        </li>
    </ul>
    </li>
    <?php /* ?>
  <li class="">
  <a href="login.html">
  <i class="icon-user"></i>
  <span class="title">Login Page</span>
  </a>
  </li>
  <?php */
    ?>
    </ul>
    <!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR -->
<script type="text/javascript">

    $(document).ready(function () {
        var current_URL = "<?php echo isset($this->params->url) ? $this->params->url : ''; ?>";

        $('.page-sidebar a').each(function () {
            var href = $(this).attr('href');
            var res = href.match(current_URL);
            if (res) {
                $(this).addClass('active');
                $(this).parents('li').addClass('active');
            }
            if (current_URL == 'admin/labs') {
                $('.page-sidebar a').removeClass('active');
                $('.page-sidebar li').removeClass('active');
                $('.page-sidebar li.admin_labs').addClass('active');

            }

        });

    });
</script>
