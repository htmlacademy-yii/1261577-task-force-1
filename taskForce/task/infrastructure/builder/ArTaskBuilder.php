<?php

namespace taskForce\task\infrastructure\builder;

use frontend\models\Task as modelTask;
use taskForce\category\infrastructure\builder\ArCategoryBuilder;
use taskForce\task\domain\ImageList;
use taskForce\task\domain\Location;
use taskForce\task\domain\Task;
use taskForce\user\infrastructure\builder\ArUserBuilder;

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
        $userBuilder = new ArUserBuilder();
        $statusBuilder = new ArStatusBuilder();
        $task->author = $userBuilder->build($model->user);
        $task->executor = $userBuilder->build($model->executor);
        $task->id = $model->id;
        $task->shortName = $model->short;
        $task->description = $model->description;
        $task->address = $model->address;
        $task->budget = $model->budget;
        $task->dateCreate = $model->created_at;
        $task->deadline = $model->deadline;
        $task->category = $categoryBuilder->build($model->category);
        $task->status = $statusBuilder->build($model->status);
        $location = new Location();
        $imageList = new ImageList();
        if($detailView === true) {
            $location->longitude = $model->longitude;
            $location->latitude = $model->latitude;
            $imageBuilder = new ArImageBuilder();
            foreach ($model->taskImages as $image) {
                $imageList[] = $imageBuilder->build($image);
            }
        }
        $task->countResponses = $model->getResponsesCount();
        $task->location = $location;
        $task->images = $imageList;
        $task->cityId = $model->city_id;

        return $task;
    }
}
