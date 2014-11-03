
<!-- BEGIN PAGE -->
<div
	class="page-content">

	<!-- BEGIN PAGE CONTAINER-->
	<div class="container-fluid">
		<!-- BEGIN PAGE HEADER-->
		<div class="row-fluid">
			<div class="span12">

				<h3 class="page-title">
				<?php echo $title_for_layout ?>
					<small> </small>
				</h3>

			</div>
		</div>
		<!-- END PAGE HEADER-->
		<!-- BEGIN PAGE CONTENT-->
		<div class="row-fluid">
			<div class="span12">
				<div class="tabbable tabbable-custom boxless">


					<div class="tab-pane" id="tab_4">
						<div class="portlet box blue">
							<div class="portlet-title">
								<h4>
									<i class="icon-reorder"></i>
									<?php if(isset($this->request->data['Vocabulary']['id'])){ echo "Edit";}else{ echo "Add"; }?>
								</h4>

							</div>
							<div class="portlet-body form">
							<?php   echo $this->Session->flash();?>
								<!-- BEGIN FORM-->
							<?php echo $this->Form->create('Vocabulary',array('class'=>'form-horizontal form-row-seperated'));

							if(isset($this->request->data['Vocabulary']['id']) && !empty($this->request->data['Vocabulary']['id']))
							{
								echo $this->Form->hidden('id');
							}

							if(isset($this->request->data['Vocabulary']['weight']) && is_numeric($this->request->data['Vocabulary']['weight'])){
								echo $this->Form->hidden('weight');
							}else{
								echo $this->Form->hidden('weight',array('value'=>1));
							}
							?>

								<div class="control-group">
									<label class="control-label">Name</label>
									<div class="controls">
									<?php echo $this->Form->input('title',array('maxLength'=>255, 'class'=>'m-wrap span12','label'=>false));?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">alias</label>
									<div class="controls">
									<?php echo $this->Form->input('alias',array('maxLength'=>255, 'class'=>'m-wrap span12','label'=>false));?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Description</label>
									<div class="controls">
									<?php echo $this->Form->input('description',array('class'=>'m-wrap span12 ckeditor','label'=>false));?>
									</div>
								</div>



								<div class="form-actions">
									<button type="submit" class="btn blue">
										<i class="icon-ok"></i> Save
									</button>

									<?php echo $this->Html->link('Back To List', array('action'=>'index'),array('class'=>'btn'));?>
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