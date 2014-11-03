
<?php if(!empty($all)){
	foreach ($all as $list){?>
<tr class="odd gradeX">
	<td><?php echo $list['Page']['id'];?></td>
	<td><?php echo substr($list['Page']['title'],0,50);?></td>
	<td class="hidden-480"><?php echo substr($list['Page']['url'],0,50);?></td>
	<td class="hidden-480"><?php echo substr($list['Page']['description'],0,50);?></td>
	<td class="hidden-480"><?php echo substr($list['Page']['keywords'],0,50);?></td>
	<td class="hidden-480"><?php echo $list['Page']['views'];?></td>
	<td class="hidden-480 is_cursor"><a href="javascript:void(0);"
		id="Page_list_<?php echo $list['Page']['id'];?>"
		onclick="change_status(<?php echo $list['Page']['id'];?>)"
		title="Click here to change status"><?php if($list['Page']['status'] == 1){ echo "Published";}else{ echo "Draft";} ?></a></td>
	<td class="hidden-480"><?php if(!empty($list['Page']['created'])) echo date('M-d-Y',strtotime($list['Page']['created']));?></td>
	<td class="hidden-480"><?php if(!empty($list['Page']['updated'])) echo date('M-d-Y',strtotime($list['Page']['updated']));?></td>
	<td class="hidden-480"><?php echo $this->Html->link( '<i class="icon-edit"></i> Edit',
	array('controller' => 'homes', 'action' => 'new/'.$list['Page']['id']),array('class' => 'btn mini purple','admin'=>true,'escape' => false));?>

	</td>
</tr>
	<?php }}else{?>
<tr>
	<td colspan="10" class="mid">record not found</td>
</tr>
	<?php }?>