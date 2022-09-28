<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Rekap Absensi</title>
    <style>
        @page {
            size: A4;
        }
    </style>
  </head>
  <body >
        <div class="container">
            <br>
            <h1>Rekap Absensi</h1>
            <br>
            <div class="table-responsive">
                <table class="table table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Time in</th>
                            <th>Time out</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1
                        @endphp
                        @foreach ($data as $item )
                            <tr>
                                <th>{{ $no++ }}</th>
                                <th>{{ $item->user->nip }}</th>
                                <th>{{ $item->user->name }}</th>
                                <th>{{ $item->date }}</th>
                                <th> 
                                    @if ($item->time_in == null)
                                       -
                                    @else  
                                    {{ $item->time_in }}
                                    @endif
                                </th>
                                <th> 
                                    @if ($item->time_out == null)
                                       -
                                    @else  
                                    {{ $item->time_out }}
                                    @endif
                                </th>
                                <th>{{ $item->status }}</th>
                                <th> 
                                    @if ($item->catatan == null)
                                       -
                                    @else  
                                    {{ $item->catatan }}
                                    @endif
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>




    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>