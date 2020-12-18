@extends('templates/carcass')

@section('content')
<form action="" method="post">
    <p>
        <label for="date">Data:</label>
        <input type="text" value="{{ $defaultDate }}" disabled>
    </p>
    <p>
        <label for="salon">BTN Specialistas:</label>
        <select name="salon">
        @foreach($salons as $salon)
            <option value="{{$salon->id}}" @if($salon->id === ($selectedSalon ?? '')) selected="selected" @endif>{{$salon->address}}</option>
        @endforeach
        </select>
    </p>
    <p>
        <label for="slots">Vietos:</label>
        <input type="text" name="slots" value="{{ $slots ?? '' }}">
    </p>
    <p>
        <label for="times" style="vertical-align:top;">Laikai:</label>
        <textarea name="times" rows="4" cols="50">{{ $time ?? ''}}</textarea>
    </p>
    <button type="submit">Išsaugoti</button>
</form>
@endsection

@section('js')
<script>

</script>
@endsection