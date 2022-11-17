<template>

    <div>
        <div class="section">
           
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
                                <div class="buttons is-centered mt-2">
                                    <b-button class="button is-primary" @click="snap">Snap</b-button>
                                </div>
        
                                <div class="capture-container">
                                    <div class="canvas-container">
                                        <canvas id="canvas1" width="320" height="240"></canvas>
                                    </div>
                                    <div class="canvas-container">
                                        <canvas id="canvas2" width="320" height="240"></canvas>
                                    </div>
                                    <div class="canvas-container">
                                        <canvas id="canvas3" width="320" height="240"></canvas>
                                    </div>
                                    <img id="img" />
                                </div>
                            </div>
                            <div class="footer">
                                <div class="buttons">
                                    <button :class="btnClass" @click="submit">REGISTER FACE</button>
                                </div>
                            </div>
                        </div>
                    </b-tab-item>
                </b-tabs>
            
                <div>{{ debug }}</div>

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

            //fields
            name: '',
           
            lname: 'Amparado',
            fname: 'Etienne Wayne',
            mname: '',
            suffix: '',
            sex: 'MALE',
            contact_no: '916465',

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

            switch(this.shutterCount){
                case 0:
                    canvas1.getContext('2d').drawImage(video, 0, 0, 320, 240);
                    image_data_url = canvas1.toDataURL('image/jpeg');
                    break;
                case 1:
                    canvas2.getContext('2d').drawImage(video, 0, 0, 320, 240);
                    image_data_url = canvas2.toDataURL('image/jpeg');
                    break;
                case 2:
                    canvas3.getContext('2d').drawImage(video, 0, 0, 320, 240);
                    image_data_url = canvas3.toDataURL('image/jpeg');
                    break;
            }
            
            if(this.shutterCount < 2){
                this.shutterCount++;
            }else{
                this.shutterCount = 0;
            }
            // data url of the image
            console.log(image_data_url);
        },


        submit: function(){
            this.btnClass['is-loading'] = true;
            this.store();
        },

        async store(){
            
            let canvas1 = document.getElementById('canvas1');
            let canvas2 = document.getElementById('canvas2');
            let canvas3 = document.getElementById('canvas3');

            const descriptions = [];
            
            const detections1 = await faceapi.detectSingleFace(canvas1, new faceapi.TinyFaceDetectorOptions())
                                    .withFaceLandmarks().withFaceDescriptor();
            descriptions.push(detections1.descriptor)
            
            this.debug = descriptions;
            // const detections2 = await faceapi.detectSingleFace(canvas2, new faceapi.TinyFaceDetectorOptions())
            //                         .withFaceLandmarks().withFaceDescriptor();
            // descriptions.push(detections2.descriptor)
            
            // const detections3 = await faceapi.detectSingleFace(canvas2, new faceapi.TinyFaceDetectorOptions())
            //                         .withFaceLandmarks().withFaceDescriptor();
            // descriptions.push(detections3.descriptor)
            
            
            // axios.post('/store-descriptor', {
            //     lname : this.lname,
            //     fname : this.fname,
            //     mname : this.mname,
            //     suffix : this.suffix,
            //     sex : this.sex,
            //     contact_no : this.contact_no,
            //     //descriptors : descriptions
            // }).then(res=>{
            //     this.btnClass['is-loading'] = false;
            //     this.debug = res.data;
            //     this.$buefy.dialog.alert({
            //         title: 'Saved!',
            //         message: 'Image successfully saved.',
            //         confirmText: 'OK',
            //         type: 'is-success'
            //     })
            // })
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
        }
    },

    mounted(){
        this.startCamera();
        this.initFaceDetectionControls();
        this.run();
        
    }
}        
</script>

<style scoped src="../../../css/face-register.css"></style>