<script type="text/javascript" src="<?php echo SITEURL;?>assets/js/jquery.friendurl.js"></script>
<script type="text/javascript">
$(function(){
	$('#PageTitle').friendurl({id : 'PageUrl'});
});
</script>

<!-- BEGIN PAGE -->  
      <div class="page-content">
         <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
         <div id="portlet-config" class="modal hide">
            <div class="modal-header">
               <button data-dismiss="modal" class="close" type="button"></button>
               <h3>portlet Settings</h3>
            </div>
            <div class="modal-body">
               <p>Here will be a configuration form</p>
            </div>
         </div>
         <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">

                  <h3 class="page-title"><?php if(isset($this->request->data['Page']['id'])){ echo "Edit"; }else{ echo "Create New"; }?> Page<small> </small>
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
                                 <h4><i class="icon-reorder"></i><?php if(isset($this->request->data['Page']['id'])){ echo "Edit Page";}else{ echo "New Page"; }?></h4>
                                 <!--<div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="#portlet-config" data-toggle="modal" class="config"></a>
                                    <a href="javascript:;" class="reload"></a>
                                    <a href="javascript:;" class="remove"></a>
                                 </div>
                              --></div>
                              <div class="portlet-body form">
                                 <!-- BEGIN FORM-->
                                 <?php echo $this->Form->create('Page',array('class'=>'form-horizontal form-row-seperated'));
                                 
                                 if(isset($this->request->data['Page']['id']) && !empty($this->request->data['Page']['id']))
                                 {
                                  	 echo $this->Form->hidden('id');
                                 }
                                 
                                  echo $this->Session->flash('msg');?>
                                 
                                    <div class="control-group">
                                       <label class="control-label">Page Title</label>
                                       <div class="controls">
                                       <?php echo $this->Form->input('title',array('maxLength'=>255, 'class'=>'m-wrap span12','label'=>false));?>
                                       </div>
                                    </div>
                                    <div class="control-group">
                                       <label class="control-label">Page url</label>
                                       <div class="controls">
                                       <?php echo $this->Form->input('url',array('maxLength'=>255, 'class'=>'m-wrap span12','label'=>false));?>
                                       </div>
                                    </div>
                                    <div class="control-group">
                                       <label class="control-label">Page Description</label>
                                       <div class="controls">
                                       <?php echo $this->Form->input('description',array('class'=>'m-wrap span12','label'=>false));?>
                                       </div>
                                    </div>
                                    
                                    <div class="control-group">
                                       <label class="control-label">Meta Keyword</label>
                                       <div class="controls">
                                       <?php echo $this->Form->input('keywords',array('class'=>'m-wrap span12','label'=>false));?>
                                       </div>
                                    </div>
                                    <div class="control-group">
                                       <label class="control-label">Status</label>
                                       <div class="controls">
                                       <?php echo $this->Form->input('status',array('options'=>array('0'=>'Draft','1'=>'Publish'),'class'=>'n-wrap span12','label'=>false));?>
                                       </div>
                                    </div>
                                    <div class="control-group">
                                       <label class="control-label">Page Data</label>
                                       <div class="controls">
                                       <?php echo $this->Form->input('post_data',array('id'=>'editor1','readonly'=>'readonly','class'=>'span12 ckeditor m-wrap','label'=>false));  ?>
                                       </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="form-actions">
                                       <button type="submit" class="btn blue"><i class="icon-ok"></i> Save</button>
                                       
                                       <?php echo $this->Html->link('Back To all Pages', '/admin/pages/homes/',array('class'=>'btn'));?>
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
   
   <script type="text/javascript" src="<?php echo SITEURL;?>assets/ckeditor/ckeditor.js"></script>  
