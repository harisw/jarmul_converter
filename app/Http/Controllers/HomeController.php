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
    		$filename = $this->convert_img($request);
    	}
    	else if($file_type == 'snd')
    	{
    		$filename = $this->convert_snd($request);
    	}
    	else
    	{
    		$filename = $this->convert_vid($request);
    	}
    	return $this->result($filename);
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
		return $file_url;
		$new_name = $request->input('name').'.'.$request->input('file_target');
		
//		$new_img = 
		// open an image file
		//$new_img = @imagecreatefromjpeg($file_url);
		//$new_img = imagecreatefromstring($file_url);
		$img = imagetruecolortopalette($request->file('input_file'), true, 4);
		//$img = Image::make($file_url)->encode($request->file_target);
		//$img->fit(300);
		$img->save($public_folder.'/'.$new_name);

    	return $thumb_filename;
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
        return public_path('aud/results/'.$new_name);
    }

    private function convert_vid($request)
    {

    }

    public function result($file_url)
    {
        $data['file_url'] = $file_url;
        return view('result', $data);
    }
}