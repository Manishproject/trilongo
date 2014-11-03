<?php if(!empty($EmailTemplates)){
	foreach ($EmailTemplates as $EmailTemplate){?>
<tr class="odd gradeX">
	<td><?php echo $EmailTemplate['EmailTemplate']['id'];?></td>

	<td class="hidden-480"><?php echo $this->Html->link($EmailTemplate['EmailTemplate']['subject'], '/admin/email_template/email_template/new/'.$EmailTemplate['EmailTemplate']['id']);?>
		<td class="hidden-480"><?php echo $EmailTemplate['EmailTemplate']['slug'];?>
			<td class="hidden-480"><?php echo $EmailTemplate['EmailTemplate']['sender_name'];?>
	</td>
		<td><?php echo $EmailTemplate['EmailTemplate']['email_from']?>
	</td>

	</td>

	</td>
	<td class="hidden-480"><?php echo date('M-d-Y',strtotime($EmailTemplate['EmailTemplate']['created']));?>
	</td>
	<td class="hidden-480"><?php echo $this->Html->link( '<i class="icon-edit"></i> Edit',
	array('controller' => 'email_template', 'action' => 'new/'.$EmailTemplate['EmailTemplate']['id']),array('class' => 'btn mini purple','admin'=>true,'escape' => false));?>

	</td>
</tr>
	<?php }}else{?>
<tr>
	<td colspan="7" class="mid">record not found</td>
</tr>
	<?php }?>
