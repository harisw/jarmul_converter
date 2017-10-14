<!DOCTYPE html>
<html>
<head>
	<title>Converter Jarmul</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">WebSiteName</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Home</a></li>
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Page 1
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="#">Page 1-1</a></li>
          <li><a href="#">Page 1-2</a></li>
          <li><a href="#">Page 1-3</a></li>
        </ul>
      </li>
      <li><a href="#">Page 2</a></li>
      <li><a href="#">Page 3</a></li>
    </ul>
  </div>
</nav>
	<form action="{{url('/convert')}}" method="POST" enctype="multipart/form-data">
		{{csrf_field()}}
		<div class="row">
			<div class="col-md-12 form-container">
        <label>Nama File</label><br>
        <input type="text" class="form-control" placeholder="Masukkan nama File yang diinginkan" name="name">
      </div>
      <div class="col-md-12 form-container">
      	<label>Tipe File</label><br>
      	<select id="file_type" name="file_type" class="selectpicker" onchange="set_target()">
      		<option value="img">Image</option>
					<option value="snd">Sound</option>
					<option value="vid">Video</option>		
      	</select>
      </div>
      <div class="col-md-12 form-container">
      	<label>Input File</label>
      	<input type="file" name="input_file" class="form-control">
      </div>
      <div class="col-md-12 form-container">
      	<label>Tipe File</label><br>
      	<select class="selectpicker" name="file_target" id="file_target">
    				<option value="jpg">JPG</option>
					<option value="png">PNG</option>
					<option value="gif">GIF</option>
					<option value="tif">TIF</option>
					<option value="bmp">BMP</option>
					<option value="ico">ICO</option>
					<option value="webp">WEBP</option>
					<option value="psd">PSD</option>		
      	</select>
      </div>
			<div id="conversion_var">
				<div class="col-md-12 form-container row">
					<label>Resolution</label>
					<div class="col-md-6">
						<input type="text" name="width_reso" placeholder="Width Resolution" min="0" max="2000">
					</div>
					<div class="col-md-6">
						<input type="text" name="height_reso" placeholder="Height Resolution" min="0" max="2000">
					</div>
				</div>
	      <div class="col-md-12 form-container">
	      	<label>Color Depth (in bit)</label><br>
	      	<select class="selectpicker" name="col_depth" id="col_depth">
	    			<option value="1">1</option>
						<option value="2">2</option>
						<option value="4">4</option>
						<option value="8">8</option>
						<option value="16">16</option>
	      	</select>
	      </div>
				<div class="col-md-12 form-container">
					<label>Conversion Rate</label>
					<input type="number" name="conv_rate" placeholder="Conversion/Compression rate in percent" min="1" max="100">
				</div>
			</div>
		<br><br>
		<button type="submit" class="btn btn-success">Convert!!</button>
		</div>
	</form>
</body>
	<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
		function set_target(){
			type_src = document.getElementById('file_type').value;
			if(type_src == 'img')
			{
				code = `<option value="jpg">JPG</option>
					<option value="png">PNG</option>
					<option value="gif">GIF</option>
					<option value="tif">TIF</option>
					<option value="bmp">BMP</option>
					<option value="ico">ICO</option>
					<option value="webp">WEBP</option>
					<option value="psd">PSD</option>`;
				var_code = `<div class="col-md-12 form-container row">
					<label>Resolution</label>
						<div class="col-md-6">
							<input type="text" name="width_reso" placeholder="Width Resolution" min="0" max="2000">
						</div>
						<div class="col-md-6">
							<input type="text" name="height_reso" placeholder="Height Resolution" min="0" max="2000">
						</div>
					</div>
		      <div class="col-md-12 form-container">
		      	<label>Color Depth</label><br>
		      	<select class="selectpicker" name="col_depth" id="col_depth">
		    			<option value="1">1</option>
							<option value="2">2</option>
							<option value="4">4</option>
							<option value="8">8</option>
							<option value="16">16</option>
		      	</select>
		      </div>
					<div class="col-md-12 form-container">
						<label>Conversion Rate</label>
						<input type="number" name="conv_rate" placeholder="Conversion/Compression rate in percent" min="1" max="100">
					</div>`;
			}
			else if(type_src == 'snd')
			{

				code = `<option value="wav">WAV</option><option value="mp3">MP3</option>
						<option value="m4a">AAC(M4A)</option><option value="flac">FLAC</option><option value="ogg">OGG</option>`;
				var_code = `<div class="col-md-12 form-container">
					<label>Bitrate</label>
							<input type="text" name="bitrate" placeholder="Audio Bitrate" class="form-control">
					</div>
					<div class="col-md-12 form-container">
						<label>Channel</label>
						<input type="text" name="channel" placeholder="Audio Channel">
					</div>
					<div class="col-md-12 form-container">
						<label>Audio Sample Rate</label>
						<select class="selectpicker" name="sample_rate" id="sample_rate">
    						<option value="8">8kHz</option>
							<option value="11">11kHz</option>
							<option value="22">22kHz</option>
							<option value="32">32kHz</option>
							<option value="44">44kHz</option>
							<option value="48">48kHz</option>
							<option value="64">64kHz</option>		
      					</select>					
					</div>`;
			}
			else
			{
				code = `<option value="wmv">WMV</option><option value="mkv">MKV</option>
						<option value="mov">MOV</option><option value="mp4">MP4(x264)</option>
						<option value="mpeg">MPEG</option>`;
				var_code = `<div class="col-md-12 form-container row">
					<label>Frame Size</label>
						<div class="col-md-6">
							<input type="text" name="frame_width" placeholder="Frame Width">
						</div>
						<div class="col-md-6">
							<input type="text" name="frame_height" placeholder="Frame Height">
						</div>
					</div>
					<div class="col-md-12 form-container">
					<label>Bitrate</label>
							<input type="text" name="bitrate" placeholder="Bitrate" class="form-control">
					</div>
					<div class="col-md-12 form-container">
					<label>Frame Rate</label>
						<input type="text" name="frame_rate" placeholder="Frame Rate" class="form-control">
					</div>
					<div class="col-md-12 form-container">
						<label>Channel</label>
						<input type="text" name="channel" placeholder="Audio Channel">
					</div>
					<div class="col-md-12 form-container">
						<label>Audio Sample Rate</label>
						<input type="text" name="sample_rate" placeholder="Audio Sample Rate">
					</div>`;
			}
			$('#file_target').html(code);
			$('#conversion_var').html(var_code);
		}
	</script>
</html>