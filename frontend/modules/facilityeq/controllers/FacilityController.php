<?php

namespace frontend\modules\facilityeq\controllers;

use Yii;
use frontend\modules\facilityeq\models\Facility;
use frontend\modules\facilityeq\models\FacilitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FacilityController implements the CRUD actions for Facility model.
 */
class FacilityController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Facility models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Facility();
        $searchModel = new FacilitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Facility model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Facility model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Facility();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Facility model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing Facility model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        //return $this->redirect(['index']);
        return $this->redirect([
                'index',
                'FacilitySearch[fadistrict_id]' => $_SESSION['fa.fadistrict_id'],
                'FacilitySearch[fastreettype]'  => $_SESSION['fa.fastreettype'],
                'FacilitySearch[fastreetname]'  => $_SESSION['fa.fastreetname'],
                'FacilitySearch[fabuildingno]'  => $_SESSION['fa.fabuildingno'],
                'FacilitySearch[elfacility]'    => $_SESSION['fa.elfacility']]);
    }

    /**
     * Finds the Facility model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Facility the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Facility::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionSend()
    {
        
        $par1 = Yii::$app->request->post('fadistrict_id', null);
        $par2 = Yii::$app->request->post('fastreettype', null);
        $par3 = Yii::$app->request->post('fastreetname', null);
        $par4 = Yii::$app->request->post('fabuildingno', null);
        $par5 = Yii::$app->request->post('elfacility', null);
        
        $_SESSION['fa.fadistrict_id'] = $par1;
        $_SESSION['fa.fastreettype']  = $par2;
        $_SESSION['fa.fastreetname']  = $par3;
        $_SESSION['fa.fabuildingno']  = $par4;
        $_SESSION['fa.elfacility']    = $par5;

        //Yii::$app->session->setFlash('success', '_  params:' . nl2br("\n") . 
        //    '1: ' . $par1 . nl2br("\n") . 
        //    '2: ' . $par2 . nl2br("\n") .  
        //    '3: ' . $par3 . nl2br("\n") . 
        //    '4: ' . $par4 . nl2br("\n") . 
        //    '5: ' . $par5);
        
        //$searchModel = new FacilitySearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 
        //var_dump( Yii::$app->request->queryParams);exit;    
        //index?ElevatorSearch[eldevicetype]=10&ElevatorSearch[eldistrict]=&ElevatorSearch[elstreettype]=&ElevatorSearch[elstreetname]=&ElevatorSearch[elfacility_id]=  
        return $this->redirect([
                'index',
                'FacilitySearch[fadistrict_id]' => $par1,
                'FacilitySearch[fastreettype]'  => $par2,
                'FacilitySearch[fastreetname]'  => $par3,
                'FacilitySearch[fabuildingno]'  => $par4,
                'FacilitySearch[elfacility]'    => $par5 ]);
    }

}
