<?php

    if ($_SESSION["access_hub_manager"] ==1)
    {
?>
<div class="hubs index">
	<h2><?php echo __('Hubs'); ?></h2>
	<table class="table table-striped">
	<tr>
			<th><?php echo $this->Paginator->sort('hub'); ?></th>
			<th><?php echo $this->Paginator->sort('web'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($hubs as $hub): ?>
	<tr>
		
		<td><?php echo h($hub['Hub']['hub']); ?>&nbsp;</td>
		<td><?php echo h($hub['Hub']['web']); ?>&nbsp;</td>
		<td class="actions">

			<?php echo $this->Html->link($this->Html->image('view.png', array('width' => '32', 'height' => '32')) . '' . __(''),
                       array('action' => 'view', $hub['Hub']['hub_id']),
                       array('escape' => false));?>
            <?php echo $this->Html->link($this->Html->image('edit.png', array('width' => '32', 'height' => '32')) . '' . __(''),
                       array('action' => 'edit', $hub['Hub']['hub_id']),
                       array('escape' => false));?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $hub['Hub']['hub_id']), array('class' => 'btn btn-md btn-danger'), __('Are you sure you want to delete # %s?', $hub['Hub']['hub'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
		<ul class="pagination">
		<?php
		  echo $this->Paginator->prev('&laquo;', array('tag' => 'li', 'escape' => false), '<a href="#">&laquo;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
		  echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentLink' => true, 'currentClass' => 'active', 'currentTag' => 'a'));
		  echo $this->Paginator->next('&raquo;', array('tag' => 'li', 'escape' => false), '<a href="#">&raquo;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
		?>
		</ul>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul class="list-inline">
		<li><?php echo $this->Html->link(__('New Hub'), array('action' => 'add'),array('class' => 'btn btn-md btn-success')); ?></li>
		<li><?php echo $this->Html->link(__('List Pilots'), array('controller' => 'gvausers', 'action' => 'index'),array('class' => 'btn btn-md btn-primary')); ?> </li>
		
	</ul>
</div>
<?php
    }
    else
    {
        echo '<div class="alert alert-danger"> You do not have access to Hub manager module</div>';
    }
?>
