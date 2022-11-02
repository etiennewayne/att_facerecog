<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script defer src="{{ asset('/js/face/face-api.min.js') }}"></script>
    <script defer src="{{ asset('/js/face/face-script.js') }}"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            border: 1px solid red;
        }

        canvas {
            position: absolute;
            top: 0;
            left: 0;
        }

        #picture-container {
            height: 400px;
        }
    </style>
</head>

<body>
    <div class="container">
        <input type="file" id="imageUpload" />

        <div id="picture-container">
        </div>
    </div>



</body>

</html>
