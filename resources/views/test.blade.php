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
    <title>Test</title>
</head>
<body>
<div class="container">
    <div class="container">
        
        <button type="button" class="btn btn-default">button 1</button>
        <button type="button" class="btn btn-default">button 2</button>
        
    </div>
</div>



</body>
</html>