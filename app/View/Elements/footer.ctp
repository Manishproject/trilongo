<div class="main-div">
    <?php
    // get footer link and category name
    $all_category = $this->Lab->get_category_link();
    ?>
    <?php
    if (isset($all_category) && !empty($all_category)) {
        foreach ($all_category as $key => $footer_cat_data) {
            ?>
            <div class="f-panel">
                <i><img src="<?php echo SITE_URL ?>images/f-about-icon.png" alt=""/></i>
                <h4><?php echo ucfirst($footer_cat_data['FooterCategory']['category_name']) ?></h4>
                <?php
                if (isset($footer_cat_data['FooterLink']) && !empty($footer_cat_data['FooterLink'])) {
                    echo "<ul>";
                    foreach ($footer_cat_data['FooterLink'] as $footer_link_data) {
                        ?>
                        <li><a href="<?php echo SITEURL . "page/" . $footer_link_data['link_url'] ?>"><?php echo ucfirst($footer_link_data['link_name']); ?></a></li>
                    <?php
                    }
                    echo "</ul>";
                }
                ?>

            </div>
        <?php
        }
    }
    ?>

    <div class="sub-footer">

        <div class="s-f-l">
            <ul>
                <li><a href="<?php  echo site_fb_url; ?>" target="_blank" ><img src="<?php echo SITE_URL ?>images/f-icon.png" alt=""/> </a></li>
                <li><a href="<?php  echo site_tw_url; ?>" target="_blank"><img src="<?php echo SITE_URL ?>images/t-icon.png" alt=""/> </a></li>
                <li><a href="<?php  echo site_linkedin; ?>" target="_blank"><img src="<?php echo SITE_URL ?>images/l-icon.png" alt=""/> </a></li>
                <li><a href="<?php  echo site_youtube_url; ?>" target="_blank"><img src="<?php echo SITE_URL ?>images/y-icon.png" alt=""/> </a></li>
                <li><a href="<?php  echo site_instagram_url; ?>" target="_blank"><img src="<?php echo SITE_URL ?>images/inst-icon.png" alt=""/> </a></li>
                <li><a href="<?php  echo site_tumblr_url; ?>" target="_blank"><img src="<?php echo SITE_URL ?>images/tumbler-icon.png" alt=""/> </a></li>
            </ul>
            <div class="copyrights">
                <ul>
                    <li>Copyright 2011 trilongo.com
                    <li>/</li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li>/</li>
                    <li><a href="#">Sitemap</a></li>
                </ul>
            </div>

        </div>

        <div class="s-f-r">
            <ul>
                <li><a href="#"><img src="<?php echo SITE_URL ?>images/app-img.png" alt=""/> </a></li>
                <li><a href="#"><img src="<?php echo SITE_URL ?>images/anoroid-icon.png" alt=""/> </a></li>
                <li><a href="#"><img src="<?php echo SITE_URL ?>images/truste-img.png" alt=""/> </a></li>
            </ul>
            <div class="pay-icon">
                <ul>
                    <li><a href="#"><img src="<?php echo SITE_URL ?>images/paypal-icon.png" alt=""/> </a></li>
                    <li><a href="#"><img src="<?php echo SITE_URL ?>images/v-icon.png" alt=""/> </a></li>
                    <li><a href="#"><img src="<?php echo SITE_URL ?>images/v-e-icon.png" alt=""/> </a></li>
                    <li><a href="#"><img src="<?php echo SITE_URL ?>images/maestro-icon.png" alt=""/> </a></li>
                    <li><a href="#"><img src="<?php echo SITE_URL ?>images/m-c-ion.png" alt=""/> </a></li>
                </ul>
            </div>

        </div>


    </div>
</div>
<script type="text/javascript">
    jQuery("#click_menu").click(function () {
        jQuery("#open_menu").slideToggle()
    });

    // change select box then set current location and then load page
    $(function () {
        // stop keyboard
        $(".onlyNumber").live("keydown",function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });



        // only number and letter
        $('.OnlyNumberLetter').live("keypress",function (e) {
            var arr = [0,32];
            var allowedChars = new RegExp("^[a-zA-Z0-9\-\b]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (allowedChars.test(str) || !$.inArray( e.charCode , arr )) {
                return true;
            }
            e.preventDefault();
            return false;
        }).keyup(function() {
            // the addition, which whill check the value after a keyup (triggered by Ctrl+V)
            // We take the same regex as for allowedChars, but we add ^ after the first bracket : it means "all character BUT these"
            var forbiddenChars = new RegExp("[^a-zA-Z0-9\-]", 'g');
            if (forbiddenChars.test($(this).val())) {
                $(this).val($(this).val().replace(forbiddenChars, ''));
            }
        });



        $(".success, .error").live("click", function() {
            $(this).fadeOut();
        })
        $(".change_location").change(function () {
            var currently_in = $(this).val();
            var country_name = $(".change_location option:selected").text();
            window.location.href = "<?php echo SITE_URL."reservations/setcurrentlocation/";  ?>" + currently_in + "/" + country_name;
        })




        // feedback form send email to admin email
        // save form with ajax
        $('.feedback_submit').live('click', function()
        {
            $("#feedback_form").ajaxForm({
                beforeSubmit: function() {
                    $('#save_retailer_data').attr("disabled", true);
                    $('#save_retailer_data').val("Sending...");
                    $(".ServerError").html('');
                },
                success: function(response) {
                    $('#save_retailer_data').val("Send");
                    var data = $.parseJSON(response);
                    if(data['status']==1){
                        $(".ServerError").css({color:'green'});
                        $(".ServerError").html(data['message']);
                        setTimeout(function(){
                            $(".ServerError").html('');
                            $("#feedback_form")[0].reset();
                            setTimeout(function(){
                               $.fancybox.close();
                            },500);
                        },1000);
                    }else{
                        setTimeout(function(){
                            $(".ServerError").html('');
                        },900);
                       $(".ServerError").html(data['message']);
                    }
                    $('#save_retailer_data').attr("disabled", false);
                }
            }).submit();
        });

    })
</script>

<!--// check currently in set or not if set then set select box according to currently in -->
<?php
if (isset($country_name) && !empty($country_name)) {
    ?>
    <script>
        $(function () {
            var text1 = '<?php  echo $country_name; ?>';
            $(".change_location option").filter(function () {
                return this.text == text1;
            }).attr('selected', true);
        });
    </script>
<?php
}
?>
