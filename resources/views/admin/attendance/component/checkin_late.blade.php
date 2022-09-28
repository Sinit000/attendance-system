@if(!$checkin)
<div></div>
@else
    @if($checkin->checkin_status=='late')
        <div><p style="background: #f44336;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;">{{$checkin->checkin_late}}</p></div>  
    @else
    <div>{{$checkin->checkin_late}}</div>
    @endif
@endif