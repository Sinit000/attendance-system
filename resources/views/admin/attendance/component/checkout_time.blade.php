
@if(!$checkin)
<div></div>
@else
<div>{{$checkin->checkout_time}}</div>
@endif