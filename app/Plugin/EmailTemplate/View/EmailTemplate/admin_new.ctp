<!-- BEGIN PAGE -->  
      <div class="page-content  maincontent">
        
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid content">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">

                  <h3 class="page-title"><?php if(isset($this->request->data['EmailTemplate']['id'])){ echo "Edit"; }else{ echo "Create New"; }?> Email Template<small> </small>
                  </h3>
         
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row-fluid">
               <div class="span12">
                  <div class="tabbable tabbable-custom boxless">
  
  
  <div class="tab-pane"  id="tab_4">
                           <div class="portlet box blue">
             
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <?php echo $this->Form->create('EmailTemplate',array('class'=>'form-horizontal form-row-seperated'));
                                 
                                 if(isset($this->request->data['EmailTemplate']['id']) && !empty($this->request->data['EmailTemplate']['id']))
                                 {
                                  	 echo $this->Form->hidden('id');
                                 }
                                 ?>
                                 
                                    <div class="control-group">
                                       <label class="control-label">Template Id</label>
                                       <div class="controls">
                                       <?php
                                       if(isset($this->request->data['EmailTemplate']['id']) && !empty($this->request->data['EmailTemplate']['id'])){
                                 			 echo $this->Form->input('slug',array('readonly'=>'readonly','class'=>'m-wrap span12','label'=>false)); 
                                       }
                                 		else{
                                 			echo $this->Form->input('slug',array('class'=>'m-wrap span12','label'=>false)); 
                                 		}
                                 		?>
                                          
                                          <span class="help-inline">Enter email type</span>
                                       </div>
                                    </div>
                                    
                                    <div class="control-group">
                                       <label class="control-label">Sender Name</label>
                                       <div class="controls">
                                          <?php echo $this->Form->input('sender_name',array('class'=>'m-wrap span12','label'=>false));?>
                                          <span class="help-inline">Enter sender name</span>
                                       </div>
                                    </div>
                                    
                                    
                                    <div class="control-group">
                                       <label class="control-label">Email From</label>
                                       <div class="controls">
                                          <?php echo $this->Form->input('email_from',array('class'=>'m-wrap span12','label'=>false));?>
                                          <span class="help-inline">Enter email address</span>
                                       </div>
                                    </div>
                                    
                                    <div class="control-group">
                                       <label class="control-label">Subject</label>
                                       <div class="controls">
                                          <?php echo $this->Form->input('subject',array('class'=>'m-wrap span12','label'=>false));?>
                                          <span class="help-inline">Enter subject</span>
                                       </div>
                                    </div>
                                    
                                    
                                       <div class="control-group">
                              <label class="control-label">Message Body</label>
                              <div class="controls">
                              <?php echo $this->Form->input('message',array('id'=>'editor1','readonly'=>'readonly','class'=>'span12 ckeditor m-wrap','label'=>false));  ?>
                              </div>
                           </div>
                                    
                                    <div class="form-actions">
                                       <button type="submit" class="btn blue"><i class="icon-ok"></i> Save</button>
                                       
                                       <?php echo $this->Html->link('Back To all emails', '/admin/email_template',array('class'=>'btn'));?>
                                    </div>
                                    
                                 <?php echo $this->Form->end(); ?>
                                 <!-- END FORM-->  
                              </div>
                           </div>
                        </div>
                 
                  </div>
               </div>
            </div>
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  
   
