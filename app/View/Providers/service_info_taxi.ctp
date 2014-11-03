<section class="pg_mid">
    <div class="form_pages">
        <div class="company-edit">
            <div class="form-heading"><h2>Service Info Driver</h2></div>
            <div class="clear"></div>
            <?php
            echo $this->Form->create('ServiceInformation');
            echo $this->Session->flash('logmsg');
            $ServiceList = $this->Lab->get_service_taxi();
            if (isset($ServiceList['service_area']) && !empty($ServiceList['service_area'])) {
                $i=0;
                foreach ($ServiceList['service_area'] as $service_area_key => $service_area_value) {
                    ?>
                    <div class="service_info_box">
                        <strong><?php echo ucfirst($service_area_value['ServiceArea']['name']) ?></strong>

                        <div class="service_info_box_btm">
                            <?php
                            if (isset($ServiceList['service_type']) && !empty($ServiceList['service_type'])) {
                                foreach ($ServiceList['service_type'] as $service_type_key => $service_type_value) {
                                    ?>
                                    <div class="ser_res">
                                        <label><?php echo ucfirst($service_type_value['ServiceType']['type']); ?> </label>
                                        <?php
                                        if(isset($this->request->data[$i]['ServiceInformation']['id']) && !empty($this->request->data[$i]['ServiceInformation']['id'])){
                                             echo $this->Form->hidden($i.".ServiceInformation.id",array('label'=>false,'value'=>$this->request->data[$i]['ServiceInformation']['id']));
                                        }
                                         echo $this->Form->input($i.".ServiceInformation.rate",array('label'=>false,'type'=>'text','class' => 'onlyNumber validate[required]'));
                                         echo $this->Form->hidden($i.".ServiceInformation.service_id",array('label'=>false,'value'=>2));
                                         echo $this->Form->hidden($i.".ServiceInformation.user_id",array('label'=>false,'value'=>ME));
                                         echo $this->Form->hidden($i.".ServiceInformation.provider_id",array('label'=>false,'value'=>PROVIDERID));
                                         echo $this->Form->hidden($i.".ServiceInformation.service_area_id",array('label'=>false,'value'=>$service_area_value['ServiceArea']['id']));
                                         echo $this->Form->hidden($i.".ServiceInformation.service_type_id",array('label'=>false,'value'=>$service_type_value['ServiceType']['id'])); ?>
                                    </div>
                                <?php
                                    $i++;
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
            }
            ?>
            <div class="clearfix">
                <div class="btn-submit">
                    <?php echo $this->Form->submit('Update', array('class' => 'submit-button h_f_b')); ?>
                </div>
            </div>
            <?php
            echo $this->Form->end();
            ?>
            <div class="clear"></div>
        </div>
    </div>
    </div>
</section>


<?php
echo $this->Html->css(array('validationEngine.jquery'));
echo $this->Html->script(array('jquery.validationEngine', 'jquery.validationEngine-en'));

?>
<script>
    $(function(){
        $("#ServiceInformationServiceInfoTaxiForm").validationEngine({scroll: false});
    })
</script>