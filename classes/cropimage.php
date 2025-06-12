<?php
class GetBookingsWPCropImage{

 	var $imgSrc,$myImage,$cropHeight,$cropWidth,$x,$y,$thumb;  
	
	function __construct( )
	{
	  
	}
	
	function setDimensions ($x, $y, $width, $height)
	{
	   $this->cropWidth   = $width; 
	   $this->cropHeight  = $height;						 
	   $this->x = $x;
	   $this->y = $y;
	
	}
	
	function setImage($image)
	{
	
	//Your Image
	   $this->imgSrc = $image; 
						 
	//getting the image dimensions
	   list($width, $height) = getimagesize($this->imgSrc); 
	   $extorig=substr ($this->imgSrc, -3); 
	   
	    $ext=strtolower($extorig);
		 switch($ext)
		  {
		   case 'png' : $this->myImage = imagecreatefrompng($this->imgSrc);
		   break;
		   case 'jpg' : $this->myImage = imagecreatefromjpeg($this->imgSrc);
		   break;
		   case 'jpeg' : $this->myImage = imagecreatefromjpeg($this->imgSrc);
		   break;
		   case 'gif' : $this->myImage = imagecreatefromgif($this->imgSrc);
		   break;
		  }
				  

				
		 if($width > $height) $biggestSide = $width; //find biggest length
		   else $biggestSide = $height; 
				 
	} 
	
	function createThumb()
	{
						
	  $thumbSizeW = $this->cropWidth; // will create a 250 x 250 thumb
	  $thumbSizeH = $this->cropHeight; // will create a 250 x 250 thumb
	  $this->thumb = imagecreatetruecolor($thumbSizeW, $thumbSizeH); 
	  

	  /* fix PNG transparency issues */                       
	 imagefill($this->thumb, 0, 0, IMG_COLOR_TRANSPARENT);         
	 imagesavealpha($this->thumb, true);      
	 imagealphablending($this->thumb, true); 
	
	 imagecopyresampled($this->thumb, $this->myImage, 0, 0,$this->x, $this->y, $thumbSizeW, $thumbSizeH, $this->cropWidth, $this->cropHeight); 
	  
	  
	  
	} 
	
	function renderImage($dest)
	{
		$info = pathinfo($dest);
        $ext = $info['extension'];
		$ext=strtolower($ext);
		
	   
	 switch($ext)
                  {
                   case 'png' : $img = imagepng($this->thumb ,$dest,9);
                   break;
                   case 'jpg' : $img = imagejpeg($this->thumb ,$dest,100);
                   break;
                   case 'jpeg' : $img = imagejpeg($this->thumb ,$dest,100);
				  // case 'jpeg' : $img = imagejpeg($this->thumb ,$dest);
                   break;
                   case 'gif' : $img = imagegif($this->thumb,"$dest");
                   break;
                  }

	  imagedestroy($this->thumb); 
	}
	
}  
$key = "imagecrop";
$this->{$key} = new GetBookingsWPCropImage();