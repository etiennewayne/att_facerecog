<!DOCTYPE html>
<html>

<head>
    {{-- <script src="{{ asset('/js/face/face-api.js') }}"></script>
    <script src="{{ asset('js/face/commons.js') }}"></script>
    <script src="{{ asset('js/face/weights/tiny_face_detector_model-weights_manifest.json') }}"></script> --}}

    <script defer src="{{ asset('/js/face/face-api.min.js') }}"></script>


    <script src="{{ asset('/js/face/faceDetectionControls.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/css/face/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
</head>

<body>

    <div class="section">

        <div class="title">FACE RECOGNITION</div>
        <div class="center-content page-container">
            <div class="progress" id="loader">
                <div class="indeterminate"></div>
            </div>
            <div style="position: relative" class="margin">
                <video onloadedmetadata="onPlay(this)" id="inputVideo" autoplay muted playsinline></video>
                <canvas id="overlay" />
            </div>
            <input type="file" id="testMe">
        </div>

        <div id="fps_meter" class="fps_meter">
            <div>
                <label for="time">Time:</label>
                <input disabled value="-" id="time" type="text" class="bold">
                <label for="fps">Estimated Fps:</label>
                <input disabled value="-" id="fps" type="text" class="bold">
            </div>
        </div>
        <!-- tiny_face_detector_controls -->

    </div>

</body>

<script>
    let forwardTimes = []
    let faceMatcher;

    function updateTimeStats(timeInMs) {
        forwardTimes = [timeInMs].concat(forwardTimes).slice(0, 30)
        const avgTimeInMs = forwardTimes.reduce((total, t) => total + t) / forwardTimes.length
        $('#time').val(`${Math.round(avgTimeInMs)} ms`)
        $('#fps').val(`${faceapi.utils.round(1000 / avgTimeInMs)}`)
    }

    async function onPlay() {
        //loop
        const videoEl = $('#inputVideo').get(0)

        if (videoEl.paused || videoEl.ended || !isFaceDetectionModelLoaded())
            return setTimeout(() => onPlay())


        const options = getFaceDetectorOptions() //return tinyFaceDetector

        const ts = Date.now()

        const result = await faceapi.detectSingleFace(videoEl, options)
            .withFaceLandmarks()
            .withFaceDescriptor();

        updateTimeStats(Date.now() - ts)

        console.log(result)


        if (result) {
            const canvas = $('#overlay').get(0)
            const dims = faceapi.matchDimensions(canvas, videoEl, true)
            faceapi.draw.drawDetections(canvas, faceapi.resizeResults(result, dims))
        }

        // if (result) {
        //     const canvas = $('#overlay').get(0)
        //     faceapi.matchDimensions(canvas, videoEl, true)

        //     //faceapi.draw.drawDetections(canvas, )
        //     const resizeDetections = faceapi.resizeResults(result, videoEl);
        //     //faceapi.draw.drawDetections(canvas, resizeDetections)
        //     //console.log(canvas)
        //     // const drawBox = new faceapi.draw.DrawBox(canvas, { label: "Test"})
        //     // drawBox.draw(canvas);
        //     //console.log(faceapi.resizeResults(result, dims))
        //     console.log(resizeDetections);

        //     //const results = resizeDetections.map(d => faceMatcher.findBestMatch(d.descriptor));
        //     //naa error


        //     // results.forEach((result, i) => {
        //     //     const box = resizeDetections[i].detection.box;
        //     //     const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() });
        //     //     drawBox.draw(canvas);
        //     // })
        // }

        setTimeout(() => onPlay(), 3000)

    }

    async function run() {
        // load face detection model
        await changeFaceDetector(TINY_FACE_DETECTOR)
         //ikaw ang hinungdan mabuang ko!!!!!


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
    }

    function loadLabeledImages() {
        const labels = ['leda', 'et'];

        return Promise.all(
            labels.map(async label => {
                const descriptions = [];

                for (let i = 1; i <= 3; i++) {

                    //to call image in local storage
                    //const canvas = require('canvas');
                    //faceapi.env.monkeyPatch({ Canvas, Image })
                    //const img = await canvas.loadImage(`/labeled_images/${label}/${i}.jpg`);
                    //const faceBase64 = 'data:image/jpg;base64,' + fs.readFileSync(`/labeled_images/${label}/${i}.jpg`, {encoding: 'base64'})
                    //console.log(faceBase64);
                    //no of images in folder //need upload to server with http

                    const img = await faceapi.fetchImage(`/labeled_images/${label}/${i}.jpg`)
                    const detections = await faceapi.detectSingleFace(img, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks().withFaceDescriptor();

                    descriptions.push(detections.descriptor)
                    //console.log('descriptions: ' + descriptions)
                } //loop

                return new faceapi.LabeledFaceDescriptors(label, descriptions)
            })
        );
    }

    function updateResults() {}

    $(document).ready(function() {
        // renderNavBar('#navbar', 'webcam_face_detection')
        initFaceDetectionControls()
        run()
    })
</script>
</body>

</html>
