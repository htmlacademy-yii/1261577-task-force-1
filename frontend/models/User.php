<?php

namespace frontend\models;

use Yii;
use \yii\db\ActiveRecord;
use \yii\db\ActiveQuery;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $birthday
 * @property string|null $phone
 * @property string|null $skype
 * @property string|null $telegram
 * @property string|null $about
 * @property string $password
 * @property string|null $address
 * @property string $created_at
 * @property int|null $view_count
 * @property int|null $is_hidden
 * @property int|null $city_id
 * @property int $role_id
 * @property string|null $avatar
 * @property string|null $last_activity
 * @property float|null $rating
 * @property int|null $has_review
 *
 * @property Discussion[] $discussions
 * @property Discussion[] $discussions0
 * @property FavoriteExecutor[] $favoriteExecutors
 * @property FavoriteExecutor[] $favoriteExecutors0
 * @property Response[] $responses
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property City $city
 * @property Role $role
 * @property UserCategory[] $userCategories
 * @property UserSubscription[] $userSubscriptions
 */
class User extends ActiveRecord implements IdentityInterface
{
    private $_tasksCount;
    private $_customerTasksCount;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required'],
            [['birthday', 'created_at', 'last_activity'], 'safe'],
            [['about', 'address'], 'string'],
            [['view_count', 'is_hidden', 'city_id', 'role_id', 'has_review'], 'integer'],
            [['rating'], 'number'],
            [['name', 'email', 'phone', 'skype', 'password', 'avatar','telegram'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
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
            'email' => 'Email',
            'birthday' => 'Birthday',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'about' => 'About',
            'password' => 'Password',
            'address' => 'Address',
            'created_at' => 'Created At',
            'view_count' => 'View Count',
            'is_hidden' => 'Is Hidden',
            'city_id' => 'City ID',
            'role_id' => 'Role ID',
            'avatar' => 'Avatar',
            'last_activity' => 'Last Activity',
            'rating' => 'Rating',
            'has_review' => 'Has Review',
            'telegram' => 'Telegram',
        ];
    }

    /**
     * @param $count
     */
    public function setTasksCount($count)
    {
        $this->_tasksCount = (int) $count;
    }


    /**
     * @param $count
     */
    public function setCustomerTasksCount($count)
    {
        $this->_customerTasksCount = (int) $count;
    }

    /**
     * @return ActiveQuery
     */
    public function getDiscussions()
    {
        return $this->hasMany(Discussion::class, ['executor_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getDiscussions0()
    {
        return $this->hasMany(Discussion::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFavoriteExecutors()
    {
        return $this->hasMany(FavoriteExecutor::class, ['executor_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFavoriteExecutors0()
    {
        return $this->hasMany(FavoriteExecutor::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Task::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->via('userCategories');
    }

    /**
     * @return ActiveQuery
     */
    public function getUserSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTasksCount()
    {
        if($this->isNewRecord) {
            return null;
        }
        if($this->_tasksCount === null) {
            $this->setTasksCount($this->getTasks()->count());
        }

        return $this->_tasksCount;
    }

    /**
     * @return ActiveQuery
     */
    public function getCustomerTasksCount()
    {
        if($this->isNewRecord) {
            return null;
        }
        if($this->_customerTasksCount === null) {
            $this->setCustomerTasksCount($this->getTasks0()->count());
        }

        return $this->_customerTasksCount;
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
}
