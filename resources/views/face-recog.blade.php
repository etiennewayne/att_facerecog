<!DOCTYPE html>
<html>

<head>
    {{-- <script src="{{ asset('/js/face/face-api.js') }}"></script>
    <script src="{{ asset('js/face/commons.js') }}"></script>
    <script src="{{ asset('js/face/weights/tiny_face_detector_model-weights_manifest.json') }}"></script> --}}

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    
    {{-- <script src="{{ asset('js/webcam/webcam.js') }}" defer></script> --}}


    <!-- Fonts -->
    {{--    <link rel="dns-prefetch" href="//fonts.gstatic.com">--}}
    {{--    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}

    <script defer src="{{ asset('/js/face/face-api.min.js') }}"></script>
    <script src="{{ asset('/js/face/faceDetectionControls.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/css/face/styles.css') }}">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <style>
        html body{
            font-family: 'Roboto', sans-serif;
        }
    </style>
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
<body>
    <div id="app">
        <div class="center-content">
            <div class="title">Face Recognition Attendance System</div>
            <div class="video-container">
                <video onloadedmetadata="onPlay(this)" 
                    id="inputVideo" autoplay muted playsinline
                    width="320" height="240">
                </video>
                <canvas id="overlay" />
            </div>

            <div class="time-container">
                <div id="clock"></div>
            </div>
        </div>
    </div>
  
</body>

<script>
    let forwardTimes = []
    let faceMatcher;

    //let inputSize = 512
    //let scoreThreshold = 0.5
    //var options;


    async function onPlay() {
        //loop
        const videoEl = $('#inputVideo').get(0)
        let canvas = $('#overlay').get(0);
        if (videoEl.paused || videoEl.ended || !isFaceDetectionModelLoaded())
            return setTimeout(() => onPlay())

        //options = new faceapi.TinyFaceDetectorOptions({ inputSize, scoreThreshold }) //return tinyFaceDetector
        const options = getFaceDetectorOptions()

        const result = await faceapi.detectSingleFace(videoEl, options)
            .withFaceLandmarks()
            .withFaceDescriptor();
        const displaySize = { width: videoEl.width, height: videoEl.height };
        

        if (result && faceMatcher) {
                
            faceapi.matchDimensions(canvas, displaySize, true);
            const resizeDetection = faceapi.resizeResults(result, displaySize);


            // console.log('Resize Detection Box => ', resizeDetection);
           // console.log('result => ', result);
            // console.log('faceMatcher => ', faceMatcher);

            const faceResult = faceMatcher.findBestMatch(result.descriptor);

            console.log('faceResult => ', faceResult);
            
            const faceDraw = new faceapi.draw.DrawBox(resizeDetection.detection.box, { label: faceResult.toString() });
            faceDraw.draw(canvas);
            //console.log('faceDraw ', faceDraw);
        }else{
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
        }

        
        setTimeout(() => onPlay(), 3000)
    }

    async function run() {
        // load face detection model
        //await changeFaceDetector(TINY_FACE_DETECTOR)
         //ikaw ang hinungdan mabuang ko!!!!!

        await changeFaceDetector(TINY_FACE_DETECTOR)
        changeInputSize(128)
       
        // try to access users webcam and stream the images
        // to the video element
        const stream = await navigator.mediaDevices.getUserMedia({
            video: {}
        })

        const videoEl = $('#inputVideo').get(0)
        videoEl.srcObject = stream

        const labeledFaceDescriptors = await loadLabeledImages();
        faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);
        console.log(labeledFaceDescriptors);
    }

    function loadLabeledImages() {
        const labels = [
            {
                folder: 'leda',
                name: 'Leda Grace Kigwa'
            }, 
            {
                folder: 'et',
                name: 'Etienne Wayne'
            },
            {
                folder: 'monmon',
                name: 'Ramonito'
            }
        ];

        return Promise.all(
            labels.map(async label => {
                const descriptions = [];

                for (let i = 1; i <= 3; i++) {

                    const img = await faceapi.fetchImage(`/labeled_images/${label.folder}/${i}.jpg`); //convert image to base64
                   
                    const detections = await faceapi.detectSingleFace(img, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks().withFaceDescriptor();
                    console.log('img analyze detections ' + label.folder + ' => ', detections);
                    descriptions.push(detections.descriptor)
                    //console.log('descriptions: ' + descriptions)
                } //loop

                return new faceapi.LabeledFaceDescriptors(label.name, descriptions)
            })
        );
    }

    function updateResults(){}

    $(document).ready(function() {
        initFaceDetectionControls()
        run()
    })


    function currentTime() {
        let date = new Date(); 
        let hh = date.getHours();
        let mm = date.getMinutes();
        let ss = date.getSeconds();
        let session = "AM";

        if(hh === 0){
            hh = 12;
        }
        if(hh > 12){
            hh = hh - 12;
            session = "PM";
        }

        hh = (hh < 10) ? "0" + hh : hh;
        mm = (mm < 10) ? "0" + mm : mm;
        ss = (ss < 10) ? "0" + ss : ss;
            
        let time = hh + ":" + mm + ":" + ss + " " + session;

        document.getElementById("clock").innerText = time; 
        let t = setTimeout(function(){ currentTime() }, 1000);
    }

    currentTime();
</script>
</body>

</html>
