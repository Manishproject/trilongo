<?php
//pr($provider_info);


?>
<div class="my-account">
    <?php echo $this->element('provider_header'); ?>
    <div class="company-info ">
        <div class="form-heading"><h2>Services Information</h2>
        </div>
        <div class="company-info-c">
            <div class="c-i-l-link center">
                <a href="<?php echo SITE_URL . "providers/service_info_driver"; ?>">Hire A Driver</a>
                <a class="mid_margin" href="<?php echo SITE_URL . "providers/service_info_taxi"; ?>">Book A Taxi</a>
                <a href="<?php echo SITE_URL . "providers/service_info_vehicle"; ?>">Rent Lease A Vehicle</a>
            </div>
        </div>
    </div>
    <div class="company-info new_pad">
        <div class="form-heading"><h2>Company Information</h2>
            <?php
            if (array_filter($provider_info['Company'])) {
                echo '<div class="edit-btn"><a href="' . SITE_URL . "providers/company_info_edit" . '">Edit</a></div>';
            }
            ?>
        </div>
        <div class="company-info-c">
            <?php
            if (!array_filter($provider_info['Company'])) {
                echo ' <div class="c-i-l-link"> <a href="' . SITE_URL . "providers/company_info_edit" . '">Add your Company info</a></div>';
            } else {
                ?>
                <div class="">
                    <ul>
                        <li>
                            <div class="c-i-l"> Your Company's Full Name</div>
                            <div class="c-i-d">:</div>
                            <div class="c-i-r"> Reference site about Lorem Ipsum, giving information on its origins,
                            </div>
                        </li>
                        <div class="clear"></div>
                        <li>
                            <div class="c-i-l">Point of Contact's Name For Transportation Services At Your Company</div>
                            <div class="c-i-d">:</div>
                            <div class="c-i-r"> Reference site about Lorem Ipsum, giving information on its origins,
                            </div>
                        </li>
                        <div class="clear"></div>
                        <li>
                            <div class="c-i-l"> Your Company's Full Name</div>
                            <div class="c-i-d">:</div>
                            <div class="c-i-r"> Loreum Ipsum</div>
                        </li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>


</div>

