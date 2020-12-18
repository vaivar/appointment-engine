@extends('templates/carcass')

@section('content')
<div id="datepicker"></div>
<p>
    <label for="salons">BTN specialistas:</label>
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
        <th>BTN specialistas</th>
        <th>Laikas</th>
        <th>Užimtos vietos</th>
        <th>Vietos</th>
        <th></th>
    </tr>
    @foreach($timeslots as $timeslot)
    <tr>
        <td>{{ $timeslot->address }}</td>
        <td>{{ $timeslot->time }}</td>
        <td>{{ $timeslot->slots_occupied }}</td>
        <td>{{ $timeslot->slots_total }}</td>
        <td><button onclick="edit({{ $timeslot->id }})">Redaguoti</button>&nbsp;<button onclick="remove({{ $timeslot->id }})" @if($timeslot->slots_occupied > 0) disabled @endif>Pašalinti</button></td>
    </tr> 
    @endforeach
</table>
</p>
<button onclick="addTimeslot()">Pridėti</button>
@if(!empty($status))
<p>{{ $status }}</p>
@endif
@endsection

@section('js')
<script>
let filledDates = [@foreach($filledDates as $fdate) "{{ $fdate->date }}" @if (!$loop->last) , @endif @endforeach];
let selectedDate = '{{ $defaultDate }}';
$(function () {
    $('#datepicker').datepicker({
        beforeShowDay: function(date) {
            return [true, filledDates.includes(extractDateString(date)) ? 'dateFilled': ''];
        },
        onSelect: function(dateText) {
            window.location.href = '/timetable/' + dateText;
        },
        dateFormat: 'yy-mm-dd',
        defaultDate: @if(!empty($defaultDate)) '{{ $defaultDate }}' @else 0 @endif
    });
});
function extractDateString(date){
    return date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
}
$('#salons').change(function() {
    window.location.href = '/timetable/' + selectedDate + "/" + $(this).val().toString();
})
function addTimeslot() {
    window.location.href = '/timeslot/' + selectedDate + "/new";
}
function edit(id) {
    window.location.href = '/timeslot/' + selectedDate + "/" + id + "/edit";
}
function remove(id) {
    window.location.href = '/timeslot/' + selectedDate + "/" + id + "/delete";
}
</script>
@endsection