<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use frontend\models\Tickets;
use frontend\models\TicketAction;
use frontend\models\UploadImage;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use zxbodya\yii2\galleryManager\GalleryManagerAction;
use frontend\models\Product;

/**
 * Tickets controller
 *	!!!It's an example code for how to pass parameters to view!!!
 *	There are 2 methods to pass parameters to view: 
 *	1.push: pass to view any number of variables - in an associative array (see below how)
 *	2.pull: use in view $this->context to access all members of a controller class (see below how)
 *
 */
class TicketsController extends Controller
{
	/*	2. HOW TO PASS PARAMETERS TO VIEW = PULL !!!
		------------------------------------
		Passing parameters to view using 'pull' method, 		
		It is possible to access the controller's methods and members inside the view, via $this->context
		For example below inside the view may be used such code: $this->context->tilist2 for accessing  the member, 
		which we should initialize before, in class constructor:*/
	/*public $tilist2;
	function __construct($id, $module, $config = []) {
		 parent::__construct($id, $module, $config);	
		 $ticketsModel = new Tickets();
		 $this->tilist2 = $ticketsModel->getTicketsList();
	}*/
	 
    /*public function actionIndex(){
        $tiall = isset($session['ticketsFilterAll']) ? $session['ticketsFilterAll'] : 0;
        $this->redirect(['indexf','tiall' => $tiall]);
        
    }*/
	public function actionIndex()	// http://yii2-advanced-frontend/index.php?r=tickets%2Findex
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        //---Get the filtering conditions from session variable & request
        $session = Yii::$app->session;
        $ticketsFilterAll = Yii::$app->request->get('tiall');
        if( is_null($ticketsFilterAll) ) $ticketsFilterAll = $session['ticketsFilterAll'];
        else if($session['ticketsFilterAll'] != $ticketsFilterAll )$session['ticketsFilterAll'] = $ticketsFilterAll;
        $ticketsFilterAll = is_null($ticketsFilterAll) ? FALSE : $ticketsFilterAll;
        //Yii::warning(Yii::$app->request->isPjax?'!!!!!GOT Pjax!!!!! ':'IndexController got NOT Pjax',__METHOD__);

        //---Get filters
        if( isset( $_REQUEST['district'] ) ) $session['fltrDistrict'] = $_REQUEST['district'];
        if( isset( $_REQUEST['tifindstr'] ) ) $session['fltrTifindstr'] = $_REQUEST['tifindstr'];
        if( isset( $_REQUEST['f_tidevicetype'] ) ) $session['fltrTidevicetype'] = $_REQUEST['f_tidevicetype'];

    	$ticketsModel = new Tickets();
        //$ticketsModel->fltrDistrict = $session['fltrDistrict'];    // Get filtering value for district
        $ticketsModel->district = $session['fltrDistrict'];    // Get filtering value for district
        $ticketsModel->tifindstr = $session['fltrTifindstr'];    // Get filtering value for district
        $ticketsModel->f_tidevicetype = $session['fltrTidevicetype'];    // Get filtering value for device type
    	$provider = $ticketsModel->search($ticketsFilterAll,Yii::$app->request->queryParams);

    	//--- 1. HOW TO PASS PARAMETERS TO VIEW = PUSH!!!
    	//------------------------------------
    	//--- Passing parameters to view using 'push' method, 
    	//--- by passing the data as the second parameter to the view rendering methods,
    	//--- which should be an associative array. 
    	//--- View rendering methods call PHP extract() to import array keys into the local symbol table as variables.
    	//--- Inside the view for this example	 variable $tilist1 will be accessible: 
    	//\Yii::$app->language = 'ru-RU';
        if(Yii::$app->request->isAjax)
            return $this->renderpartial( '_index', ['provider'=>$provider, 'model'=>$ticketsModel,'tiall'=>$ticketsFilterAll ] );
        else 
            return $this->render( 'index', [/*'tilist1' => $ticketsModel->getTicketsList(),*/'provider'=>$provider, 'model'=>$ticketsModel,'tiall'=>$ticketsFilterAll ] );
    }

	public function actionView($id)	// http://tickets/index.php?r=tickets%2Fview&id=X
	{
        $imagemodel = new UploadImage();
        Tickets::setReadFlag($id);
        return $this->render('view', [
            'model' => $this->findModel($id),'imagemodel'=>$imagemodel
        ]);
	}
	public function actionAppoint($tistatus)    // http://tickets/index.php?r=tickets%2Fappoint&id=X&list=X
    {   
        //Yii::warning(Yii::$app->request->post(),__METHOD__);
        $model = new TicketAction();
        $data = Yii::$app->request->post();
        if( isset( $data['ticketId']) && isset($tistatus) ){
            $model->tistatus    = $tistatus;
            $model->tiplannedtimenew = $data['ticketplanneddate'];
            $model->tiiplannedtime = $data['fitterplanneddate'];
            $model->ticketId    = $data['ticketId'];
            $model->tiltext     = $data['tiltext'];
            $model->receiverId  = $data['receiverId'];
            $model->senderId    = $data['senderId'];
            $model->senderdeskId = $data['senderdeskId'];
            $model->servicedeskId= $data['servicedeskId'];
            $model->errorcode = $data['errorcode'];
            $model->tidesk_id = $data['deskId'];

            $model->tioosbegin  = $data['tioosbegin'];
            $model->tioosbegintm= $data['tioosbegintm'];
            $model->tioosend    = $data['tioosend'];
            $model->tioosendtm  = $data['tioosendtm'];
            $model->tioostype_id = $data['tioostypeId'];

            $model->actor = $data['actor'];
            //if( $tistatus=='MASTER_ASSIGN_DATE' )   $model->updateTiplanneddate();
            /*else*/                                    $model->save();
        }
        else {
            echo ('Unknown error in '. __METHOD__.',line '.__LINE__);
        }
        return $this->redirect(['view','id'=>$data['ticketId'],'blk_md_FitterStartStop'=>1]);// Set flag to avoid md_FitterStartStop invocation
    }

    public function actionSpartaddsdate($id,$plannedsdate)
    {
        TicketAction::savespartdate($id,$plannedsdate);
        $this->redirect(['view','id'=>$id]);//print_r($data);
    }
    
    /*--- 171020, DIDENKO, new spare part logic ---*/
    public function actionSpartadd($id)
    {
        if(Yii::$app->request->isPost) {
          $data = Yii::$app->request->post();
          TicketAction::savespart($id,$data);
        }
 
        return $this->runAction('view',['id'=>$id]);
    }

    public function actionSpartdelete($id,$spartid=0)
    {
        if ($spartid > 0){
            TicketAction::deletespart($spartid);
        }
        return $this->runAction('view',['id'=>$id]);
    }

    public function actionGetPartsList($ClassStr='0.0.0')
    {
        $classid = $ClassStr + 0.0;     // Берем только первую цифирь
        $res = json_encode(Tickets::GetPartsList($classid));
        return $res;
    }

    /* Использовалась ранее для получения единиц измерения */
    public function actionGetPartUnit($PartId = '0')
    {
        return Tickets::GetPartUnit($PartId);
    }
    /*--- 171020, DIDENKO, new spare part logic ---*/

    public function actionUpload($id,$ticode)
    {
      $model = new UploadImage();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload($ticode)) {
                // file is uploaded successfully
                return $this->redirect(['view','id'=>$id]);;
            }
        }   
        return $this->redirect(['index']);//$this->redirect(['view','id'=>$id]);
    }

    /**
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
    	$model = new Tickets();
        if (($model->findOne($id)) !== null) {
        	//Yii::warning('1Ticode='.$model->ticode,__METHOD__);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actions()
    {
        return [
           'galleryApi' => [
               'class' => GalleryManagerAction::className(),
               // mappings between type names and model classes (should be the same as in behaviour)
               'types' => [
                    'product' => Product::className()
               ]
           ],
        ];
    }
    /**
     * Ajax controller.Gets POST request, returns html-text
     * @return HTML-text for html-Select with Elevator Error codes for desired group
     */
    public function actionGetErrorCodesListHtml(){
        $result = 'GetErrorCodesListHtml():Error request';
        if(Yii::$app->request->isPost){
            $errorGroupId = Yii::$app->request->post()['ErrorCodeGroup'];
            if ( isset( Yii::$app->request->post()['ErrorCodeGroup'] ))
                $result = Html::dropDownList('errorcode',0,Tickets::getDeviceErrorCodesList4User($errorGroupId),['class'=>'form-control']);
        }
        return $result;
    }

}
    