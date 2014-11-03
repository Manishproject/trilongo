<?php 
class Message extends AppModel
{
	public $name = 'Message';
	
	var $validate = array(
		'subject1' =>array('rule'=>'notEmpty','message'=>"Title can not be blank !"),
	
		
        );
        
  public function Add($subject,$message,$user_id,$reservation_id,$message_type){
   	$data =array();
   	$data['Message']['subject']=$subject;
   	$data['Message']['message']=$message;
   	$data['Message']['user_id']=$user_id;
   	$data['Message']['status']=1;
   	$data['Message']['message_type']=$message_type;
   	$data['Message']['reservation_id']=$reservation_id;
    $this->set($data);
      $this->create(false);
    $this->save($data,false);
    return $this->id;
  }     
        
}
?>
