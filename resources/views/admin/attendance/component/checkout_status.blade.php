
@if(!$checkin)
<div></div>
@else
<div>{{$checkin->checkout_status}}</div>
@endif