<?php

namespace frontend\modules\facilityeq\controllers;

use Yii;
use frontend\modules\facilityeq\models\StreetSearch;
use frontend\modules\facilityeq\models\ImageUpload;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * Default controller for the `facilityeq` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
    	
    	//$searchModel = new StreetSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
        //    'searchModel' => $searchModel,
        //    'dataProvider' => $dataProvider,
        ]);

    }


	public function attributeLabels()
    {
        return [
            'streetdistrict' => 'Район',
            'streetname' => 'Назавние улицы',
            'streetnameru' => 'Название улицы (рус)',
        ];
    }

    public function actionSetRecord()
    {

        return $this->render('record');

    }

    public function actionView($id)
    {
        return $this->render('view');
        //, [
        //    'model' => $this->findModel($id),
        //]);
    }

    /**
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /*$model = new Country();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);*/

        return $this->render('create');
    }

    /**
     * Updates an existing Country model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        /*$model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);*/
        return $this->render('update');
    }

}