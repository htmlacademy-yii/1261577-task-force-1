<?php

namespace taskForce\task\infrastructure\builder;

use frontend\models\Task as modelTask;
use taskForce\category\infrastructure\builder\ArCategoryBuilder;
use taskForce\task\domain\Location;
use taskForce\task\domain\Task;

class ArTaskBuilder
{
    /**
     * @param modelTask $model
     * @param bool $detailView
     * @return Task
     */
    public function build(modelTask $model, $detailView = false): Task
    {
        $task = new Task();
        $categoryBuilder = new ArCategoryBuilder();
        $task->id = $model->id;
        $task->shortName = $model->short;
        $task->description = $model->description;
        $task->address = $model->address;
        $task->budget = $model->budget;
        $task->created_at = $model->created_at;
        $task->category = $categoryBuilder->build($model->category);
        if($detailView === true) {
            $task->location = new Location($model->latitude, $model->longitude); //dto
        }

        return $task;
    }
}
