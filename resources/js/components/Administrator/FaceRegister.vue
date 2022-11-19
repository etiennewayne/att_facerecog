<template>

    <div>
        <div class="section">
            <div class="box">

                <b-tabs v-model="activeTab">
                    <b-tab-item label="Personal Inforamtion">
                        <div>
                            <b-field label="Last Name">
                                <b-input type="text" v-model="lname" placeholder="Last Name" required />
                            </b-field>
    
                            <b-field label="First Name">
                                <b-input type="text" v-model="fname" placeholder="First Name" required />
                            </b-field>
    
                            <b-field label="Middle Name">
                                <b-input type="text" v-model="mname" placeholder="Middle Name" />
                            </b-field>
    
                            <b-field label="Suffix">
                                <b-input type="text" v-model="suffix" placeholder="Suffix" />
                            </b-field>
    
                            <b-field label="Sex" expanded>
                                <b-select v-model="sex" placeholder="Sex" expanded>
                                    <option value="MALE">MALE</option>
                                    <option value="FEMALE">FEMALE</option>
                                </b-select>
                            </b-field>
    
                            <b-field label="Contact No.">
                                <b-input type="text" v-model="contact_no" placeholder="Contact No." required />
                            </b-field>
                            <div class="buttons">
                                <b-button label="Clear" type="button" @click="clearForm"></b-button>
                            </div>
                        </div>
                    </b-tab-item>
        
                    <b-tab-item label="Image">
                        <div class="form-container">
                            <div class="form-header">
                                <div class="form-title">REGISTER FACE HERE</div>
                            </div>
                            <div class="form-body">
                                <div class="webcam-container">
                                    <video class="webcam" id="video" width="320" height="240" autoplay></video>
                                </div>
                                <div class="camera-title">CAMERA</div>
                                <div class="buttons is-centered mt-2">
                                    <b-button class="button is-primary" @click="snap">Snap</b-button>
                                    <b-button class="is-warning" @click="showSize">Show Size</b-button>
                                </div>
        
                                <div class="capture-container">
                                    <div class="canvas-container">
                                        <canvas id="canvas1" :width="canvasWidth" :height="canvasHeight"></canvas>
                                    </div>
                                    <div class="canvas-container">
                                        <canvas id="canvas2" :width="canvasWidth" :height="canvasHeight"></canvas>
                                    </div>
                                    <div class="canvas-container">
                                        <canvas id="canvas3" :width="canvasWidth" :height="canvasHeight"></canvas>
                                    </div>
                                    <img id="img" />
                                </div>
                            </div>
                            <div class="footer">
                                <div class="buttons is-centered">
                                    <button :class="btnClass" @click="submit">REGISTER FACE</button>
                                </div>
                            </div>
                        </div>
                    </b-tab-item>
                </b-tabs>
                <!-- <div>{{ debug }}</div> -->
            </div>
            
            <b-loading :is-full-page="true" v-model="isLoading" :can-cancel="true"></b-loading>

        </div> <!--section-->
        <!-- Webcam.min.js -->
    </div>



</template>    

<!-- <script src='/js/webcam/webcam.js' defer></script> -->
<script>

export default {
    data(){
        return{
            activeTab: 0,
            shutterCount: 0,

            loading: true,
            isLoading: false,

            //fields
            name: '',
           
            lname: 'Dela Curz',
            fname: 'Juan',
            mname: '',
            suffix: '',
            sex: 'MALE',
            contact_no: '916465',

            canvasWidth: 320,
            canvasHeight: 240,

            detections1: null,
            detections2: null,
            detections2: null,
            descriptions: [],
            // lname: '',
            // fname: '',
            // mname: '',
            // suffix: '',
            // sex: '',
            // contact_no: '',

            debug: 'test',

            btnClass: {
                'button': true,
                'is-primary': true,
                'is-loading': true
            }
        }
    },


    methods:{

        async startCamera(){
            this.btnClass['is-loading'] = true;
            let stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
	        video.srcObject = stream;

           
        },

        snap(){

            let canvas1 = document.getElementById('canvas1');
            let canvas2 = document.getElementById('canvas2');
            let canvas3 = document.getElementById('canvas3');
            let img = document.getElementById('img');

            let image_data_url;

            // var scale = Math.min(this.canvasWidth / video.width, this.canvasHeight / video.height);
            // var w = video.width * scale;
            // var h = video.height * scale;

            // var left = this.canvasWidth / 2 - w / 2;
            // var top = this.canvasHeight / 2 - h / 2;

            switch(this.shutterCount){
                case 0:
                    canvas1.getContext('2d').drawImage(video, 0, 0, this.canvasWidth, this.canvasHeight);
                    //image_data_url = canvas1.toDataURL('image/jpeg');
                    break;
                case 1:
                    canvas2.getContext('2d').drawImage(video, 0, 0, this.canvasWidth, this.canvasHeight);
                    //image_data_url = canvas2.toDataURL('image/jpeg');
                    break;
                case 2:
                    canvas3.getContext('2d').drawImage(video, 0, 0, this.canvasWidth, this.canvasHeight);
                    //image_data_url = canvas3.toDataURL('image/jpeg');
                    break;
            }
            
            if(this.shutterCount < 2){
                this.shutterCount++;
            }else{
                this.shutterCount = 0;
            }
            // data url of the image

        },


        submit: function(){
            this.btnClass['is-loading'] = true;
            this.isLoading = true;
            console.log('first')

            this.store().then(()=>{
                console.log('store completed')
                this.btnClass['is-loading'] = false;
                this.isLoading = false;
            });
        },

        async store(){
            console.log('second');
            
            let canvas1 = document.getElementById('canvas1');
            let canvas2 = document.getElementById('canvas2');
            let canvas3 = document.getElementById('canvas3');
           
    
            this.detections1 = await faceapi.detectSingleFace(canvas1, new faceapi.TinyFaceDetectorOptions())
                                    .withFaceLandmarks().withFaceDescriptor();
            this.descriptions.push(this.detections1.descriptor)

            this.detections2 = await faceapi.detectSingleFace(canvas2, new faceapi.TinyFaceDetectorOptions())
                                    .withFaceLandmarks().withFaceDescriptor();
            this.descriptions.push(this.detections2.descriptor)
            
            this.detections3 = await faceapi.detectSingleFace(canvas3, new faceapi.TinyFaceDetectorOptions())
                                    .withFaceLandmarks().withFaceDescriptor();
            this.descriptions.push(this.detections3.descriptor)
            
            this.debug = this.descriptions;

            this.btnClass['is-loading'] = false;

            axios.post('/store-descriptor', {
                lname : this.lname,
                fname : this.fname,
                mname : this.mname,
                suffix : this.suffix,
                sex : this.sex,
                contact_no : this.contact_no,
                descriptors : this.descriptions
            }).then(res=>{
               
                this.debug = res.data;
                this.$buefy.dialog.alert({
                    title: 'Saved!',
                    message: 'Image successfully saved.',
                    confirmText: 'OK',
                    type: 'is-success',
                    onConfirm: ()=>{
                        
                        this.clearForm();
                    }
                })
            }).catch(err=>{
                alert(err)
            })
        },
        clearForm(){
            this.shutterCount = 0;
            this.lname = '';
            this.fname = '';
            this.mname = '';
            this.suffix = '';
            this.sex = '';
            this.contact_no = '';

            this.descriptions = [];

            this.detections1 = null;
            this.detections2 = null;
            this.detections3 = null;

            let context1 = canvas1.getContext('2d');
            let context2 = canvas2.getContext('2d');
            let context3 = canvas3.getContext('2d');

            context1.clearRect(0, 0, canvas1.width, canvas1.height);
            context2.clearRect(0, 0, canvas2.width, canvas2.height);
            context3.clearRect(0, 0, canvas3.width, canvas3.height);

        },
        initFaceDetectionControls(){
            faceapi.nets.faceRecognitionNet.loadFromUri('/js/face/weights');
            faceapi.nets.faceLandmark68Net.loadFromUri('/js/face/weights');
        },

        async run() {
            await changeFaceDetector(TINY_FACE_DETECTOR);
            changeInputSize(128);

            this.btnClass['is-loading'] = false;
            console.log(this.btnClass)
        },

        showSize(){
            let videoImg = document.getElementById('video');
            let canvas1 = document.getElementById('canvas1');
           // alert('Video Width: ' + canvas1.width);
           alert('screen width: ' + screen.width);
        },

        myEventHandler(e) {
            // your code for handling resize...
            //console.log(e)
            if(screen.width < 400){
                this.canvasWidth = 240;
                this.canvasHeight = 320;
            }else{
                this.canvasWidth = 320;
                this.canvasHeight = 240;
            }
        }
    },

    created() {
        window.addEventListener("resize", this.myEventHandler);
    },
    destroyed() {
        window.removeEventListener("resize", this.myEventHandler);
    },
    mounted(){
        this.startCamera();
        this.initFaceDetectionControls();
        this.run(); 
    }
}        
</script>

<style scoped src="../../../css/face-register.css"></style>