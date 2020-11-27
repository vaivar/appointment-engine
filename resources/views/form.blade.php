@extends('templates/carcass')

@section('content')
<form action="" method="post">
    <p>
        <label for="name">Vardas, Pavardė</label>
        <input type="text" name="name"></input>
    </p>
    <p>
        <label for="phone">Mob. telefonas</label>
        <input type="text" name="phone"></input>
    </p>
    <p>
        <label for="email">El. paštas</label>
        <input type="text" name="email"></input>
    </p>
    <p>
        <label for="salon">Salonas</label>
        <select name="salon" id="salon" onchange="getDates()">
        @foreach($salons as $salon)
            <option value="{{$salon->id}}">{{$salon->address}}</option>
        @endforeach
        </select>
    </p>
    <p>
        <label for="date">Data</label>
        <input type="text" name="date" id="datepicker"></input>
    </p>
    <p>
        <label for="time">Laikas</label>
        <select name="time" id="time">
            <option value="">--:--</option>
        </select>
    </p>
    <p>
        <label for="message" style="vertical-align:top;">Jūsų žinutė:</label>
        <textarea name="message" rows="4" cols="50"></textarea>
    </p>
    @honeypot
    <button type="submit">Siųsti užklausą</button>
</form>
@foreach ($errors->all() as $error)
<p>{{ $error }}</p>
@endforeach
@if(!empty($status))
<p>{{ $status }}</p>
@endif

@endsection

@section('js')
<script>
let dates = [@foreach($dates as $date)"{{ $date->date }}"@if (!$loop->last),@endif @endforeach];

$(function () {
    $("#time").prop("disabled", true);
    $('#datepicker').datepicker({
        beforeShowDay: function(date) {
            return [dates.includes(extractDateString(date))];
        },
        onSelect: function(dateText) {
           getTimes();
        },
        dateFormat: 'yy-mm-dd',
        defaultDate: 0
    });
});

function extractDateString(date){
    return date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
}

function getDates() {
    $("#datepicker").prop("disabled", true);
    $("#time").prop("disabled", true);
    $("#datepicker").val('');
    $("#time").empty();
    $.get("/ajax/getDatesForSalon", { salon: $("#salon").val() }, function(data) {
        dates = [];
        JSON.parse(data).forEach(o => {
            dates.push(o.date);
            
        });
        $("#datepicker").prop("disabled", false);
    });
}

function getTimes() {
    $("#time").prop("disabled", true);
    $.get("/ajax/getTimesForDate", { salon: $("#salon").val(), date: $("#datepicker").val()}, function(data) {
        $("#time").empty();
        JSON.parse(data).forEach(o => {
            $("#time").append('<option value="' + o.time + '">' + o.time + '</option>');
        });
        $("#time").prop("disabled", false);
    });
}
</script>
@endsection