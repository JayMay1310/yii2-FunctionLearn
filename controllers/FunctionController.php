<?php

namespace app\controllers;



use yii;
use yii\web\UploadedFile;
use yii\db\Expression;
use app\models\Category;
use app\models\Functions;
use app\models\UploadForm;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Json;

class FunctionController extends \yii\web\Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDelete($id)
    {
        $model = Functions::findOne((int)$id);
        $model->delete();
        return $this->redirect(['category/index']);
    }

    public function actionUpdate($id)
    {
        $categories = Category::find()->all();
        $model = Functions::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'categories' => $categories,
            ]);
        }
    }

    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->xmlFile = UploadedFile::getInstance($model, 'xmlFile');
            if ($model->upload()) {
                $file_path = 'uploads/' . $model->xmlFile->baseName . '.' . $model->xmlFile->extension;
                $xml = simplexml_load_file($file_path, "SimpleXMLElement", LIBXML_NOCDATA);
                $json = json_encode($xml);
                $array_xml = json_decode($json,TRUE);
        
                foreach ($array_xml['link'] as $value) 
                {
                    $category = Category::find()->where(['title' => $value['category']])->one();
                    $isFunction = Functions::find()->where(['function'=>$value['function']])->one(); 
                    if (empty($isFunction))
                    {
                        $learn = new Functions();
                        $learn->function = $value['function'];
                        $learn->value = $value['value'];
                        $learn->category_id = $category->id;
                        $learn->link = $value['link'];        
                        $learn->save();
                    }
                }

                return $this->redirect(['index', ]);
            }
        }
        return $this->render('upload', ['model' => $model]);
    }


    
    public function actionCreate($id = null)
    {
        $request = Yii::$app->request;

        $get = $request->get();

        $categories = Category::find()->all();
        $model = new Functions();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['create', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'categories' => $categories,
            ]);
        }

        return $this->render('create');
    }

    public function actionLearn()
    {
        $action=Yii::$app->request->post('action');
        $selection=(array)Yii::$app->request->post('selection');//typecasting
        $model_redirect = Functions::findOne((int)$selection[0]);
        $category_redirect = $model_redirect->category_id;

        foreach($selection as $id){
            $model = Functions::findOne((int)$id);//make a typecasting
            $category = Category::findOne((int)$model->category_id);
            $category->last_update = new Expression('NOW()');
            $category->save(false); 

            $model->last_update = new Expression('NOW()');
            $model->count = $model->count + 1;
            $model->save(false); 
       }

       return $this->redirect(['category/view', 'id' => $category_redirect]);     
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action))
        {
            return false;
        }

        if (!Yii::$app->user->isGuest)
        {
            return true;
        }
        else
        {
            Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());
            //для перестраховки вернем false
            return false;
        }
    }
}
