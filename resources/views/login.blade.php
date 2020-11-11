@extends('templates/carcass')

@section('content')
<form method="post">
    <label for="username">Email:</label>
    <input type="text" name="username" required>
    <br />
    <label for="passw">Password:</label>
    <input type="password" name="password" required>
    <br />
    <input type="submit" value="Log in">
</form>
@endsection