<div class="container-fluid">
    <?php echo $this->Session->flash(); ?>
    <div class="row-fluid">

        <?php echo $this->Form->create('Message', array('controller' => 'Messages', 'action' => 'new')); ?>

        <div class="block-change-pass">
            <label>To</label>
            <?php echo $this->Form->input('Message.to', array('type' => 'select', 'options' => $participents, 'multiple' => true, 'label' => false, 'class' => 'span12', 'id' => 'participents', 'data-placeholder' => "Participents", 'required' => true)); ?>

            <label>Subject</label>
            <?php echo $this->Form->input('Message.subject', array('type' => 'text', 'label' => false, 'class' => 'span12', 'id' => 'Message_subject', 'required' => true)); ?>


            <label>Message</label>
            <?php echo $this->Form->input('Message.body', array('type' => 'textarea', 'label' => false, 'class' => 'ckeditor', 'cols' => '80', 'id' => 'editor4', 'rows' => '8', 'tabindex' => '1', 'required' => true)); ?>
            <div class="clearfix"></div>

            <?php echo $this->Form->submit('Send', array('class' => 'btn btn-primary pull-right')) ?>
            <div class="clearfix"></div>

        </div>
        <?php echo $this->Form->end(); ?>
    </div>


</div>

<script type="text/javascript">

    $('#participents').chosen({no_results_text: "Oops, No participent found!"});

</script>