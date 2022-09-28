@if($workday=="true")
    @if($checkin_status=="true")
    <div><button type="button" class="btn btn-info" id="checkout" data-id="{{ $checkin_id }}">Checkout</button></div>
    @elseif($checkin_status=="present")
    <div></div>
    <!-- <div><button type="button" class="btn btn-danger">Present</button></div> -->
    @elseif($checkin_status=="leave")
    <div></div>
    @else
    <div>
        <div><button type="button" class="btn btn-success"  id="checkin" data-id="{{ $id }}">Checkin</button></div>
    </div>
    @endif
@else
<div></div>
@endif