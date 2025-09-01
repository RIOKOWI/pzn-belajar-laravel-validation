<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/form" method="post">
        @csrf
        <label>username : <input type="text" name="username"></label> 
        <br>
        <label>password : <input type="password" name="password"></label>
        <br>
        <input type="submit" value="login">
    </form>
</body>
</html>