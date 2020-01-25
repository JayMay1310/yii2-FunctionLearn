<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "word".
 *
 * @property int $id
 * @property string $word
 * @property string $translation
 * @property int $category_id
 * @property string $last_update
 * @property int $count
 *
 * @property Functions $category
 */
class Functions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'functions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'count'], 'integer'],
            [['last_update'], 'safe'],
            [['function', 'value', 'link'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'function' => 'Функция',
            'value' => 'Что делает?',
            'link' => 'Link',
            'category_id' => 'Category ID',
            'last_update' => 'Last Update',
            'count' => 'Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

}