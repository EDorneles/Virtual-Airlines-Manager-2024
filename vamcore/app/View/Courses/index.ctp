<?php
   
    if ($_SESSION["access_courses_manager"] ==0)
    {
?>
<div class="courses index">
	<h2><?php echo __('courses'); ?></h2>
	<table class="table table-striped">
	<tr>
			<th><?php echo $this->Paginator->sort('create_date'); ?></th>
			<th><?php echo $this->Paginator->sort('publish_date'); ?></th>
			<th><?php echo $this->Paginator->sort('hide_date'); ?></th>
			<th><?php echo $this->Paginator->sort('course_name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($courses as $course): ?>
	<tr>
		<td><?php echo h($course['course']['create_date']); ?>&nbsp;</td>
		<td><?php echo h($course['course']['publish_date']); ?>&nbsp;</td>
		<td><?php echo h($course['course']['hide_date']); ?>&nbsp;</td>
			<td><?php echo h($course['course']['course_name']); ?>&nbsp;</td>
		<td class="actions">

			<?php echo $this->Html->image("view.png", array(
			    'url' => array('controller' => 'courses', 'action' => 'view', $course['course']['course_id'])
				));?>
			<?php echo $this->Html->image("edit.png", array(
			    'url' => array('controller' => 'courses', 'action' => 'edit', $course['course']['course_id'])
				));?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $course['course']['course_id']), array('class' => 'btn btn-md btn-danger'), __('Are you sure you want to delete # %s?', $course['course']['course_id'])); ?>
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
		<li><?php echo $this->Html->link(__('New course'), array('action' => 'add'),array('class' => 'btn btn-md btn-success')); ?></li>
	</ul>
</div>
<?php
    }
    else
    {
        echo '<div class="alert alert-danger"> You do not have access to courses manager module</div>';
    }
?>
