<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{url('upload')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <button type="submit">Upload</button>
        <img src="https://res.cloudinary.com/dxlbihc2u/image/upload/v1663635770/employee/dq16ssufphutbh8owcl9.jpg" alt="" width="100" height="100">
    </form>
</body>
</html>