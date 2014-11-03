
<!-- BEGIN PAGE -->
		<div class="page-content maincontent noright">
			
			<!-- BEGIN PAGE CONTAINER-->			
			<div class="container-fluid content">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
						<h3 class="page-title"><?php echo $title_for_layout?> <small></small>
						</h3>
		
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet box light-grey">
						

						
						<div id="LabMsg"></div>
							<div class="portlet-body">
							<div class="dataTables_filter1" id="sample_1_filter">
							<script> 
							
							jQuery(document).ready(function($) {
								$('#LabMsg').html('');
								$( "#labs_ajax_search_btn" ).click(function(e) { call( $( "#labs_ajax_search" ).val()); });
								$('#labs_ajax_search').live('keypress',function(e){ var p = e.which; if(p==13) {  call($( "#labs_ajax_search" ).val()); } });
							   function call(txt)
							   {
								   //if(txt ==""){ $('#LabMsg').html('<div class="alert alert-error"><button data-dismiss="alert" class="close"></button><strong>Error!</strong> Enter search value.</div>'); }else{
									var datastring = "mssg="+txt+"&from=users";
									$("#LabMsg").html('');
									$("#labs_ajax_search_btn").text('Searching...');
									    $(function() {
									        $.ajax({type: 'POST',
									            url: '<?php echo SITEURL;?>admin/email_template/email_template/mail_search/',
									            data: datastring,
									            success: function(data) {
									            	$("#labs_ajax_search_btn").text('Search!');
									                $("#AllMails").html(data);
									            },
									            error: function(comment) {
									            }});
									    });	
									      
									//	}
									}
							});

							 </script>
							<label>Search: <input type="text" class="m-wrap" id="labs_ajax_search"><button id="labs_ajax_search_btn" type="button" class="btn green">Search!</button></label>
							
							</div>
							
							
								<div class="clearfix clear">
								<?php 
								echo $this->Html->link('Add New Mail', '/admin/email_template/email_template/new',array('class'=>'btn green'));?>
									<div class="btn-group">
										<i class="icon-plus"></i>
									</div>
							
								</div>
								
								<table class="table table-striped table-bordered table-hover" id="sample_1">
									<thead>
										<tr>
											<?php /*?><th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th> <?php */?>
											
											<th><?php echo $this->Paginator->sort('id');?></th>
											<th class="hidden-480"><?php echo $this->Paginator->sort('subject');?></th>
											<th class="hidden-480"><?php echo $this->Paginator->sort('slug');?></th>
											<th class="hidden-480"><?php echo $this->Paginator->sort('sender_name');?></th>
											<th><?php echo $this->Paginator->sort('email_from');?></th>
											<th class="hidden-480"><?php echo $this->Paginator->sort('created');?></th>
											<th ></th>
										</tr>
									</thead>
									<tbody id="AllMails">
									
									<?php if(!empty($all)){
										foreach ($all as $EmailTemplate){?>
										<tr class="odd gradeX">
											<td><?php echo $EmailTemplate['EmailTemplate']['id'];?></td>
											<td class="hidden-480"><?php echo $this->Html->link($EmailTemplate['EmailTemplate']['subject'], '/admin/email_template/email_template/new/'.$EmailTemplate['EmailTemplate']['id']);?></td>	
											<td class="hidden-480"><?php echo $EmailTemplate['EmailTemplate']['slug'];?></td>
	
											<td class="hidden-480"><?php echo $EmailTemplate['EmailTemplate']['sender_name'];?></td>
										
												<td><?php echo $EmailTemplate['EmailTemplate']['email_from']?>	</td>
											<td class="hidden-480"><?php echo date('M-d-Y',strtotime($EmailTemplate['EmailTemplate']['created']));?></td>
											<td class="hidden-480">
											
											<?php echo $this->Html->link( '<i class="icon-edit"></i> Edit',array('controller' => 'email_template', 'action' => 'new/'.$EmailTemplate['EmailTemplate']['id']),array('class' => 'btn mini purple','admin'=>true,'escape' => false));?>
											
											</td>
										</tr>
										<?php }}else{?> 
										<tr><td colspan="7" class="mid"> record not found </td></tr>
										<?php }?>

	
									</tbody>
								</table>
							
							<div class="span6"><div class="pagination pagination-large">
								<?php echo $this->Paginator->numbers(array('first' => 2, 'last' => 2));?>
								</div></div>	
								
							</div>
						</div>
						<!-- END EXAMPLE TABLE PORTLET-->
					</div>
				</div>
				
				<!-- END PAGE CONTENT-->
			</div>
			<!-- END PAGE CONTAINER-->
		</div>
		<!-- END PAGE -->
