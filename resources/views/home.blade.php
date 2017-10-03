<!DOCTYPE html>
<html>
<head>
	<title>Converter Jarmul</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
	<form action="{{url('/convert')}}" method="POST" enctype="multipart/form-data">
		{{csrf_field()}}

		<div class="form-control">
			<input type="text" name="name" placeholder="Masukkan Nama file disini">
		</div>
		<div class="form-control">
		<select id="file_type" name="file_type" onchange="set_target();">
			<option value="img">Image</option>
			<option value="snd">Sound</option>
			<option value="vid">Video</option>
		</select>
		</div>
		<br><br><br>
		<div class="form-control">
		<input type="file" name="input_file">
		</div>
		<br><br>
		<div class="form-control">
			<select name="file_target" id="file_target">
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
		<br><br>
		<button type="submit" class="btn btn-success">Convert!!</button>
	</form>
</body>
	<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
		function set_target(){
			type_src = document.getElementById('file_type').value;
			if(type_src == 'img')
			{
				code = `<option value="bmp">Bitmap</option><option value="sound">Sound</option>
						<option value="vid">Video</option>`;
			}
			else if(type_src == 'snd')
			{
				code = `<option value="flac">FLAC</option><option value="alac">ALAC</option>
						`;
			}
			else
			{
				code = `<option value="img">Image</option><option value="sound">Sound</option>
						<option value="vid">Video</option>`;
			}
			$('#file_target').html(code);
		}
	</script>
</html>