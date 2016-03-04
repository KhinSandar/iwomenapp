<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AppBaseController extends Controller
{
    /**
     * Validate request for current resource
     *
     * @param Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     */
    public function validateRequestOrFail($request, array $rules, $messages = [], $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            return $this->getFirstMessage($validator, $rules);
        }
    }

    public function getFirstMessage($validator, array $rules){
        foreach ($rules as $rule => $value) {
            if($validator->errors()->has($rule))
                return Response::json($validator->errors()->first($rule), 400);
        }
    }

    public function makeResponse($result, $message)
    {
        $result['message'] = $message;
        return $result;
    }

    public function sendResponse($result, $message)
    {
        return Response::json($this->makeResponse($result, $message));
    }

    public function uploadImage($file, $path, $resize = [400, 200, 100]){
        $filename = "photo_".date('YmdHis')."_".rand().$file->getClientOriginalName();;
        $file_path = public_path().$path;
        $file->move($file_path, $filename);
        $size = getimagesize($file_path.$filename);
        switch(strtolower($size['mime']))
        {
            case 'image/png':
                $source_image = imagecreatefrompng($file_path.$filename);
                break;
            case 'image/jpeg':
                $source_image = imagecreatefromjpeg($file_path.$filename);
                break;
            case 'image/gif':
                $source_image = imagecreatefromgif($file_path.$filename);
                break;
            default: die('image type not supported');
        }
        $resize_url = [];
        foreach ($resize as $key => $value) {
            $width      = $value; 
            $height     = round($width*$size[1]/$size[0]);
            $photoX     = ImagesX($source_image);
            $photoY     = ImagesY($source_image);
            $images_fin = ImageCreateTrueColor($width, $height);
            ImageCopyResampled($images_fin, $source_image, 0, 0, 0, 0, $width+1, $height+1, $photoX, $photoY);
            if(!file_exists($file_path.'x'.$value)){
                mkdir($file_path.'x'.$value);
            }
            $resize_url[] = "http://api.iwomenapp.org".$path.'x'.$value.'/'.$filename;
            ImageJPEG($images_fin, $file_path.'x'.$value.'/'.$filename, 100);
        }
        ImageDestroy($source_image);
        ImageDestroy($images_fin);
        return ['__type'=>'File', 'name' => $filename, 'url'=>"http://api.iwomenapp.org".$path.$filename,'resize_url' => $resize_url];
    }

    public function uploadAudio($file, $path){
        $filename = "audio_".date('YmdHis')."_".rand().$file->getClientOriginalName();
        $file_path = public_path().$path;
        $file->move($file_path, $filename);

        return 'http://api.iwomenapp.org'.$path.$filename;
    }

    public function uploadVideo($file, $path){
        $filename = "video_".date('YmdHis')."_".rand().$file->getClientOriginalName();
        $file_path = public_path().$path;
        $file->move($file_path, $filename);

        return 'http://api.iwomenapp.org'.$path.$filename;
    }

    public function uploadAPK($file, $path){
        $filename = "apk_".date('YmdHis')."_".rand().$file->getClientOriginalName();
        $file_path = public_path().$path;
        $file->move($file_path, $filename);

        return 'http://api.iwomenapp.org'.$path.$filename;
    }
}