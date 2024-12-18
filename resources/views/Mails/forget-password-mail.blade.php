<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$mailData['title']}}</title>
    <link href="{{ asset('css/mail.css') }}" rel="stylesheet">
</head>
<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: sans-serif;
    }

    .container {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        height: 400px;
        width: 400px;
        padding: 0 40px;
        border-radius: 10px;
        border: 1px solid red;
        gap: 10px;
    }

    button:hover {
        background-color: white;
        border: 1px solid red;
        color: red;
    }

    button {
        background-color: red;
        border-radius: 6px;
        border: none;
        padding: 8px 8px;
        color: white;
        cursor: pointer;
    }

    a {
        text-decoration: none;
    }
</style>

<body>
    <div class="container">
        <h1>Forget Passoword</h1>
        <p>{{$mailData['body']}}</p>
        <a href="{{$mailData['url']}}"><button>Reset Passoword</button></a>

    </div>
</body>

</html>