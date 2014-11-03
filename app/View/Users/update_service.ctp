<?php

?>
    <script type="text/javascript">

        (function ($) {

            $(document).ready(function () {
                $('.user-services .services_type').change(function () {
                    var pObj = $(this).parents('.user-services');

                    if ($(this).attr('checked')) {
                        pObj.find('.services').show().find('input').attr('required', 'required');
                    } else {
                        pObj.find('.services').hide().find('input').val('').removeAttr('required');
                    }
                }).change();

            });

        })(jQuery)

    </script>

<?php echo $this->Form->Create('User'); ?>
    <h2>Services</h2>
<?php $i = 0;
foreach ($type_of_service as $k => $v) {
    ?>
    <div class="user-services">
        <?php
        $cost = '';
        if (isset($UserServices[$k])) {
            echo $this->Form->checkbox('ServiceInfo.' . $k, array('class' => 'services_type', 'checked' => 'checked')) . $v;
            $cost = $UserServices[$k];
        } else {
            echo $this->Form->checkbox('ServiceInfo.' . $k, array('class' => 'services_type')) . $v;
        }
        $cost = isset($this->request->data['ServiceInfo'][$k]['cost']) ? $this->request->data['Provider'][$k]['cost'] : $cost;


        ?>
        <div class="services">
            <?php echo 'USD' . $this->Form->input("ServiceInfo.$k.cost", array('div' => false, 'style' => 'width:100px;', 'value' => $cost, 'type' => 'text', 'label' => false)) . '/Day'; ?>
        </div>
    </div>

    <?php $i++;
} ?>
<?php echo $this->Form->submit('Update Services'); ?>
<?php echo $this->Form->end(); ?>