
@if(!$checkin)
<div></div>
@else
<div>{{$checkin->checkin_status}}</div>
@endif