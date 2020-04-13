<?php

namespace taskForce\review\infrastructure;

use frontend\models\Review as modelReview;
use frontend\models\Task;
use taskForce\review\domain\ReviewsRepository;
use taskForce\review\infrastructure\builder\ArReviewBuilder;

class ArReviewsRepository implements ReviewsRepository
{
    /**
     * @var ArReviewBuilder
     */
    private $builder;

    /**
     * ArReviewsRepository constructor.
     * @param ArReviewBuilder $builder
     */
    public function __construct(ArReviewBuilder $builder)
    {
        $this->builder = $builder;
    }


    public function getCountReviewsByExecutorId(int $id): int
    {
        return modelReview::find()
        ->joinWith('task')
        ->where([Task::tableName().".executor_id" => $id])
        ->count();
    }

    public function getReviewsByExecutorId(int $id): array
    {
        $reviews = modelReview::find()
                    ->joinWith('task')
                    ->where([Task::tableName().".executor_id" => $id])
                    ->all();
        $reviewsList = [];
        foreach ($reviews as $review) {
            $reviewsList[] = $this->builder->build($review);
        }

        return $reviewsList;
    }
}
