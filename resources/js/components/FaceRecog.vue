<template>
    <div>
        <div class="w-center-content">
            <div class="w-title">Face Recognition Attendance System</div>
            <div class="video-container">
                <video @loadedmetadata="onPlay(this)"
                    id="inputVideo" autoplay muted playsinline
                    :width="canvasWidth" :height="canvasHeight">
                </video>
                <canvas id="overlay" />
            </div>

            <div class="time-container">
                <div id="clock"></div>
            </div>

           <div>
               <b-field>
                   <b-select v-model="timeStatus" disabled>
                       <option value="in_am">IN AM</option>
                       <option value="out_am">OUT AM</option>

                       <option value="in_pm">IN PM</option>
                       <option value="out_pm">OUT PM</option>

                   </b-select>
               </b-field>
           </div>
        </div>


        <!-- <div class="section">
            <div class="buttons">
                <b-button @click="loadLabeledImages">Load Label (debug only)</b-button>
            </div>
        </div> -->
    </div>
</template>


<script>
import { tSExpressionWithTypeArguments } from '@babel/types';


    let forwardTimes = []
    let faceMatcher;
    var tempId = 0;
    var labelResult = '';

export default {
    data() {
        return{
            labels: [],
            labeledFaceDescriptors: null,

            timeStatus: '',

            canvasWidth: 240,
            canvasHeight: 320,
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
            
            //const displaySize = { width: this.canvasWidth, height: this.canvasHeight }; //try

            //console.log('faceMatcher', faceMatcher);

            if (result &&  faceMatcher) {
                
                faceapi.matchDimensions(canvas, displaySize, true);
                const resizeDetection = faceapi.resizeResults(result, displaySize);

                // console.log('Resize Detection Box => ', resizeDetection);
                // console.log('result => ', result);
                // console.log('faceMatcher => ', faceMatcher);

                const faceResult = faceMatcher.findBestMatch(result.descriptor);

                //console.log('faceResult => ', faceResult);

                const faceDraw = new faceapi.draw.DrawBox(resizeDetection.detection.box, { label: faceResult.toString() });
                faceDraw.draw(canvas);

                labelResult = faceResult.toString().split(/[ ,]+/)[0];
                if(labelResult === 'unknown'){
                    console.log('Refresh Data');
                    this.loadFaces(); 
                }

                if(faceResult.distance <= 0.4){
                    console.log('Im sure for the recognition');
                    if(labelResult != 'unknown'){
                        axios.post('/store-dtr',{
                            t_time: new Date(),
                            t_user: labelResult,
                            t_status: this.timeStatus
                        }).then(res=>{
                            if(res.data.status === 'saved'){
                                this.$buefy.toast.open({
                                    duration: 4000,
                                    message: 'Time recorded successfully.',
                                    position: 'is-top',
                                    type: 'is-success'
                                })
                            }
                            
                        }).catch(err=>{
                            this.loadFaces();
                        })
                    }   
                }
                //either number or u will return

                //console.log('result =>' + labelResult + ' vs TempId => ' + tempId);

                // if(labelResult[0] != 'u'){
                //     if(labelResult[0] != tempId){
                        
                //     }
                //     tempId = parseInt(labelResult[0]);
                // }

                

            }else{
                canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
            }

            setTimeout(() => this.onPlay(), 4000)
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

            await this.loadFaces();
        },

        async loadFaces(){
            this.labeledFaceDescriptors = await this.loadLabeledImages();
            //console.log('label descriptors: ', this.labeledFaceDescriptors);
            //start faceMatching, threshold 0.6
            faceMatcher = new faceapi.FaceMatcher(this.labeledFaceDescriptors, 0.6);
            //console.log('faceMatcher descriptors: ', faceMatcher);
            
            // if(faceMatcher){
            //     this.$buefy.toast.open({
            //         duration: 4000,
            //         message: 'Face data successfully loaded.',
            //         position: 'is-bottom',
            //         type: 'is-info'
            //     });
            // }
        },

        async loadLabeledImages() {
            await axios.get('/load-descriptions').then(res=>{
                //console.log('load images from db: ', res.data);
                this.labels = res.data;
            })

            return Promise.all(
                this.labels.map(async label => {
                    const descriptions = [];
                    const name = `${label.user_id} ${label.fname}`;
                    //console.log(label.descriptors[0].descriptor);
                    for(let i = 0; i < 3; i++) {
                        descriptions.push(new Float32Array(label.descriptions[i].descriptions))
                    }
                    //console.log('descriptions: ', descriptions);
                    return new faceapi.LabeledFaceDescriptors(name, descriptions)
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

            //set combo box for time in , time out auto
            if(hh >= 0 && hh < 12){
                this.timeStatus = 'in_am';
            }

            if(hh >= 12 && hh < 13 ){
                if( mm > 0 && mm <= 30){
                    this.timeStatus = 'out_am';
                }
                if(mm > 30 && mm <= 59){
                    this.timeStatus = 'in_pm';
                }
            }

            if(hh > 13 && hh <= 15){
                this.timeStatus = 'in_pm';
            }

            if(hh > 15 && hh <= 23){
                this.timeStatus = 'out_pm';
            }


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

            let t = setTimeout( ()=>
                this.currentTime()
                , 1000);
        },

        initFaceDetectionControls(){
            faceapi.nets.faceRecognitionNet.loadFromUri('/js/face/weights');
            faceapi.nets.faceLandmark68Net.loadFromUri('/js/face/weights');
        },

        myEventHandler(e) {
            // your code for handling resize...
            console.log('canvasheight: ' +this.canvasHeight + '\n canvas widht: ' + this.canvasWidth)
            if(screen.width < 450){
                this.canvasWidth = 240;
                this.canvasHeight = 320;
            }else{
                this.canvasWidth = 320;
                this.canvasHeight = 240;
            }
        },
    },

    created() {
        window.addEventListener("resize", this.myEventHandler);
    },
    destroyed() {
        window.removeEventListener("resize", this.myEventHandler);
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
