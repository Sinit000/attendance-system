@if(!$checkin)
<div></div>
@else
    @if($checkin->checkout_status=='early')
        <div><p style="background: #f44336;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;">{{$checkin->checkout_late}}</p></div>  
    @else
    <div>{{$checkin->checkout_late}}</div>
    @endif
@endif