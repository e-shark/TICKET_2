<?php

namespace frontend\modules\facilityeq\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

use frontend\modules\facilityeq\models\Elevator;
use frontend\modules\facilityeq\models\ElevatorSearch;
use frontend\modules\facilityeq\models\District;
use frontend\modules\facilityeq\models\Street;
use frontend\modules\facilityeq\models\Facility;
use frontend\modules\facilityeq\models\Elgallery;

/**
 * ElevatorController implements the CRUD actions for Elevator model.
 */
class ElevatorController extends Controller
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
     * Lists all Elevator models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Elevator();
        $searchModel = new ElevatorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageSize=25;

        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Elevator model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

	public function actionFileUpload($id)	// http://tickets/index.php?r=elevator%2file-upload&id=X
	{
		if (empty($_FILES['galleryfiles']))
			return json_encode(['error'=>'No files found for upload.']); 

		$files = $_FILES['galleryfiles'];
		return Elgallery::galleryFilesUpload($id, $files);
	}
	
	public function actionDeleteUploadedFile($id)	// http://tickets/index.php?r=elevator%2file-upload&id=X
	{
		if (empty($_POST['key'])) 
			return json_encode(['error'=>'No files found for delete.']); 
		$fname=$_POST['fname'];
		Elgallery::deleteGalleryFile($_POST['key']);
		$uploadDir = Elgallery::getGalleryDir($id);
		unlink("$uploadDir/$fname");
		return json_encode([]); 
	}
	
	public function actionFile($id, $fname)	// http://tickets/index.php?r=elevator%2file&id=X
	{
		$localDir = Elgallery::getGalleryDir($id);
//	Yii::warning("localDir/fname="."$localDir/$fname",__METHOD__);
		if (!is_file("$localDir/$fname"))
			throw new \yii\web\NotFoundHttpException('The file does not exists.');

		return Yii::$app->response->sendFile("$localDir/$fname",$fname, ['inline'=>true]);		
	}
	
    /**
     * Creates a new Elevator model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Elevator();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Elevator model.
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
        } else {

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Elevator model.
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
     * Finds the Elevator model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Elevator the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = Elevator::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSend()
    {
        $par1 = Yii::$app->request->post('eldevicetype', null);
        $par2 = Yii::$app->request->post('eldistrict', null);
        $par3 = Yii::$app->request->post('elstreettype', null);
        $par4 = Yii::$app->request->post('elstreetname', null);
        $par5 = Yii::$app->request->post('elfacility_id', null);

        $_SESSION['el.eldevicetype']  = $par1;
        $_SESSION['el.eldistrict']    = $par2;
        $_SESSION['el.elstreettype']  = $par3;
        $_SESSION['el.elstreetname']  = $par4;
        $_SESSION['el.elfacility_id'] = $par5;
        
        /*Yii::$app->session->setFlash('success', '_  params:' . nl2br("\n") . 
            '1: ' . $par1 . nl2br("\n") . 
            '2: ' . $par2 . nl2br("\n") .  
            '3: ' . $par3 . nl2br("\n") . 
            '4: ' . $par4 . nl2br("\n") . 
            '5: ' . $par5);*/
        
        //$searchModel = new ElevatorSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 
        //var_dump( Yii::$app->request->queryParams);exit;    
        //index?ElevatorSearch[eldevicetype]=10&ElevatorSearch[eldistrict]=&ElevatorSearch[elstreettype]=&ElevatorSearch[elstreetname]=&ElevatorSearch[elfacility_id]=  
        return $this->redirect([
                'index',
                'ElevatorSearch[eldevicetype]'  => $par1,
                'ElevatorSearch[eldistrict]'    => $par2,
                'ElevatorSearch[elstreettype]'  => $par3,
                'ElevatorSearch[elstreetname]'  => $par4,
                'ElevatorSearch[elfacility_id]' => $par5 ]);
    }

    public function actionSenddrop($id)
    {
        session_start();  


        $par1 = Yii::$app->request->post('Elevator')['eldistrict'];
        $par2 = Yii::$app->request->post('Elevator')['elstreetname'];
        $par3 = Yii::$app->request->post('Elevator')['elfacility_id'];
        
        $_SESSION['dr.fadistrictname'] = $par1;
        $_SESSION['dr.fastreetname'] = $par2;
        $msg  =  'act_par1: '.  $_SESSION['dr.fadistrictname'] . nl2br("\n");
        $msg .=  'act_par2: '.  $_SESSION['dr.fastreetname'] . nl2br("\n");

        /*Yii::$app->session->setFlash('success', '_  params:' . nl2br("\n") . 
            '1: ' . $par1 . nl2br("\n") . 
            '2: ' . $par2 . nl2br("\n") .  
            '3: ' . $par3 . nl2br("\n") . 
            'msg:' .  nl2br("\n") . $msg

            );*/


        return $this->redirect([
                'update',
               'id' => $id, 
                ]);
    }

    public function actionStreetlists($id) //!!
    {
        $mymodel = new Elevator();
        $size= $mymodel->getStreetList($id);

        if($size){
            echo '<option value="">Выберите улицу</option>';
            foreach($size as $key => $value ){
               echo "<option value='" . $key . "'>" . $value . "</option>";               
            }
        }
        else
        {
             echo '<option value="0">"Улица не найдена</option>';
        }
    }

    public function actionBuildlists($id) //!!
    {
        $mymodel = new Elevator ();
        $size= $mymodel->getBuildList($id);

        if($size){
            echo '<option value="">Выберите дом</option>';
            foreach($size as $key => $value){
                echo "<option value='" .$key. "'>".$value."</option>";
            }
        }
        else
        {
             echo '<option value="0">"Дом не найден</option>';
        }
    }

    public function actionUpload($id,$ticode)
    {
      $model = new UploadImage();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload($ticode)) {
                // file is uploaded successfully
                return $this->redirect(['view','id'=>$id]);
            }
        }   
        return $this->redirect(['index']);//$this->redirect(['view','id'=>$id]);
    }
}
