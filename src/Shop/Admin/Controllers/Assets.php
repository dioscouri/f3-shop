<?php 
namespace Shop\Admin\Controllers;

class Assets extends \Admin\Controllers\BaseAuth 
{
 


 
    public function index() {
    	
    	
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->renderTheme('Shop/Admin/Views::assets/massuploader.php');
    	
 	
    }

public function handleTraditional()
    {
    	
   	
        $app = \Base::instance();
        $files_path = $app->get('TEMP') . "files";
        $chunks_path = $app->get('TEMP') . "chunks";
        
        if (!file_exists($chunks_path)) {
            mkdir( $chunks_path, \Base::MODE, true );
        }
        
        if (!file_exists($files_path)) {
            mkdir( $files_path, \Base::MODE, true );
        }
        
        $uploader = new \Fineuploader\Traditional\Handler;
        
        
        $name =  $uploader->getName();
        
        
        $parts = explode('_', $name); 
        
        $model_number = $parts[0];
        
        $order = (int) explode('.',$parts[1])[0];
        
       	//LOOK FOR A PRODUCT
        $product = (new \Shop\Models\Products)->setCondition('tracking.model_number',$model_number)->getItem();
        if(empty($product->id))  {
        	$result = [];
        	$result['success'] = false;
        	$result['error'] = 'Product not found looking for tracking.model_number = '.$model_number;
        	$result['preventRetry'] = true;
        	
        	echo json_encode($result);
        	exit;
        }
        
        
       
        
        // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $uploader->allowedExtensions = array(); // all files types allowed by default
        
        // Specify max file size in bytes.
        $uploader->sizeLimit = 10 * 1024 * 1024; // default is 10 MiB
        
        // Specify the input name set in the javascript.
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default
        
        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $uploader->chunksFolder = $chunks_path;

        $method = $_SERVER["REQUEST_METHOD"];
        if ($method == "POST") {
            header("Content-Type: text/plain");
        
            // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
            $result = $uploader->handleUpload( $files_path );
        
            // To return a name used for uploaded file you can use the following line.
            $result["uploadName"] = $uploader->getUploadName();
            
            $result["originalName"] = $uploader->getName();
            
            // was upload successful?
            if (!empty($result['success'])) 
            {
                // OK, we have the file in the tmp folder, let's now fire up the assets model and save it to Mongo
                $model = new \Assets\Admin\Models\Assets;
                $db = $model->getDb();
                $grid = $model->collectionGridFS();
                
                // The file's location in the File System
                $filename = $result["uploadName"];
                
                $pathinfo = pathinfo($filename);
                $buffer = file_get_contents( $files_path . "/" . $filename );
                
                $originalname = $result["originalName"];
                $pathinfo_original = pathinfo($originalname);

                $thumb = null;
                if ( $thumb_binary_data = $model->getThumb( $buffer, $pathinfo['extension'] )) {
                    $thumb = new \MongoBinData( $thumb_binary_data, 2 );
                }
                $values = array(
                    'storage' => 'gridfs',
                    'contentType' => $model->getMimeType( $buffer ),
                    'md5' => md5_file( $files_path . "/" . $filename ),
                    'thumb' => $thumb,
                    'url' => null,
           			"title" => \Dsc\String::toSpaceSeparated( $model->inputfilter()->clean( $originalname ) ),
                    "filename" => $originalname,
                );
                                
                if (empty($values['title'])) {
                    $values['title'] = $values['md5'];
                }
                // save the file
                if ($storedfile = $grid->storeFile( $files_path . "/" . $filename, $values )) 
                {
                	$model->load(array('_id'=>$storedfile));
                	$model->bind( $values );
	                $model->{'slug'} = $model->generateSlug();
	                $model->{'type'} = 'shop.assets';
     	            $model->save();
                }
                // $storedfile has newly stored file's Document ID
                $result["asset_id"] = (string) $storedfile;
                $result["slug"] = $model->{'slug'};
                
                \Dsc\Queue::task('\Assets\Models\Storage\CloudFiles::gridfstoCDN', array($result['asset_id'], '/product_images/'.$model_number.'/') );
            } 
            
            
            $product->addImage($model->{'slug'},$order);
            
            
            
            
            
            echo json_encode($result);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        
    }
    
   
}