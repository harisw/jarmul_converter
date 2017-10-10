<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use File;
use Image;
use FFMpeg;

class HomeController extends Controller
{
    public function index()
    {
    	return view('home');
    }

    public function convert(Request $request)
    {
    	$file_type = $request->input('file_type');
    	if($file_type == 'img')
    	{
    		$convert_result = $this->convert_img($request);
    	}
    	else if($file_type == 'snd')
    	{
    		$convert_result = $this->convert_snd($request);
    	}
    	else
    	{
    		$convert_result = $this->convert_vid($request);
    	}
    	return $this->download($convert_result[0], $convert_result[1]);
    }

    private function convert_img($request)
    {	
    	$temp_folder = 'results';
    	$public_folder = public_path('img/'.$temp_folder);

    	$ext = $request->input_file->extension();
    	$filename = str_replace(' ', '_', $request->input('name')).'.'.$ext;
    	$temp_folder = $request->file('input_file')->storeAs($temp_folder, $filename);

		//move file
		$upload = $request->file('input_file')->move($public_folder, $temp_folder);
    	$image = $request->file('input_file');
    	$file_url = 'img/'.$temp_folder;

    	$thumb_filename = 'thumb_'.$filename;
		$new_name = $request->input('name').'.'.$request->input('file_target');
		
        $width = $request->input('width_reso');
        $height = $request->input('height_reso');
        $color = $request->input('color_depth');
        $rate = $request->input('conv_rate');
        $greyscale = $request->input('greyscale');

//		$new_img = 
		// open an image file
		//$new_img = @imagecreatefromjpeg($file_url);
		//$new_img = imagecreatefromstring($file_url);
		//$img = imagetruecolortopalette($request->file('input_file'), true, 4);
		$img = Image::make($file_url);
        if($width && $height)
            $img->resize($width, $height);
        if($greyscale)
            $img->greyscale();
        if($rate)        
            $img->encode($request->file_target, $rate);
		//$img->fit(300);
		$img->save($public_folder.'/'.$new_name);

    	$url = 'img\\results\\';
        $data = [$url, $new_name];
        return $data;
    }

    private function convert_snd($request)
    {
        $temp_folder = 'results';
        $public_folder = public_path('aud/'.$temp_folder);

        $ext = $request->input_file->extension();
        $filename = str_replace(' ', '_', $request->input('name')).'.'.$ext;
        $temp_folder = $request->file('input_file')->storeAs($temp_folder, $filename);

        //move file
        $upload = $request->file('input_file')->move($public_folder, $temp_folder);
        $image = $request->file('input_file');
        $file_url = 'aud/'.$temp_folder;

        $new_name = $request->input('name').'.'.$request->input('file_target'); 
        $ffmpeg = FFMpeg\FFMpeg::create(['timeout' => 3600]);
        // $ffmpeg = \FFMpeg\FFMpeg::create([
        //             'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
        //             'ffprobe.binaries' => '/usr/local/bin/ffprobe' 
        //         ]);
        $audio = $ffmpeg->open($file_url);

        switch ($request->input('file_target')) {
               case 'wav':
                   $format = new FFMpeg\Format\Audio\Wav();
                   break;
               case 'aac':
                   $format = new FFMpeg\Format\Audio\Aac();
                   break;
                case 'mp3':
                    $format = new FFMpeg\Format\Audio\Mp3();
                    break;
                case 'flac':
                    $format = new FFMpeg\Format\Audio\Flac();
                    break;
                case 'vorbis':
                    $format = new FFMpeg\Format\Audio\Vorbis();
                    break;
        }
        $format->on('progress', function ($audio, $format, $percentage) {
            echo "$percentage % transcoded";
        });
        $format->setAudioChannels($request->input('channel'))
               ->setAudioKiloBitrate($request->input('bitrate'));
        $audio->save($format, $public_folder.'/'.$new_name);
        $url = 'aud\\results\\';
        $data = [$url, $new_name];
        return $data;
    }

    private function convert_vid($request)
    {
        $response = new Response();
        $temp_folder = 'results';
        $public_folder = public_path('vid/'.$temp_folder);

        $ext = $request->input_file->extension();
        $filename = str_replace(' ', '_', $request->input('name')).'.'.$ext;
        $temp_folder = $request->file('input_file')->storeAs($temp_folder, $filename);

        //move file
        $upload = $request->file('input_file')->move($public_folder, $temp_folder);
        $image = $request->file('input_file');
        $file_url = 'vid/'.$temp_folder;

        $new_name = $request->input('name').'.'.$request->input('file_target');
        
        // switch ($request->input('file_target')) {
        //     case 'wmv':
        //         $format = new FFMpeg\Format\Video\WMV();
        //         break;
        //     case 'webm':
        //         $format = new FFMpeg\Format\Video\WEBM();
        //         break;
        //     case 'ogg':
        //         $format = new FFMpeg\Format\Video\OGG();
        //         break;
        //     case 'x264':
        //         $format = new FFMpeg\Format\Video\X264();
        //         break;
        // }
        $hframe = $request->input('frame_width');
        $wframe = $request->input('frame_height');
        $bitrate = $request->input('bitrate');
        $framerate = $request->input('frame_rate');
        $channel = $request->input('channel');
        $samplerate = $request->input('sample_rate');

        // $ffmpeg = FFMpeg\FFMpeg::create(['timeout' => 3600]);
        // $video = $ffmpeg->open($file_url);
        
        // try {
        //     if($hframe)
        //         $video->filters()->resize(new FFMpeg\Coordinate\Dimension($hframe, $wframe));
        //     if($framerate)
        //         $video->filters()->framerate(new FFMpeg\Coordinate\FrameRate($framerate), 3);
        // $video->filters()->synchronize();

        // $format->on('progress', function($video, $format, $percentage){
        //     echo "$percentage % transcoded";
        // });
        // if($bitrate)
        //     $format->setKiloBitrate($bitrate);
        // if($channel)
        //     $format->setAudioChannels($channel);
        // $video->save($format, $public_folder.'/'.$new_name);            
        // } catch (Exception $e) {
        //     return redirect('/')->with('status', $e);
        // }
        try {
            exec('ffmpeg -i '.$file_url.' -c:v libx264 '.$new_name);
        } catch (Exception $e) {
            return $response->setStatusCode(500, 'Error!');
        }
        $url = 'vid\\results\\';
        $data = [$url, $new_name];
        return $data;
    }

    public function result()
    {
        return view('result');
    }
    public function download($fileurl, $filename)
    {
        $file_path = public_path($fileurl).$filename;

        if(file_exists($file_path))
        {
            return \Response::download($file_path, $filename);
        }
        else
        {
            exit($file_path);
        }
    }
}