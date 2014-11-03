			
								
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
	<td class="mid" colspan="6">No search result found</td>
</tr>
<?php }?>

</table>
							
  <div class="span6">
     <div class="pagination pagination-large">
      <?php echo $this->Paginator->numbers(array('first' => 2, 'last' => 2));?>
     </div>
  </div>	
								
