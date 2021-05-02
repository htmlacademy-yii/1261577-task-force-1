<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "task_status".
 *
 * @property int $id
 * @property string $name
 * @property string|null $translation
 *
 * @property Task[] $tasks
 */
class TaskStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const NAME_STATUS_NEW = 'Новое';
    const NAME_STATUS_JOB = 'В работе';
    const NAME_STATUS_CANCEL = 'Отменено';
    const NAME_STATUS_COMPLETE = 'Выполнено';
    const NAME_STATUS_FAIL = 'Провалено';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'translation'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'translation' => 'Translation',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['status_id' => 'id']);
    }
}
