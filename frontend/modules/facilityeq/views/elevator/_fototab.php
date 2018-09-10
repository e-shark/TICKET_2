<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\db\Query;
use frontend\modules\facilityeq\models\Elgallery;
use kartik\file\FileInput;

?>

<div class="tickets-_uploadtab">

<?php
	$id = $model['id'];
	$galleryfiles = Elgallery::getGalleryFiles($id);
	$iniCount = count($galleryfiles);

	$pluginOptions = [ 
		'append'=>true,
		'allowedFileTypes'=>['image', 'html', 'text', 'video', 'audio', 'flash', 'object', 'office'],
//		'showRemove'=>false,
		'previewFileType' => 'any',
		'initialPreviewCount'=> $iniCount,
		'overwriteInitial'=> false,
//		'uploadAsync'=>true,
		'uploadUrl' => Url::toRoute(['elevator/file-upload', 'id' =>$id]),
		'maxFileCount' => 8
	] + 
	Elgallery::setFileInputInitialPreview($id, $galleryfiles);
//Yii::warning("pluginOptions = ".print_r($pluginOptions, true),__METHOD__);
	echo FileInput::widget([
    'name' => 'galleryfiles[]',
    'options' => [
		'multiple' => true,
		'showPreview'=> false
		],
	'pluginOptions' => $pluginOptions,
	]);

?>
</div>
