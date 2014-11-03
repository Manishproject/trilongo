<div class="container-fluid">
    <?php echo $this->Session->flash(); ?>
    <div class="row-fluid">

        <div class="messageList">
            <h3><?php echo $thread_messages[0]['Message']['subject'] ?></h3>
            <?php foreach ($thread_messages as $m) { //pr($m['Message']);?>
                <div><?php echo $m['Message']['body']; ?></div>
                <hr>
            <?php } ?>
        </div>
        <div class="reply_form">
            <?php echo $this->Form->create('Message', array('controller' => 'Messages', 'action' => 'view')); ?>

            <div class="block-change-pass">

                <?php echo $this->Form->input('Message.subject', array('type' => 'hidden', 'label' => false, 'class' => 'span12', 'id' => 'Message_subject', 'value' => $m['Message']['subject'], 'required' => false)); ?>
                <?php echo $this->Form->input('MessageIndex.thread_id', array('type' => 'hidden', 'label' => false, 'class' => 'span12', 'id' => 'Message_subject', 'value' => $thread_id, 'required' => false)); ?>

                <label>Reply</label>
                <?php echo $this->Form->input('Message.body', array('type' => 'textarea', 'label' => false, 'class' => 'ckeditor', 'cols' => '80', 'id' => 'editor4', 'rows' => '8', 'tabindex' => '1', 'required' => true)); ?>
                <div class="clearfix"></div>

                <?php echo $this->Form->submit('Send', array('class' => 'btn btn-primary pull-right')) ?>
                <div class="clearfix"></div>

            </div>
            <?php echo $this->Form->end(); ?>
        </div>

    </div>


</div>

	