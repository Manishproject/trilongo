<link rel="stylesheet" type="text/css" href="<?php echo SITEURL;?>assets/uniform/css/uniform.default.css" />
<link rel="stylesheet" href="<?php echo SITEURL;?>assets/data-tables/DT_bootstrap.css" />
	

<!-- BEGIN PAGE -->
		<div class="page-content">
			
			<!-- BEGIN PAGE CONTAINER-->			
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
						<h3 class="page-title">All users list <small></small>
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
						
						
						<div class="portlet-title">
								<h4><i class="icon-globe"></i>Manage</h4>
								<div class="tools"></div>
							</div>
						
						<div id="LabMsg"></div>
							<div class="portlet-body">
							<div class="dataTables_filter" id="sample_1_filter">
							<script> 
							$( document ).ready(function() {
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
									            url: '<?php echo SITEURL;?>admin/mails/mails/mail_search/',
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
							
							
								<div class="clearfix">
								<?php 
								echo $this->Html->link('Add New Mail', '/admin/mails/mails/new',array('class'=>'btn green'));?>
									<div class="btn-group">
										<i class="icon-plus"></i>
									</div>
							
								</div>
								
								<table class="table table-striped table-bordered table-hover" id="sample_1">
									<thead>
										<tr>
											<?php /*?><th style="width:8px;"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th> <?php */?>
											
											<th><?php echo $this->Paginator->sort('id');?></th>
											<th><?php echo $this->Paginator->sort('email');?></th>
											<th class="hidden-480"><?php echo $this->Paginator->sort('type');?></th>
											<th class="hidden-480"><?php echo $this->Paginator->sort('sender_name');?></th>
											<th class="hidden-480"><?php echo $this->Paginator->sort('subject');?></th>
											<th class="hidden-480"><?php echo $this->Paginator->sort('created');?></th>
											<th ></th>
										</tr>
									</thead>
									<tbody id="AllMails">
									
									<?php if(!empty($all)){
										foreach ($all as $user){?>
										<tr class="odd gradeX">
											<td><?php echo $user['Mail']['id'];?></td>
											<td><?php echo $this->Html->link($user['Mail']['email'], '/admin/mails/mails/new/'.$user['Mail']['id']);?></td>
											<td class="hidden-480"><?php echo $user['Mail']['type'];?></td>
											<td class="hidden-480"><?php echo $user['Mail']['sender_name'];?></td>
											<td class="hidden-480"><?php echo $user['Mail']['subject'];?></td>
											<td class="hidden-480"><?php echo date('M-d-Y',strtotime($user['Mail']['created']));?></td>
											<td class="hidden-480">
											
											<?php echo $this->Html->link( '<i class="icon-edit"></i> Edit',array('controller' => 'mails', 'action' => 'new/'.$user['Mail']['id']),array('class' => 'btn mini purple','admin'=>true,'escape' => false));?>
											
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
		
	<script type="text/javascript" src="<?php echo SITEURL;?>assets/data-tables/DT_bootstrap.js"></script>
	