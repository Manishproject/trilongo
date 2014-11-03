<div class="warning_message">
    <div class="warning_inner">
        <img src="<?php echo SITE_URL . "images/loading.gif"; ?>"/>
        <span class="waiting_message"> Please wait</span>
        <span class="waiting_note"><b>Note</b>:Do not close your browser during this time.Also do not refresh the page or press the escape</span>
    </div>
</div>


<div class="payment_pg">
    <!--    <p>Processing Fee will be added to the final price 4222222222222.</p>-->

    <div class="form-heading"><h2>Payment Details</h2></div>

    <?php
    echo $this->Session->flash("logmsg");
    echo $this->Session->flash("front_error");
    echo $this->Form->create('PaymentLog', array('novalidate'));
    echo "<div id='cards'>";
    echo "<label> Payment Type</label>";
    echo $this->Html->image("payment/visa.jpg", array("id" => "Visa", 'class' => 'card_type_image'));
    echo $this->Html->image("payment/master-card.jpg", array("id" => "MasterCard", 'class' => 'card_type_image'));
    echo $this->Html->image("payment/discover.jpg", array("id" => "Discover", 'class' => 'card_type_image'));
    echo $this->Html->image("payment/american-express.jpg", array("id" => "Amex", 'class' => 'card_type_image'));
    echo $this->Form->error('card_type');
    echo "</div>";

    echo "<div class='charge_detail'>";
    echo "<strong>Payable service Charge </strong>$" . number_format($provider_show_payment, 2) . "<br>";
    echo "<strong>Trilongo Fee </strong>$" . number_format($service_charge, 2) . "<br>";
    echo "<strong>Total amount </strong>$" . number_format($payable_amount, 2);
    echo "</div>";
    ?>
    <div class="clear"></div>
    <div class="input_field">
        <div><label class="label">Card Number</label></div>
        <?php
        echo $this->Form->input('card_type', array("class" => "card_type", "type" => "hidden"));
        echo $this->Form->input('card_no', array('maxlength' => 16, 'placeholder' => 'Card Number', "class" => "onlyNumber card_number validate[required,custom[onlyNumberSp],maxSize[16]]", 'label' => false,));
        ?>
    </div>

    <div class="input_field">


        <div class="input_field_in">
            <div><label class="label">Card Month</label></div>
            <?php
            $month_arr = $this->Custom->make_combo(1, 12);
            echo $this->Form->input('card_month', array('options' => $month_arr, 'label' => false, 'class' => 'validate[required]'));
            ?>
        </div>
        <div class="input_field_in">/</div>
        <div class="input_field_in">
            <div><label class="label">Card Year</label></div>
            <?php
            $current_year = date('Y');
            $year_arr = $this->Custom->make_combo($current_year, $current_year + 50);
            echo $this->Form->input('card_year', array('options' => array($year_arr), 'label' => false, 'class' => 'validate[required]'));
            ?>

        </div>


        <div class="input_field_in">
            <div><label class="label">Card Cvv</label></div>
            <?php echo $this->Form->input('card_cvv', array('maxlength' => 3, 'placeholder' => 'Card Cvv', "type" => "password", 'label' => false, 'class' => 'onlyNumber validate[required,custom[onlyNumberSp],maxSize[3]]')); ?>
        </div>

        <div class="clear"></div>
    </div>
    <div class="input_field">
        <div><label class="label">First Name</label></div>
        <?php echo $this->Form->input('user_fname', array('maxlength' => 15, 'placeholder' => 'First Name', "type" => "text", 'label' => false, 'class' => ' OnlyNumberLetter validate[required,custom[onlyLetterNumber]]')); ?>
    </div>
    <div class="input_field">
        <div><label class="label">Last Name</label></div>
        <?php echo $this->Form->input('user_lname', array('maxlength' => 15, 'placeholder' => 'Last Name', "type" => "text", 'label' => false, 'class' => 'OnlyNumberLetter validate[required,custom[onlyLetterNumber]]')); ?>
    </div>
    <div class="clear"></div>
    <?php
    echo '<div class="btn-submit">' . $this->Form->submit('Pay', array('id' => 'payment_submit')) . '</div>';
    $this->Form->end();
    ?>
</div>
<?php
echo $this->Html->css(array('validationEngine.jquery'));
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));
?>
<script type="text/javascript" language="javascript">
    $(document).ready(function () {



        // js for expire data and month

//        calculate current month or year
        var currentMonth = (new Date).getMonth() + 1;
        var currentYear = (new Date).getFullYear();
        // window load time remove old option
        $("#PaymentLogCardMonth > option").each(function () {
            var current_val = $(this).val();
            if (current_val < currentMonth) {
                $("#PaymentLogCardMonth option[value=" + current_val + "]").remove();
            }
        });
        // year change time year and compare and set month option
        $("#PaymentLogCardYear").change(function () {
            var current_val = $(this).val();
            $('#PaymentLogCardMonth').find('option').remove();

            for (var i = 1; i <= 12; i++) {
                if (current_val > currentYear) {
                    $("#PaymentLogCardMonth").append(new Option(i, i));
                } else if ($.trim(current_val) == $.trim(currentYear)) {
                    if (i >= currentMonth) {
                        $("#PaymentLogCardMonth").append(new Option(i, i));
                    }
                }
            }
        })


        jQuery("#PaymentLogPaymentForm").validationEngine('attach', {
            scroll: false,
            onValidationComplete: function (form, status) {
                if (status) {
                    $(".warning_message").show();
                    $("#PaymentLogPaymentForm").submit();
                }
            }
        });


        var card_num = $(".card_number");
        card_num.keyup(function () {
            var cc_num = $(this).val();
            select_cc(creditCardTypeFromNumber(cc_num));
        });
        card_num.bind("paste", function () {
            var cc_num = $(this).val();
            select_cc(creditCardTypeFromNumber(cc_num));
        });
        card_num.bind('input propertychange', function () {
            var cc_num = $(this).val();
            select_cc(creditCardTypeFromNumber(cc_num));
        });
        //validating credit card
        if (card_num.val() != "") {
            var cc_num = $(".card_number").val();
            select_cc(creditCardTypeFromNumber(cc_num));
        }
        //Toggle coupon code container//
        if ($(".coupon_code_chk").attr("checked")) {
            $("#coupon_code_container").fadeIn();
        }
        $(".coupon_code_chk").click(function () {
            if ($(this).attr("checked")) {
                $("#coupon_code_container").fadeIn();
            }
            else {
                $("#coupon_code_container").fadeOut();
            }
        });


    });

    function select_cc(card_type) {
        if (card_type != "UNKNOWN") {
            var crds = $("#cards");
            crds.find("img").css("border", "none");
            crds.find(".card_type_image").hide();
            crds.find("img#" + card_type).show();
            $(".card_type").val(card_type);
        }
        else {
            $(".card_type_image").show();
            $(".cards").find("img").css("border", "none")
            $(".card_type").val("");
        }
    }
    //function to validate credit cards
    function creditCardTypeFromNumber(num) {
        // first, sanitize the number by removing all non-digit characters.
        num = num.replace(/[^\d]/g, '');
        // now test the number against some regexes to figure out the card type.
        if (num.match(/^5[1-5]\d{14}$/)) {
            return 'MasterCard';
        } else if (num.match(/^4\d{15}/) || num.match(/^4\d{12}/)) {
            return 'Visa';
        } else if (num.match(/^3[47]\d{13}/)) {
            return 'Amex';
        } else if (num.match(/^6011\d{12}/)) {
            return 'Discover';
        }
        return 'UNKNOWN';
    }


    function goodbye(e) {
        if (!e) e = window.event;
        //e.cancelBubble is supported by IE - this will kill the bubbling process.
        e.cancelBubble = true;
        e.returnValue = 'You sure you want to leave?'; //This is displayed on the dialog

        //e.stopPropagation works in Firefox.
        if (e.stopPropagation) {
            e.stopPropagation();
            e.preventDefault();
        }
    }


</script>