
 @if($workday=="true")
    @if($checkin_status=="true")
    <div><p style="background: #F1C40F;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;">Checkout</p></div>
    @elseif($checkin_status=="present")
    <div><p style="background: #5DADE2;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;">Present</p></div>
    @elseif($checkin_status=="leave")
    <div><p style="background: #f44336;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;">Permission</p></div>
    @else
    <div></div>
    @endif
@else
<div><p style="background: #f44336;border-radius: 15px;width: 100px;height: 30px;text-align: center;color:#FFFFFF;">Day Off</p></div>
@endif