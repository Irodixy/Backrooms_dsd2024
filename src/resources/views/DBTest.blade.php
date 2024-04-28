<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DB_Test</title>
</head>
<body>
    <h1>Sooo.... </h1>
    <p>This going to be fun!</p>
	<br></br>
	<p>I really enjoy doing this so I hope everything is working as supose to.</p>
	<p>Just let the JSON file inside the form bellow and it should do their thing normally.</p>
	<p>Don't forget to give me some feedback :D</p>
	<br></br><br></br><br></br><br></br>
	<form action="/laravel/DBTest" method="post" enctype="multipart/form-data">
        @csrf
		Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <button type="submit">Upload</button>
    </form>
	</br></br>

</body>
</html>