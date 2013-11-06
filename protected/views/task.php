<?php
/**
 * @author Nick Kaye <nick@outrightmental.com>
 * ©2013 Outright Mental Inc.
 * All Rights Reserved
 *
 * @var ProducteevTask $task
 */
if (!$task instanceof ProducteevTask)
    return;
?>
<!-- Example row of columns -->
<div class="row-fluid">
    <div class="task <?php echo $task->isActive() ? 'active' : 'done'; ?>">
        <div class="col-md-1"><?php echo $task->isActive() ? 'Active' : 'Done'; ?></div>
        <div class="col-md-3">
            <?php echo $task->title; ?>
        </div>
        <div class="col-md-2 deadline <?php
        if (strlen($task->deadline_status)) echo 'deadline_status_' . $task->deadline_status;
        ?>">
            Due <?php echo Util::datetime($task->deadline, App::DATETIME_FORMAT_PRETTY, $task->deadline_timezone); ?>
        </div>
        <div class="col-md-3">&nbsp;</div>
    </div>
</div>
<div class="clearfix"></div>