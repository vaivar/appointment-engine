@extends('templates/carcass')

@section('content')
<form action="" method="post">
    <p>
        <label for="date">Data:</label>
        <input type="text" value="{{ $defaultDate }}" disabled>
    </p>
    <p>
        <label for="salons">Salonas:</label>
        <select name="salons">
        @foreach($salons as $salon)
            <option value="{{$salon->id}}">{{$salon->address}}</option>
        @endforeach
        </select>
    </p>
    <p>
        <label for="slots">Vietos:</label>
        <input type="text" name="slots">
    </p>
    <p>
        <label for="times" style="vertical-align:top;">Laikai:</label>
        <textarea name="times" rows="4" cols="50"></textarea>
    </p>
    <button type="submit">Išsaugoti</button>
</form>
@endsection

@section('js')
<script>

</script>
@endsection