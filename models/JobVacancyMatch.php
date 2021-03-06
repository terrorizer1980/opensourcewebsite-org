<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "job_vacancy_match".
 *
 * @property int $id
 * @property int $vacancy_id
 * @property int $resume_id
 *
 * @property Resume $resume
 * @property Vacancy $vacancy
 */
class JobVacancyMatch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'job_vacancy_match';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vacancy_id', 'resume_id'], 'required'],
            [['vacancy_id', 'resume_id'], 'integer'],
            [['resume_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resume::className(), 'targetAttribute' => ['resume_id' => 'id']],
            [['vacancy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vacancy::className(), 'targetAttribute' => ['vacancy_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vacancy_id' => 'Vacancy ID',
            'resume_id' => 'Resume ID',
        ];
    }

    /**
     * Gets query for [[Resume]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResume()
    {
        return $this->hasOne(Resume::className(), ['id' => 'resume_id']);
    }

    /**
     * Gets query for [[Vacancy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacancy()
    {
        return $this->hasOne(Vacancy::className(), ['id' => 'vacancy_id']);
    }
}
