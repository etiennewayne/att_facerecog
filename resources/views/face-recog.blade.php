<!DOCTYPE html>
<html>

<head>
    {{-- <script src="{{ asset('/js/face/face-api.js') }}"></script>
    <script src="{{ asset('js/face/commons.js') }}"></script>
    <script src="{{ asset('js/face/weights/tiny_face_detector_model-weights_manifest.json') }}"></script> --}}

    <script defer src="{{ asset('/js/face/face-api.min.js') }}"></script>
    <script src="{{ asset('/js/face/faceDetectionControls.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/css/face/styles.css') }}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.css"> --}}
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script> --}}
</head>

<body>
    
    <div class="center-content page-container">
  
      <div class="progress" id="loader">
        <div class="indeterminate"></div>
      </div>
      <div style="position: relative" class="margin">
        <video onloadedmetadata="onPlay(this)" id="inputVideo" autoplay muted playsinline></video>
        <canvas id="overlay" />
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

        if (videoEl.paused || videoEl.ended || !isFaceDetectionModelLoaded())
            return setTimeout(() => onPlay())

        //options = new faceapi.TinyFaceDetectorOptions({ inputSize, scoreThreshold }) //return tinyFaceDetector
        const options = getFaceDetectorOptions()

        const result = await faceapi.detectSingleFace(videoEl, options)
            .withFaceLandmarks()
            .withFaceDescriptor();

        
        if (result && faceMatcher) {
            const canvas = $('#overlay').get(0);    
            const dims = faceapi.matchDimensions(canvas, videoEl, true);
            const resizeDetection = faceapi.resizeResults(result, dims);


            // console.log('Resize Detection Box => ', resizeDetection);
           // console.log('result => ', result);
            // console.log('faceMatcher => ', faceMatcher);

            const faceResult = faceMatcher.findBestMatch(result.descriptor);

            //console.log('faceResult => ', faceResult);

            const faceDraw = new faceapi.draw.DrawBox(resizeDetection.detection.box, { label: faceResult.toString() });
            faceDraw.draw(canvas);
            //console.log('faceDraw ', faceDraw);
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
        //console.log(labeledFaceDescriptors);
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
</script>
</body>

</html>
