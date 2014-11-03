<?php
class Proposal extends Model{
    public $validate = array(
      
    );
    
 function add($reservation_id,$pid,$proposed_amount,$currency='USD',$is_awarded=false,$is_accepted=false){
  	$data =array();

   	$data['Proposal']['proposed_amount']=$proposed_amount;
   	$data['Proposal']['provider_id']=$pid;
   	$data['Proposal']['is_awarded']=$is_awarded;
   	$data['Proposal']['is_accepted']=$is_accepted;
   	$data['Proposal']['currency']=$currency;
   	$data['Proposal']['reservation_id']=$reservation_id;
    $this->set($data);
    $this->save($data,false);
    return $this->id;	
 	
 }   
}
?>