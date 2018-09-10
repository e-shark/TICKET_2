<?php

namespace frontend\modules\facilityeq\models;
use Yii;
use yii\base\Model;
use yii\helpers\Url;

class Elgallery extends Model
{
	const MAXTEXTPREVIEWSIZE = 1000; 
	//const SERVER_DNS = 'http://adsl-medcentr.hit.kharkov.ua';
	const SERVER_DNS = 'http://undef-sg2-kh.maxnet.ua';
	
	static public function saveGalleryFiles($id, &$finfo)
	{
		for($i=0; $i < count($finfo); $i++){
			$info = $finfo[$i];
			$sql = "INSERT INTO elevator_gallery (elevator_id, userid, fname, fclientname,size) VALUES ($id,".Yii::$app->user->id. ",'".$info['fname']."','".$info['fclientname']."','".
			$info['size']."');";
			if(FALSE === Yii::$app->db->createCommand($sql)->execute()) 	return FALSE;
			$finfo[$i]['id'] = Yii::$app->db->getLastInsertID();
		}
		return TRUE; 
	} 

	static public function deleteGalleryFile($id)
	{
		$sql = "DELETE FROM elevator_gallery WHERE id = $id";
		Yii::$app->db->createCommand($sql)->execute();
	} 

	static public function getGalleryFiles($id)
	{
		$sql4galleryfiles=
			'SELECT * FROM elevator_gallery where elevator_id='.$id;
		$galleryfiles = Yii::$app->db->createCommand($sql4galleryfiles)->queryAll();
		return $galleryfiles;
	} 
	
	public static function getGalleryDir($id)
	{
		$uploadDir=Yii::GetAlias('@AppDataStore/Equipment');
		$uploadDir = "$uploadDir/E_$id";
		if(!is_dir($uploadDir)) {
			mkdir($uploadDir);
		}
		
		return $uploadDir;
	}

	public static function getGalleryFileUrl($id, $galleryfile){
		$fname = $galleryfile['fname'];
		$path_parts = pathinfo($fname);
		$ext = $path_parts['extension']; 
		$is_encode = $ext=='doc' || $ext=='docx' || $ext=='xls' || $ext=='xlsx';

		$url = Url::toRoute(['elevator/file', 'id' => $id,
		'fname'=> $fname]); 		
		return $is_encode ? urlencode($url) : $url;	
	}
	
	public static function setFileInputInitialPreview ( $id, $galleryfiles )
	{
		$iniFiles = ($galleryfiles !== FALSE) ? array_column($galleryfiles,'fname') : [];
		$iniCount = count($galleryfiles);
		$iniConfig =[];
		$urlDelete = Url::toRoute(['elevator/delete-uploaded-file', 'id' =>$id]);
		$uploadDir = self::getGalleryDir($id);

		for($i=0; $i < $iniCount; $i++){
			$galleryfile = $galleryfiles[$i];
			$extra = $iniFiles[$i];
			$ext = end(explode('.', $extra));
			$type = "image";
			$iniFiles[$i] = self::getGalleryFileUrl($id, $galleryfile);
			if($ext=='doc' || $ext=='docx' || $ext=='xls' || $ext=='xlsx') {
				$type = "office";
				$iniFiles[$i] = self::SERVER_DNS.$iniFiles[$i];
			}
			else if($ext=='txt'){
				$type = "text";
				$length = min(self::MAXTEXTPREVIEWSIZE,$galleryfile['size']);
				$iniFiles[$i]=file_get_contents("$uploadDir/$extra",FALSE, NULL, 0, $length);
				
				if ( mb_detect_encoding($iniFiles[$i],'cp1251, UTF-8') == 'Windows-1251')
					$iniFiles[$i]=iconv('cp1251','UTF-8',$iniFiles[$i]);
			}
			else if($ext=='pdf') $type = "pdf";
			else if($ext=='mp4') $type = "video";

			$iniConfig[$i] = ['type'=>$type, 'caption'=>$galleryfiles[$i]['fclientname'], 'size' => $galleryfiles[$i]['size'], 'url'=>$urlDelete, 
			'key'=>$galleryfiles[$i]['id'], 
			'extra'=>['fname'=>$extra]];
			if( $type == "video") $iniConfig[$i] += ['filetype' => 'video/mp4', 'filename'=> $galleryfiles[$i]['fclientname']];
		}

		return [ 
			'initialPreviewAsData'=> true, 
			'initialPreview'=> $iniFiles,
			'initialPreviewConfig'=>$iniConfig,
		];
	}

	public static function galleryFilesUpload($id, $files)	{
		// a flag to see if everything is ok
		$success = true;

	// file info to store
		$finfo = [];
		$filenames = $files['name'];

		$uploadDir = self::getGalleryDir($id);
		for($i=0; $i < count($filenames); $i++){
			$path_parts = pathinfo($filenames[$i]);
			$fname = $filenames[$i];
			$target =  "$uploadDir/$fname";
			$nreps = 1;
			while(file_exists($target)){
				$fname = $path_parts['filename']."($nreps).".$path_parts['extension'];
				$target =  "$uploadDir/$fname";
				$nreps++;
		}

			if(move_uploaded_file($files['tmp_name'][$i], $target)) {
				$finfo[] = ['id'=>0,'fname'=>$fname, 'fclientname'=>$fname,'size'=>$files['size'][$i]];
			} else {
				$success = false;
				break;
			}
		}

		if(!$success || !self::saveGalleryFiles($id, $finfo))
			$success = false;

		if ($success === true) {
					
			$output = ['append'=>true, 'showUpload'=>false,] + self::setFileInputInitialPreview($id, $finfo);
//	$output = [];
		} elseif ($success === false) {
			$output = ['error'=>'Error while uploading gallery files. Contact the system administrator'];
    // delete any uploaded files
			foreach ($finfo as $info) {
				$target = $info['fname'];
				unlink("$uploadDir/$target");
			}
		} else {
			$output = ['error'=>'No files were processed.'];
		}
		return json_encode($output);
	}
} 

