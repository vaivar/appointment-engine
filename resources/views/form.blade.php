@extends('templates/carcass')

@section('content')
<form action="" method="post">
    <div>
        <label for="name">Vardas, Pavardė</label>
        <input class="rounded-rectangle" type="text" name="name"></input>
    </div>
    <div>
        <label for="phone">Mob. telefonas</label>
        <input class="rounded-rectangle" type="text" name="phone"></input>
    </div>
    <div>
        <label for="email">El. paštas</label>
        <input class="rounded-rectangle" type="text" name="email"></input>
    </div>
    <div>
        <label for="salon">Pasirinkite BTN specialistą</label>
        <select class="rounded-rectangle" name="salon" id="salon" onchange="getDates()">
        <option value="all">Man svarbiau data ir laikas</option>
        @foreach($salons as $salon)
            @if($salon->id != '5')
                <option value="{{$salon->id}}">{{$salon->address}}</option>
            @endif
        @endforeach
        </select>
    </div>
    <div>
      <label for="date">Susitikimo data</label>
      <input
        id="datepicker"
        class="rounded-rectangle"
        type="text"
        name="date"
        placeholder="Pasirinkite susitikimo datą"
      >
      </input>
    </div>
    <div>
        <label for="time">Siūlomas susitikimo laikas</label>
        <select class="rounded-rectangle" name="time" id="time">
            <option value="">--:--</option>
        </select>
    </div>
    <div>
        <label for="message" style="vertical-align:top;">Jūsų žinutė:</label>
        <textarea name="message" rows="5"></textarea>
    </div>
    @honeypot
    <button class="rounded-rectangle" type="submit">Registruotis konsultacijai</button>
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