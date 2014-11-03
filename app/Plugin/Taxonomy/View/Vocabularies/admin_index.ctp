
<!-- BEGIN PAGE -->
		<div class="page-content">
			
			<!-- BEGIN PAGE CONTAINER-->			
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">

						<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
						<h3 class="page-title"><?php echo  $title_for_layout?>
		
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
							
							  <?php   echo $this->Session->flash();?>
							  
							  
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
									            url: '<?php echo SITEURL;?>admin/taxonomy/vocabularies/search_page',
									            data: datastring,
									            success: function(data) {
									            	$("#labs_ajax_search_btn").text('Search!');
									                $("#ajax-table-container").html(data);
									            },
									            error: function(comment) {
									            }});
									    });	
									      
									//	}
									}
							});
							function change_status(id)
							{
								if(id !="")
								{
									var datastring = "mssg="+id+"&from=users";
									$("#Page_list_"+id).text('updating...');
									    $(function() {
									        $.ajax({type: 'POST',
									            url: '<?php echo SITEURL;?>admin/pages/homes/updated_status',
									            data: datastring,
									            success: function(data) {
									            	$("#Page_list_"+id).html(data);
									            },
									            error: function(comment) {
									            }});
									    });	
									
								}
							}

							 </script>
							<label>Search: <input type="text" class="m-wrap" id="labs_ajax_search"><button id="labs_ajax_search_btn" type="button" class="btn green">Search!</button></label>
							
							</div>
							
							
								<div class="clearfix">
								<?php 
							echo $this->Html->link('Add New Vocabulary',
							 array('action' => 'add'),
							  array('icon' => 'chevron-up', 'tooltip' => __d('Taxonomy', 'Move up'),'class'=>'btn green')
		);
								?>
									<div class="btn-group">
										<i class="icon-plus"></i>
									</div>
							
								</div>
	<div id="ajax-table-container">							
								
 <table class="table table-striped table-bordered table-hover" id="sample_1">
<?php
	$tableHeaders = $this->Html->tableHeaders(array(
		$this->Paginator->sort('id', __d('Taxonomy', 'Id')),
		$this->Paginator->sort('title', __d('Taxonomy', 'Title')),
		$this->Paginator->sort('alias', __d('Taxonomy', 'Alias')),
		$this->Paginator->sort('created'),
		$this->Paginator->sort('updated'),
		__d('Taxonomy', 'Actions'),
	));
?>
	<thead>
		<?php echo $tableHeaders; ?>
	</thead>
<?php

	$rows = array();
	foreach ($vocabularies as $vocabulary) :
		$actions = array();

		$actions[] = $this->Html->link('<i class="icon-eye-open"></i> view',
				array('controller' => 'terms', 'action' => 'index', $vocabulary['Vocabulary']['id']),
			array('icon' => 'pencil', 'tooltip' => __d('Taxonomy', 'View terms'),'escape'=>false,'class'=>'btn mini purple')
		);
		
		
		/*$actions[] = $this->Html->link('Move up',
			array('action' => 'moveup', $vocabulary['Vocabulary']['id']),
			array('icon' => 'chevron-up', 'tooltip' => __d('Taxonomy', 'Move up'))
		);
		$actions[] = $this->Html->link('Move down',
			array('action' => 'movedown', $vocabulary['Vocabulary']['id']),
			array('icon' => 'chevron-down', 'tooltip' => __d('Taxonomy', 'Move down'))
		);*/
		
		
	 $actions[] = $this->Html->link('<i class="icon-edit"></i> Edit',
			array('action' => 'edit', $vocabulary['Vocabulary']['id']),
			array('icon' => 'pencil', 'tooltip' => __d('Taxonomy', 'Edit this item'),'escape'=>false,'class'=>'btn mini purple')
		);
		
		/*$actions[] = $this->Html->link('Remove',
			array('controller' => 'vocabularies', 'action' => 'delete', $vocabulary['Vocabulary']['id']),
			array('icon' => 'trash', 'tooltip' => __d('Taxonomy', 'Remove this item')),
			__d('Taxonomy', 'Are you sure?'));*/

		$actions = $this->Html->div('item-actions', implode(' ', $actions));
		
		$rows[] = array(
			$vocabulary['Vocabulary']['id'],
			$this->Html->link($vocabulary['Vocabulary']['title'], array('controller' => 'terms', 'action' => 'index', $vocabulary['Vocabulary']['id'])),
			$vocabulary['Vocabulary']['alias'],
			date('M-d-Y',strtotime($vocabulary['Vocabulary']['created'])),
			date('M-d-Y',strtotime($vocabulary['Vocabulary']['updated'])),
			$actions,
		);
	endforeach;
	
?>

<?php if($rows){
echo $this->Html->tableCells($rows);
}else {	?>
<tr>
	<td class="mid" colspan="6">No vocabulary found</td>
</tr>
<?php }?>

</table>
							
  <div class="span6">
     <div class="pagination pagination-large">
      <?php echo $this->Paginator->numbers(array('first' => 2, 'last' => 2));?>
     </div>
  </div>	
								
		</div>					</div>
						</div>
						<!-- END EXAMPLE TABLE PORTLET-->
					</div>
				</div>
				
				<!-- END PAGE CONTENT-->
			</div>
			<!-- END PAGE CONTAINER-->
		</div>
		<!-- END PAGE -->

