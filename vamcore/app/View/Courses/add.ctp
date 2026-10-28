<?php if (!empty($_SESSION['access_courses_manager'])): ?>
<div class="courses form">
<?php echo $this->Form->create('course'); ?>
	<fieldset>
		<legend><?php echo __('Add course'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('content', array('type' => 'textarea'));
		echo $this->Form->input('start_date', array('type' => 'date'));
		echo $this->Form->input('end_date', array('type' => 'date'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<ul class="list-inline">
		<li><?php echo $this->Html->link(__('List courses'), array('action' => 'index'), array('class' => 'btn btn-md btn-primary')); ?></li>
	</ul>
</div>
<?php else: ?>
<div class="alert alert-danger">You do not have access to courses manager module.</div>
<?php endif; ?>
