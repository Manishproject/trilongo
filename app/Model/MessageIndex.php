<?php

class MessageIndex extends AppModel
{
    public $name = 'MessageIndex';

    public function UnReadCount($uid, $type = '')
    {
        $cond = array();
        $cond['recipient_id'] = $uid;
        $cond['is_new'] = 1;
        if ($type == 'inbox') $cond['status'] = 'inbox';
        if ($type == 'outbox') $cond['status'] = 'outbox';
        if ($type == 'trash') $cond[] = 'deleted IS NOT NULL';
        else  $cond[] = 'deleted IS NULL';

        return $this->find('count', array('conditions' => $cond));
    }

    public function MarkasRead($uid, $thread_id)
    {
        return $this->updateAll(array('is_new' => 0), array('thread_id' => $thread_id, 'recipient_id' => $uid));
    }

    public function send($msg_id, $thread_id, $recipient_id = 0,$message_type)
    {
        $data = array();
        $data['MessageIndex']['message_id'] = $msg_id;
        $data['MessageIndex']['thread_id'] = $thread_id;
        $data['MessageIndex']['recipient_id'] = $recipient_id;
        $data['MessageIndex']['message_type'] = $message_type;
        $data['MessageIndex']['is_read'] = 0;
        $this->set($data);

        $this->create(false);
        $this->save($data, false);
        return $this->id;
    }


}

?>


