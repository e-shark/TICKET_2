<?php

namespace frontend\modules\facilityeq\controllers;

use Yii;
use frontend\modules\facilityeq\models\Street;
use frontend\modules\facilityeq\models\StreetSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * StreetController implements the CRUD actions for Street model.
 */
class StreetController extends Controller
{
    /**
     * @inheritdoc
     */
    public $votes;

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
     * Lists all Street models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Street();
        $searchModel = new StreetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Street model.
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
     * Creates a new Street model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Street();
        //var_dump(Yii::$app->request->post());dei;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Street model.
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
     * Deletes an existing Street model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Street model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Street the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Street::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    public function actionSend()
    {
                
        $par1 = Yii::$app->request->post('streetdistrict', null);
        $par2 = Yii::$app->request->post('streettype', null);
        $par3 = Yii::$app->request->post('streetnameru', null);


        $_SESSION['st.streetdistrict']  = $par1;
        $_SESSION['st.streettype']    = $par2;
        $_SESSION['st.streetnameru']  = $par3;


        //Yii::$app->session->setFlash('success', '_  params:' . nl2br("\n") . 
        //    '1: ' . $par1 . nl2br("\n") . 
        //    '2: ' . $par2 . nl2br("\n") .  
        //    '3: ' . $par3 );
        
        //$searchModel = new StreetSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->redirect(
            [
                'index',
                'StreetSearch[streetdistrict]'=>$par1,
                'StreetSearch[streettype]'=>$par2,
                'StreetSearch[streetnameru]'=>$par3,
            ]);
    }

}
