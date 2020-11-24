@extends('templates/carcass')

@section('content')
<form action="" method="post">
    <label for="email">Email:</label>
    <input type="text" name="email" required>
    <br />
    <label for="password">Password:</label>
    <input type="password" name="password" required>
    <br />
    <input type="submit" value="Log in">
</form>
@if(!empty($status))
<p>{{ $status }}</p>
@endif
@endsection