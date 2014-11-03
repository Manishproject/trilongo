
<table class="table table-striped table-bordered table-hover" id="sample_1">
<?php
	$tableHeaders = $this->Html->tableHeaders(array(
		$this->Paginator->sort('id', __d('Taxonomy', 'Id')),
		$this->Paginator->sort('title', __d('Taxonomy', 'Title')),
		$this->Paginator->sort('Slug', __d('Taxonomy', 'Slug')),
		$this->Paginator->sort('status'),
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
	foreach ($termsTree as $term):
	  $id = $term['Term']['id'];
	  $title = $term['Term']['title'];
	  $slug = $term['Term']['slug'];
		$actions = array();
			
	/*	$actions[] = $this->Html->link('Move up',
			array('action' => 'moveup',	$id, $vocabulary['Vocabulary']['id']),
			array('icon' => 'chevron-up', 'tooltip' => __d('Taxonomy', 'Move up'))
		);
		$actions[] = $this->Html->link('Move down',
			array('action' => 'movedown', $id, $vocabulary['Vocabulary']['id']),
			array('icon' => 'chevron-down', 'tooltip' => __d('Taxonomy', 'Move down'))
		);*/
		$actions[] = $this->Html->link('<i class="icon-edit"></i> Edit',
			array('action' => 'edit', $id, $vocabulary['Vocabulary']['id']),
			array('icon' => 'pencil', 'tooltip' => __d('Taxonomy', 'Edit this item'),'escape'=>false,'class'=>'btn mini purple')
		);
	/*	$actions[] = $this->Html->link('Remove',
			array('action' => 'delete',	$id, $vocabulary['Vocabulary']['id']),
			array('icon' => 'trash', 'tooltip' => __d('Taxonomy', 'Remove this item')),
			__d('Taxonomy', 'Are you sure?'));*/
		
	 $actions = $this->Html->div('item-actions', implode(' | ', $actions));

	$status= ($term['Term']['status'] == 1)?"Published":"Draft";
		 
	$status= '<a href="javascript:void(0);" id="Page_list_'.$term['Term']['id'].'" onclick="change_status('.$term['Term']['id'].')" title="Click here to change status">'.$status.'</a>';
											
	$rows[] = array(
			$id,
			$title,
			$slug,
			
			date('M-d-Y',strtotime($term['Term']['created'])),
			date('M-d-Y',strtotime($term['Term']['updated'])),
			$status,
			$actions,
		);
	endforeach;

?>
<?php if($rows){
echo $this->Html->tableCells($rows);
}else {	?>
<tr>
	<td class="mid" colspan="7">No search result found</td>
</tr>
<?php }?>

</table>

<div class="span6"><div class="pagination pagination-large">
	<?php echo $this->Paginator->numbers(array('first' => 2, 'last' => 2));?>
</div></div>