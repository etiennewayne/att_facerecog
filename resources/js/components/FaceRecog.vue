<template>
    <div>
        <div class="w-center-content">
            <div class="w-title">Face Recognition Attendance System</div>
            <div class="video-container">
                <video @loadedmetadata="onPlay(this)" 
                    id="inputVideo" autoplay muted playsinline
                    width="320" height="240">
                </video>
                <canvas id="overlay" />
            </div>

            <div class="time-container">
                <div id="clock"></div>
            </div>
        </div>


        <div class="section">
            <div class="buttons">
                <b-button @click="loadLabeledImages">Load Label (debug only)</b-button>
            </div>
        </div>
    </div>
</template>


<script>

    let forwardTimes = []
    let faceMatcher;


export default {
    data() {
        return{
            labels: [],
            labeledFaceDescriptors: null,
        }
    },

    methods: {

        async onPlay() {
            //loop
            const videoEl = $('#inputVideo').get(0)
            let canvas = $('#overlay').get(0);
            
            if (videoEl.paused || videoEl.ended || !isFaceDetectionModelLoaded())
                return setTimeout(() => this.onPlay())

            //options = new faceapi.TinyFaceDetectorOptions({ inputSize, scoreThreshold }) //return tinyFaceDetector
            const options = getFaceDetectorOptions()

            const result = await faceapi.detectSingleFace(videoEl, options)
                .withFaceLandmarks()
                .withFaceDescriptor();
            const displaySize = { width: videoEl.width, height: videoEl.height };

            console.log('faceMatcher', faceMatcher);
            
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

            
            setTimeout(() => this.onPlay(), 3000)
        },

        async run() {
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

            this.labeledFaceDescriptors = await this.loadLabeledImages();
            console.log('label descriptors: ', this.labeledFaceDescriptors);
            //start faceMatching, threshold 0.6
            faceMatcher = new faceapi.FaceMatcher(this.labeledFaceDescriptors, 0.6);
            console.log('faceMatcher descriptors: ', faceMatcher);
        },

        async loadLabeledImages() {
           
            await axios.get('/load-descriptors').then(res=>{
                //console.log('load images from db: ', res.data);
                this.labels = res.data;
            })

            return Promise.all(
                this.labels.map(async label => {
                    const descriptions = [];
                    const name = `(${label.employee_id}) ${label.fname}`;

                    //console.log(label.descriptors[0].descriptor);
                    
                    for(let i = 0; i < 3; i++) {

                        descriptions.push(new Float32Array(label.descriptors[i].descriptor))
                    }
                    console.log('descriptions: ', descriptions);
                    return new faceapi.LabeledFaceDescriptors(name, descriptions)
                })
            );
        },


        loadLabeledImagesLocalImages() {
            const labels = [
                {
                    name: 'Leda Grace',
                    folder: 'leda',
                    lname: 'Abella',
                    fname: 'Leda Grace',
                    mname: 'C',
                    suffix: '',
                    sex: 'FEMALE',
                    contact_no: '09164'
                }, 
                {
                    name: 'Etienne',
                    folder: 'et',
                    lname: 'Amparado',
                    fname: 'Etienne Wayne',
                    mname: 'N',
                    suffix: '',
                    sex: 'MALE',
                    contact_no: '09164'
                    
                },
                {
                    name: 'Ramonito',
                    folder: 'monmon',
                    lname: 'Lumapac',
                    fname: 'Ramonito',
                    mname: 'U',
                    suffix: '',
                    sex: 'MALE',
                    contact_no: '09164'
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
                        console.log('descriptions: ' + descriptions)
                    } //loop

                    axios.post('/store-descriptor', {
                            lname : label.lname,
                            fname : label.fname,
                            mname : label.mname,
                            suffix : label.suffix,
                            sex : label.sex,
                            contact_no : label.contact_no,
                            descriptors : descriptions
                        })
                    return new faceapi.LabeledFaceDescriptors(label.name, descriptions)
                })
            );
        },

        updateResults(){},

        currentTime: function() {
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

            let t = setTimeout( ()=> this.currentTime() , 1000);
        },

        initFaceDetectionControls(){
            faceapi.nets.faceRecognitionNet.loadFromUri('/js/face/weights');
            faceapi.nets.faceLandmark68Net.loadFromUri('/js/face/weights');
        }
    },


    mounted(){

        this.$nextTick(function () {
            // Code that will run only after the
            // entire view has been rendered
            this.loadLabeledImages();

            this.initFaceDetectionControls();
            this.run();
            this.currentTime();
        })

      

    }
}
</script>