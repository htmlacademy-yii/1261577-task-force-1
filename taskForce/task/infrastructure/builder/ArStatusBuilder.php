<?php

namespace taskForce\task\infrastructure\builder;

use frontend\models\TaskStatus;
use taskForce\task\domain\Status;

class ArStatusBuilder
{
    /**
     * @param TaskStatus $model
     * @return Status
     */
    public function build(TaskStatus $model): Status
    {
        $status = new Status();
        $status->id = $model->id;
        $status->name = $model->name;
        $status->translation = $model->translation;

        return $status;
    }
}
