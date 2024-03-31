<?php
  
    if ($_SESSION["access_courses_manager"] ==1)
    {
?>
<div class="courses view">
	<div class="col-md-12">
	    <div class="panel panel-primary">
	        <div class="panel-heading">
	              <h3 class="panel-title"><?php  echo __('courses'); ?></h3>
	        </div>
	        <div class="panel-body">
	            <table class="table table-striped">
	              	<tr>
	              		<td><?php echo __('courses Name'); ?></td>
	              		<td><?php echo h($courses['courses']['courses_name']); ?></td>
	              	</tr>
	              	<tr>
	              		<td><?php echo __('Create Date'); ?></td>
	              		<td><?php echo h($courses['courses']['create_date']); ?></td>
	              	</tr>
	              	<tr>
	              		<td><?php echo __('Publish Date'); ?></td>
	              		<td><?php echo h($courses['courses']['publish_date']); ?></td>
	              	</tr>
	              	<tr>
	              		<td><?php echo __('Hide Date'); ?></td>
	              		<td><?php echo h($courses['courses']['hide_date']); ?></td>
	              	</tr>
	              	<tr>
	              		<td><?php echo __('courses Description'); ?></td>
	              		<td><?php echo h($courses['courses']['courses_text']); ?></td>
	              	</tr>
	              </table>
	        </div>
	    </div>
	</div>


<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul class="list-inline">
		<li><?php echo $this->Html->link(__('Edit courses'), array('action' => 'edit', $courses['courses']['courses_id']),array('class' => 'btn btn-md btn-primary')); ?> </li>
		<li><?php echo $this->Html->link(__('List courses'), array('action' => 'index'),array('class' => 'btn btn-md btn-primary')); ?> </li>
		<li><?php echo $this->Html->link(__('New courses'), array('action' => 'add'),array('class' => 'btn btn-md btn-success')); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete courses'), array('action' => 'delete', $courses['courses']['courses_id']),array('class' => 'btn btn-md btn-danger'), __('Are you sure you want to delete # %s?', $courses['courses']['courses_id'])); ?> </li>
	</ul>
</div>
<?php
    }
    else
    {
        echo '<div class="alert alert-danger"> You do not have access to courses manager module</div>';
    }
?>
