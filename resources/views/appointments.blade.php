@extends('templates/carcass')

@section('content')
<div id="datepicker"></div>
<p>
    <label for="salons">Salonas:</label>
    <select name="salons" id="salons">
        <option value="0" @if($selectedSalon === '0') selected="selected" @endif>Visi</option>
    @foreach($salons as $salon)
        <option value="{{$salon->id}}" @if($selectedSalon === strval($salon->id)) selected="selected" @endif>{{$salon->address}}</option>
    @endforeach
    </select>
</p>
<p>
<table id="timeslots">
    <tr>
        <th>Vardas, Pavardė</th>
        <th>Mob. telefonas</th>
        <th>El. paštas</th>
        <th>Žinutė</th>
        <th>Salonas</th>
        <th>Data</th>
        <th>Laikas</th>
        <th></th>
    </tr>
    @foreach($appointments as $appointment)
    <tr>
        <td>{{ $appointment->full_name }}</td>
        <td>{{ $appointment->phone }}</td>
        <td>{{ $appointment->email }}</td>
        <td>{{ $appointment->message }}</td>
        <td>{{ $appointment->address }}</td>
        <td>{{ $appointment->date }}</td>
        <td>{{ $appointment->time }}</td>
        <td><button onclick="remove({{ $appointment->id }})">Pašalinti</button></td>
    </tr> 
    @endforeach
</table>
</p>
@if(!empty($status))
<p>{{ $status }}</p>
@endif
@endsection

@section('js')
<script>
let filledDates = [@foreach($filledDates as $fdate) "{{ $fdate->date }}" @if (!$loop->last) , @endif @endforeach];
let selectedDate = '{{ $defaultDate }}';
$(function() {
    $('#datepicker').datepicker({
        beforeShowDay: function(date) {
            return [true, filledDates.includes(extractDateString(date)) ? 'dateFilled': ''];
        },
        onSelect: function(dateText) {
            window.location.href = '/appointments/' + dateText;
        },
        dateFormat: 'yy-mm-dd',
        defaultDate: @if(!empty($defaultDate)) '{{ $defaultDate }}' @else 0 @endif
    });
});
function extractDateString(date){
    return date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
}
$('#salons').change(function() {
    window.location.href = '/appointments/' + selectedDate + "/" + $(this).val().toString();
});
function remove(id) {
    window.location.href = '/appointments/' + selectedDate + "/" + id + "/delete";
}
</script>
@endsection