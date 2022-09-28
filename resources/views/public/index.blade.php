<!doctype html>
<html lang="en-US"class="no-js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>QR Scanner</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>

<body>
    <div class="container" style="margin-top: 20%">
		<div class="row">
            <div class="col-lg-12" style="display: flex; justify-content: center;">
                <h1>Selamat datang di Aplikasi Absensi</h1>
            </div>
		</div>
        <br>
		<div class="row">
            <div class="col-lg-12" style="display: flex; justify-content: center;">
                <div class="form-group">
                    <a href="{{ route('scan.masuk') }}" type="button" id="btn-save" class="btn btn-primary">
                        <i class="fas fa-check"></i>
                        Absen Masuk
                    </a>
                    <a href="{{ route('scan.pulang') }}" type="button" id="btn-save" class="btn btn-primary">
                        <i class="fas fa-check"></i>
                        Absen Pulang
                    </a>
                </div>
            </div>
		</div>
	</div>




	
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</body>
</html>