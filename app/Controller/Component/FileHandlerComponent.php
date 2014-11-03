<?php  
/**
 * THIS COMPONET IS USED FOR ALL FILE RELATED OPERATIONS AND FILE MANAGEMENT
 * @DATE 08/10/2013 bY DEEMTECH- 36
 */

class FileHandlerComponent extends Component {
public $uses = array('File');

 public function read($path){ 
 
 if (@filesize( $path ) > 0 ) {	
		$f = fopen($path, 'r');
		$content = fread($f, filesize($path));
 return $content;
 }
return NULL ;	
 }
 function FileHandlerComponent(){
    $Files=ClassRegistry::init("File");
 	$this->File =$Files;
 }
 

 public function write($path, $content){

 	if ( is_writeable( $path ) ) {
		//is_writable() not always reliable, check return value. see comments @ http://uk.php.net/is_writable
		$f = fopen( $path, 'w+' );
		if ( $f !== false ) {
			fwrite( $f, $content );
			fclose( $f );
		return TRUE;
		}
	}
	
	return FALSE;
}


/*
 * $FILE contain $_FILE object
 * $path relation directory for saving
 * $replace => boolean , target will replaced or rename. 
 **/


public function file_upload_save($uid,$FILE,$path,$replace=false,$status=FALSE){
		if(!empty($FILE) && !$FILE['error']){

				$temp_name = $FILE['tmp_name'];
				$destination = WWW_ROOT. $path;
				$image_name =$this->GetFileName( $FILE['name'], $path ,$replace);
		        
				@mkdir($destination, 0777, true);//create folder if not exist
   
				if(move_uploaded_file($temp_name,$destination.$image_name))	{
					 
	 
					 $file =array();
					
					$file['user_id']= $uid;
					$file['filename']= $FILE['name'];
					$file['path']= $path.$image_name;
					$file['filemime']= $FILE['type'];
					$file['filesize']= $FILE['size'];
					$file['status'] = $status;

					$this->File->set($file );
	                $this->File->save();
					
					@unlink($temp_name);
				
					return  $this->File->id;
				}
				else{
					
				return 0;	
				}
					
				
			}
				
	return  0;
}

public function fileChangeStatus($id,$status){
	$file= $this->File->findById($id);
	if($file){
	$file['File']['status'] = $status;
	return $this->File->save($file);
	}
	return false;
}

public function file_data_save($uid,$data,$path,$replace=false ,$ext='txt'){

	$destination = SITE_URL. $path;
	
	$file_name = time().'.'.$ext;
    $file_name =$this->GetFileName( $file_name, $path ,$replace);
  
     $destination = WWW_ROOT. $path.$file_name; 
     
    $tmp_name_path = tempnam(realpath(TMP), 'file'); 

    //file_put_contents($tmp_name_path,$data);

  if (!$fp = fopen($tmp_name_path, 'wb')) {
    return 0;
  }
 
  fwrite($fp, $data);
  fclose($fp);

 /* $tmp_name =basename($tmp_name_path);  
  

 $destination = WWW_ROOT. $path;
 if(move_uploaded_file($tmp_name_path,$destination.$file_name))	{
 	 pr($destination,1);
 }else{
 	
 	die('Error occurs');
 }
   

//    @chmod($dest, 0664);
    unlink($tmp_name);
    unlink($tmp_name_path);*/
    


}
public function file_load($fid) {
 	
	$file=$this->File->find("first", array("conditions" => array("File.id" => $fid)));
	 return isset($file['File'])?$file['File']:$file ;
 }

 
 public function file_delete($fid) {
 	
	$file=$this->File->find("first", array("conditions" => array("File.id" => $fid)));
	if(count($file)){
		if($this->File->delete($fid)){
			@unlink(WWW_ROOT.$file['File']['path']);
			return true;
		}
		
	}
		return false;
 }	
 
function GetFileName($file_name,$path,$replace){
	
	if(!$replace){
		$count = 0;
		$arr_exe = explode(".",$file_name);
		$exe =end($arr_exe);
		while(is_file(WWW_ROOT.$path.$file_name))
		 {
						$count++;
						$file_name = $arr_exe[0]."_".$count.".".$exe;
		} 
	}
	return $file_name;
}

function _cron_file_delete($limit=10){
	
	$ids=$this->File->find('list',array('conditions'=>array('status'=>'0'),'limit'=>$limit));	
	foreach ($ids as $fid){
	$this->file_delete($fid);	
	}
	
}
 
 
}
?>
