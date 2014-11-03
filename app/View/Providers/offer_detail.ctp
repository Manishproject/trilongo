<div class="my-account">
    <?php echo $this->element('provider_header') ?>
    <div class="company-info">
        <div class="form-heading">
            <h2>Rider Offer Information</h2>
        </div>
        <div class="clear"></div>
        <?php
        if (isset($message_section) && !empty($message_section)) {
        ?>
        <div class="offer_info">
            <div class="offer_info_box">
                <div class="about_message">
                    <h2><strong>1</strong><span>Offer</span>
                        <?php
                        if (isset($message_section['Message']['message']) && !empty($message_section['Message']['message'])) {
                            echo $message_section['Message']['message'];
                        } else {
                            echo "No message available";
                        }
                        ?>
                        <b><i class="fa fa-clock-o"></i> <?php echo date("Y-m-d h:i A", strtotime($message_section['Reservation']['created'])); ?></b>
                    </h2>
                    <div class="show_detail">
                        <div class="show_blk"> <strong>Price</strong> <?php echo "$" . $message_section['Reservation']['your_price']." ". $message_section['Reservation']['your_price_type']; ?></div>
                        <div class="show_blk"> <strong>Total Price</strong> <?php echo "$" . $message_section['Reservation']['your_total_price']; ?> </div>
                        <div class="show_blk"><strong>Service Start Date & Time</strong> <?php echo date("Y-m-d h:i A", strtotime($message_section['Reservation']['service_start_date_time'])); ?> </div>
                        <div class="show_blk"><strong>Service End Date & Time</strong> <?php echo date("Y-m-d h:i A", strtotime($message_section['Reservation']['service_end_date_time'])); ?></div>
                        <div class="show_blk"><strong>Pickup Location</strong><?php echo $message_section['Reservation']['pickup_location']; ?></div>
                        <div class="show_blk"><strong>Drop off  Location</strong> <?php echo $message_section['Reservation']['drop_off_location']; ?> </div>
                        <div class="clear"></div>
                        <?php
                        if (!array_filter($message_section['Reservation']['Proposal'])) {
                            // check proposal is  awarded or not
                            // if awarded then not show any button
                            if (!is_numeric($message_section['Reservation']['provider_id'])) {
                                ?>
                                <div class="offer_info_btn">
                                    <?php
                                    echo $this->Html->link('Accept', 'javascript:void(0)', array('class' => 'grn accept_reservation', 'escape' => false));
                                    echo $this->Html->link('Decline', array('controller' => 'providers', 'action' => 'cancel_reservation/' . $message_section['Reservation']['id']), array('class' => 'red', 'escape' => false, 'onclick' => "return confirm('Are you sure cancel this invitation?')"));
                                    ?>
                                </div>
                                <div class="show_form offer_info_btn" style="display:none;">
                                    <?php
                                    $max_price =   number_format($message_section['Reservation']['your_price'],0,'.', '');
                                    echo $this->Form->create('OfferAccept', array('url' => array('controller' => 'providers', 'action' => 'confirmation_proposal', $message_section['Reservation']['id'])));
                                    echo ' <div class="form_input"> <label>Your Price</label>';
                                    echo $this->Form->input('amount', array('class'=>'validate[required],min[1],max['.$max_price.']','div' => array('class' => ' form_input_type'), 'label' => false));
                                    echo '</div>';
                                    echo ' <div class="form_input "> <label></label> <div class="form_input_btn">';
                                    echo $this->Form->submit('Done', array('div' => false));
                                    echo $this->Html->link('Decline', array('controller' => 'providers', 'action' => 'cancel_reservation/' . $message_section['Reservation']['id']), array('class' => 'red', 'escape' => false, 'onclick' => "return confirm('Are you sure cancel this invitation?')"));;
                                    echo '</div>';
                                    echo $this->Form->end();
                                    ?>
                                </div>

                            <?php
                            } else {
                                echo "<div class='job_awarded'>Sorry Job is awarded</div>";
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="clear"></div>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php
    if (array_filter($message_section['Reservation']['Proposal'])) {
    ?>
    <div class="company-info">
        <div class="form-heading">
            <h2>Your Proposal Information</h2>
        </div>
        <div class="clear"></div>
        <div class="offer_info">
            <div class="offer_info_box">
                <div class="about_message">
                    <?php

                   if($message_section['Reservation']['Proposal']['provider_status'] ==1){

                    ?>
                    <div class="show_detail">
                        <div class="show_blk"> <strong>Rate</strong> <?php echo "$" . $message_section['Reservation']['Proposal']['amount']." ". $message_section['Reservation']['Proposal']['price_type']; ?></div>
                        <div class="show_blk"> <strong>Total Price</strong> <?php echo "$" . $message_section['Reservation']['Proposal']['total_price']; ?> </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <?php  }else if($message_section['Reservation']['Proposal']['provider_status'] ==2){ ?>
                    <div>You don't accept this offer.</div>
                <?php  } ?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <?php  } ?>
</div>
<?php
echo $this->Html->css(array('validationEngine.jquery'));
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));
?>
<script>
    $(function () {
        $("#OfferAcceptOfferDetailForm").validationEngine({scroll: false});
        $(".accept_reservation").click(function () {
            $(".offer_info_btn").slideUp();
            $(".show_form").slideDown();
        })
    })
</script>