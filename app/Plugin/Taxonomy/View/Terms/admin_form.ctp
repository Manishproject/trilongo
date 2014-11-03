<!-- BEGIN PAGE -->  
      <div class="page-content">
         
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">

                  <h3 class="page-title"><?php echo $title_for_layout ?><small> </small>
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
                              <div class="portlet-title">
                                 <h4><i class="icon-reorder"></i><?php if(isset($this->request->data['Term']['id'])){ echo "Edit";}else{ echo "Add"; }?></h4>
                              </div>
                              <div class="portlet-body form">
                                <?php   echo $this->Session->flash();?>
                                
                                 <!-- BEGIN FORM-->
                                 <?php echo $this->Form->create('Term',array('class'=>'form-horizontal form-row-seperated'));
                                 
                                 if(isset($this->request->data['Term']['id']) && !empty($this->request->data['Term']['id'])){
                                  	 echo $this->Form->hidden('id');
                                 }
                                 
                                  echo $this->Form->hidden('vocabulary_id',array('value'=>$vocabularyId));
         
                                  ?>

                                    
                                    <div class="control-group">
                                       <label class="control-label">Name</label>
                                       <div class="controls">
                                       <?php echo $this->Form->input('title',array('maxLength'=>255, 'class'=>'m-wrap span12','label'=>false));?>
                                       </div>
                                    </div>
                                    <div class="control-group">
                                       <label class="control-label">Slug</label>
                                       <div class="controls">
                                       <?php echo $this->Form->input('slug',array('maxLength'=>255, 'class'=>'m-wrap span12','label'=>false));?>
                                       </div>
                                    </div>
                                    <div class="control-group">
                                       <label class="control-label">Description</label>
                                       <div class="controls">
                                       <?php echo $this->Form->input('description',array('class'=>'m-wrap span12 ckeditor','label'=>false));?>
                                       </div>
                                    </div>
                           <div class="control-group">
                                       <label class="control-label">Parent</label>
                                       <div class="controls">
                                       <?php  echo $this->Form->input('Taxonomy.parent_id', array('options' => $parentTree,	'empty' => true,'label' =>false,
				)); ?>
                                       </div>
                            </div>
                                    
                                  <div class="control-group">
                                       <label class="control-label">Status</label>
                                       <div class="controls">
                                       <?php echo $this->Form->input('status',array('options'=>array('0'=>'Draft','1'=>'Publish'),'class'=>'n-wrap span12','label'=>false));?>
                                       </div>
                                    </div>
                                    
                                    <div class="form-actions">
                                       <button type="submit" class="btn blue"><i class="icon-ok"></i> Save</button>
                                       
                                       <?php echo $this->Html->link('Back To List', array('action'=>'index',$vocabularyId),array('class'=>'btn'));?>
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
