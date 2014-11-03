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
