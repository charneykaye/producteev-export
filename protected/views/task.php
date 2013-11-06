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
        <!-- Status -->
        <div class="col-md-1"><?php echo $task->isActive() ? 'Active' : 'Done'; ?></div>

        <!-- Project -->
        <div class="col-md-1"><?php echo $task->project->title; ?></div>

        <!-- Deadline -->
        <div class="col-md-1 deadline <?php
        if (strlen($task->deadline_status)) echo 'deadline_status_' . $task->deadline_status;
        ?>">
            <?php if ($task->deadline) echo Util::datetime($task->deadline, App::DATETIME_FORMAT_PRETTY_DAY, $task->deadline_timezone); ?>
        </div>

        <!-- Title -->
        <div class="col-md-3">
            <?php echo $task->title; ?>
        </div>

        <!-- Subtasks -->
        <div class="col-md-3"><?php if (count($task->subtasks)): ?>
                <ul>
                    <?php
                    /** @var ProducteevTask $task */
                    foreach ($task->subtasks as $subtask)
                        if ($task instanceof ProducteevTask)
                            echo '<li>' . $subtask->title . '</li>';
                    ?>
                </ul>
            <?php endif; ?>&nbsp;</div>

        <!-- Responsibles -->
        <div class="col-md-1"><?php if (count($task->responsibles)): ?>
                <ul>
                    <?php
                    /** @var ProducteevUser $user */
                    foreach ($task->responsibles as $user)
                        if ($user instanceof ProducteevUser)
                            echo '<li>' . $user->name() . '</li>';
                    ?>
                </ul>
            <?php endif; ?>&nbsp;</div>

        <!-- Labels -->
        <div class="col-md-1"><?php if (count($task->labels)): ?>
                <ul>
                    <?php
                    /** @var ProducteevLabel $user */
                    foreach ($task->labels as $label)
                        if ($label instanceof ProducteevLabel)
                            echo '<li style="color:' . $label->foreground_color . ';background-color:' . $label->background_color . ';">' . $label->title . '</li>';
                    ?>
                </ul>
            <?php endif; ?>&nbsp;</div>

        <!-- Creator -->
        <div
            class="col-md-1"><?php if ($task->creator && $task->creator instanceof ProducteevUser) echo $task->creator->name(); ?></div>

    </div>
</div>
<div class="clearfix"></div>