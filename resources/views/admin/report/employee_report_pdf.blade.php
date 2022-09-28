<!DOCTYPE html>
<html>
<head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
#myitem {
    display: flex;
    justify-content: space-around;
}
h2 {text-align: center;}
p {text-align: center;}
</style>
</head>
<body>


<div id="myitem">
    <?php $image_path = '/uploads/Logo_BanBanHotel.png'; ?>
    <img src="{{ public_path() . $image_path }}" width="200" height="100" class="center">

</div>
<h2>Ban Ban Hotel</h2>
    <p ali>Office Address</p>
    <p>Phone : 017415213</p>
    <p>Email : sinitchou000@gmail.com</p>
    <p> <b>Employee Attendance Report </b> </p>


<!-- <table id="customers">
  <tr>
    <td><h2>


    </h2></td>
    <td>
        <h2>Ban Ban Hotel</h2>
<p>Office Address</p>
<p>Phone : 017415213</p>
<p>Email : sinitchou000@gmail.com</p>
<p> <b>Employee Attendance Report </b> </p>

    </td>
  </tr>


</table> -->
 <br> <br>
 <strong>Attendance : </strong> {{$employee->name}},  <strong> Month : </strong> {{$month}}
 <br> <br>
<table id="customers" >


  <tr style="background-color: #34ace0">
    <td > No  </td>
    <td width="50%"> Checkin Time  </td>
    <td width="50%"> Checkin Status  </td>
    <td width="50%"> Checkout Time </td>
    <td width="50%"> Checkout Status </td>
    <td width="50%"> Date </td>
    <!-- <td width="50%"> Status </td> -->
  </tr>



  <!-- <tr>
    <td width="50%"> 12/05/2022  </td>
    <td width="50%"> Present</td>
  </tr> -->
  @php($i=1)
  @foreach($attendance as $value)
  <tr>
    <td>{{$i++}}</td>
    <td width="50%"> {{ $value->checkin_time}}  </td>
    <td width="50%"> {{ $value->checkin_status}}  </td>
    <td width="50%"> {{ $value->checkout_time }} </td>
    <td width="50%"> {{ $value->checkout_status }} </td>
    <td width="50%"> {{ date('d-m-Y', strtotime($value->date )) }}  </td>
  </tr>
  @endforeach


  <!-- <tr>
    <td colspan="2">
      <strong>Total Absent : </strong> 1 , <strong> Total Leave : </strong> 0
    </td>
  </tr> -->

</table>
<br>
<br>
<strong>Leave : </strong> {{$employee->name}},  <strong> Month : </strong> {{$month}}
<br><br>
<table id="customers">

  <tr style="background-color: #34ace0">
    <td > No  </td>
    <td width="50%"> Reason  </td>
    <td width="50%"> From Date  </td>
    <td width="50%"> To Date </td>
    <td width="50%"> Status </td>
    <!-- <td width="50%"> Status </td> -->
  </tr>

  <!-- <tr>
    <td width="50%"> 12/05/2022  </td>
    <td width="50%"> Present</td>
  </tr> -->
  @php($i=1)
  @foreach($leave as $value)
  <tr>

    <td > {{ $i++}}  </td>
    <td width="50%"> {{ $value->reason}}  </td>
    <td width="50%"> {{ date('d-m-Y', strtotime($value->from_date )) }}  </td>
    <td width="50%"> {{ date('d-m-Y', strtotime($value->to_date )) }}  </td>
    <td width="50%"> {{ $value->status}}  </td>
  </tr>
  @endforeach


  <!-- <tr>
    <td colspan="2">
      <strong>Total Absent : </strong> 1 , <strong> Total Leave : </strong> 0
    </td>
  </tr> -->

</table>
<br> <br>
  <i style="font-size: 10px; float: right;">Print Data : {{ date("d M Y") }}</i>

<!-- <hr style="border: dashed 2px; width: 95%; color: #000000; margin-bottom: 50px"> -->
</body>
</html>
