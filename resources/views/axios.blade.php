<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .list-group{
            overflow-y: scroll;
            max-height: 200px
        }
    </style>
    <title>Document</title>
</head>
<body>
     <div id="app">
            <api-calling></api-calling>
        </div>    

    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
</html>