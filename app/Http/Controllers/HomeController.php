<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use File;
use Image;

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
    	return $filename;
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
		// open an image file
		$img = Image::make($file_url)->encode($request->file_target);
		//$img->fit(300);
		$img->save($public_folder.'/'.$new_name);

    	return $thumb_filename;
    }

    private function convert_snd($request)
    {

    }

    private function convert_vid($request)
    {
    	
    }
}