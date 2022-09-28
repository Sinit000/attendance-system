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
    <div class="container-fluid">
		<div class="row mt-5">
            <div class="col-lg-12" style="display: flex; justify-content: center;">
                <video id="preview" class="p-1 border" style="width:50%; height: 100%;"></video>
            </div>
		</div>
        <br>
		<div class="row">
            <div class="col-lg-12" style="display: flex; justify-content: center;">
                <div class="form-group">
                    <input type="text" class="form-control" id="nip" name="nip"
                        placeholder="NIP Karyawan" readonly>
                </div>
            </div>
            <div class="col-lg-12" style="display: flex; justify-content: center;">
                <div class="form-group">
                    <button onclick="btnSubmit()" id="btn-save" class="btn btn-primary">
                        <i class="fas fa-check"></i>
                        Pulang
                    </button>
                </div>
            </div>
		</div>
	</div>




	
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="{{ asset('backend/modules/sweetalert/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false });
        scanner.addListener('scan',function(content){
            // alert(content);
            $('#nip').val(content);
        });
        Instascan.Camera.getCameras().then(function (cameras){
            if(cameras.length>0){
                scanner.start(cameras[0]);
            }else{
                console.error('No cameras found.');
                alert('No cameras found.');
			}
        }).catch(function(e){
            console.error(e);
            alert(e);
        });
    </script>
    <script>
        function btnSubmit(){
            var id = $('#nip').val();
            ajaxurl = '{{ route("out", "id") }}'
            $.ajax({
                type: 'GET',
                url: ajaxurl,
                data: {
                    id: id,
                },
                success: function(data) {
                    if (data.message === 'Anda sudah melakukan absen pulang hari ini') {
                        swal({
                            title: "Maaf!",
                            text: "Anda sudah melakukan absen pulang hari ini",
                            icon: "error",
                            timer: 3000
                        }); 
                    } else {
                        swal({
                            title: "Berhasil!",
                            text: "Data berhasil disimpan",
                            icon: "success",
                            timer: 3000
                        }); 
                    }
                },
                error: function(data) {
                    console.log("Error: ",data)
                }
            });
        }
    </script>
</body>
</html>