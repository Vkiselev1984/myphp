<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LIST OF USERS</title>

</head>

<body>
    <h1>LIST OF USERS</h1>
    <table>
        @foreach ($users as $user)
            <tr>
                <td>{{$user->first_name}}</td>
                <td>{{$user->last_name}}</td>
                <td>{{$user->email}}</td>
            </tr>
        @endforeach

    </table>
</body>

</html>
