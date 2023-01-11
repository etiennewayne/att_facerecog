<template>

    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8-desktop is-10-tablet">
                    <div class="box">
                        <b-tabs v-model="activeTab">
                            <b-tab-item label="Personal Information">
                                <div>
                                    <div class="columns">
                                        <div class="column">
                                            <b-field label="Username" :type="this.errors.username ? 'is-danger':''"
                                                     :message="this.errors.username ? this.errors.username[0] : ''">
                                                <b-input v-model="fields.username"
                                                         placeholder="Username" required>
                                                </b-input>
                                            </b-field>
                                        </div>
                                    </div>
        
                                    <div class="columns" v-if="global_id < 1">
                                        <div class="column">
                                            <b-field label="Password" :type="this.errors.password ? 'is-danger':''"
                                                     :message="this.errors.password ? this.errors.password[0] : ''">
                                                <b-input type="password" password-reveal v-model="fields.password"
                                                         placeholder="Password" required>
                                                </b-input>
                                            </b-field>
                                        </div>
                                        <div class="column">
                                            <b-field label="Confirm Password" :type="this.errors.password_confirmation ? 'is-danger':''"
                                                     :message="this.errors.password_confirmation ? this.errors.password_confirmation[0] : ''">
                                                <b-input type="password" password-reveal v-model="fields.password_confirmation"
                                                         placeholder="Confirm Password" required>
                                                </b-input>
                                            </b-field>
                                        </div>
                                    </div>
        
        
                                    <div class="columns">
                                        <div class="column">
                                            <b-field label="Last Name" :type="this.errors.lname ? 'is-danger':''"
                                                     :message="this.errors.lname ? this.errors.lname[0] : ''">
                                                <b-input type="text" v-model="fields.lname" placeholder="Last Name" required />
                                            </b-field>
                                        </div>
                                        <div class="column">
                                            <b-field label="First Name" :type="this.errors.fname ? 'is-danger':''"
                                                     :message="this.errors.fname ? this.errors.fname[0] : ''">
                                                <b-input type="text" v-model="fields.fname" placeholder="First Name" required />
                                            </b-field>
                                        </div>
                                    </div>
        
        
                                    <div class="columns">
                                        <div class="column">
                                            <b-field label="Middle Name">
                                                <b-input type="text" v-model="fields.mname" placeholder="Middle Name" />
                                            </b-field>
                                        </div>
                                        <div class="column">
                                            <b-field label="Suffix">
                                                <b-input type="text" v-model="fields.suffix" placeholder="Suffix" />
                                            </b-field>
                                        </div>
                                    </div>
        
        
                                    <div class="columns">
                                        <div class="column">
                                            <b-field label="Sex" expanded>
                                                <b-select v-model="fields.sex" placeholder="Sex" expanded>
                                                    <option value="MALE">MALE</option>
                                                    <option value="FEMALE">FEMALE</option>
                                                </b-select>
                                            </b-field>
                                        </div>
                                        <div class="column">
                                            <b-field label="Contact No.">
                                                <b-input type="text" v-model="fields.contac_no" placeholder="Contact No." />
                                            </b-field>
                                        </div>
                                        <div class="column">
                                            <b-field label="Branches" expanded>
                                                <b-select v-model="fields.branch_id" placeholder="Branch" expanded>
                                                    <option v-for="(item, index) in branches" :key="index" :value="item.branch_id">{{ item.branch_name }}</option>
                                                </b-select>
                                            </b-field>
                                        </div>
                                    </div>
        
                                    <div class="columns">
                                        <div class="column">
                                            <b-field label="Role" expanded
                                                :type="this.errors.role ? 'is-danger':''"
                                                :message="this.errors.role ? this.errors.role[0] : ''">
                                                <b-select v-model="fields.role" placeholder="Role" expanded disabled>
                                                    <option value="EMPLOYEE">EMPLOYEE</option>
                                                </b-select>
                                            </b-field>
                                        </div>
                                        <div class="column">
                                            <b-field label="Category" expanded
                                                :type="this.errors.salary_level_id ? 'is-danger':''"
                                                :message="this.errors.salary_level_id ? this.errors.salary_level_id[0] : ''">
                                                <b-select v-model="fields.salary_level_id" placeholder="Category" expanded>
                                                    <option v-for="(item, index) in salary_levels" :key="index" :value="item.salary_level_id">{{ item.salary_level }}</option>
                                                </b-select>
                                            </b-field>
                                        </div>
                                    </div>
        
                                    <div class="columns">
                                        <div class="column">
                                            <b-field label="Province" label-position="on-border" expanded
                                                     :type="this.errors.province ? 'is-danger':''"
                                                     :message="this.errors.province ? this.errors.province[0] : ''">
                                                <b-select v-model="fields.province" @input="loadCity" expanded>
                                                    <option v-for="(item, index) in provinces" :key="index" :value="item.provCode">{{ item.provDesc }}</option>
                                                </b-select>
                                            </b-field>
                                        </div>
        
                                        <div class="column">
                                            <b-field label="City" label-position="on-border" expanded
                                                     :type="this.errors.city ? 'is-danger':''"
                                                     :message="this.errors.city ? this.errors.city[0] : ''">
                                                <b-select v-model="fields.city" @input="loadBarangay" expanded>
                                                    <option v-for="(item, index) in cities" :key="index" :value="item.citymunCode">{{ item.citymunDesc }}</option>
                                                </b-select>
                                            </b-field>
                                        </div>
                                    </div>
        
                                    <div class="columns">
                                        <div class="column">
                                            <b-field label="Barangay" label-position="on-border" expanded
                                                     :type="this.errors.barangay ? 'is-danger':''"
                                                     :message="this.errors.barangay ? this.errors.barangay[0] : ''">
                                                <b-select v-model="fields.barangay" expanded>
                                                    <option v-for="(item, index) in barangays" :key="index" :value="item.brgyCode">{{ item.brgyDesc }}</option>
                                                </b-select>
                                            </b-field>
                                        </div>
                                        <div class="column">
                                            <b-field label="Street" label-position="on-border">
                                                <b-input v-model="fields.street"
                                                         placeholder="Street">
                                                </b-input>
                                            </b-field>
                                        </div>
                                    </div>
        
                                    <div class="buttons">
                                        <b-button label="Clear" type="button" @click="clearForm"></b-button>
                                        <!-- <b-button label="Debug" type="button" @click="debugMe"></b-button> -->
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
                                            <!-- <b-button class="is-warning" @click="showSize">Show Size</b-button> -->
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
                                            <b-button :class="btnClass" @click="submit">REGISTER FACE</b-button>
                                        </div>
                                    </div>
                                </div>
                            </b-tab-item>
                        </b-tabs>
                        <!-- <div>{{ detections1 }}</div> -->
                    </div>
                </div>  <!-- col -->
            </div><!-- cols-->

            <b-loading :is-full-page="true" v-model="isLoading" :can-cancel="true"></b-loading>

        </div> <!--section-->
        <!-- Webcam.min.js -->
    </div>



</template>

<script>

export default {
    props: ['propSalaryLevels', 'propBranches'],


    data(){
        return{
            activeTab: 0,
            shutterCount: 0,
            global_id: 0,

            loading: true,
            isLoading: false,

            //fields

            fields: {
                username: '',
                password: '',
                password_confirmation: '',
                lname: '',
                fname: '',
                mname: '',
                suffix: '',
                sex: '',
                role: 'EMPLOYEE',
                salary_level: '',
                contact_no: '',
                province: '',
                city: '',
                brangay: '',
                street: '',
                descriptions: [],
            },


            errors: {},
            provinces: [],
            cities: [],
            barangays: [],
            salary_levels: [],
            branches: [],

            canvasWidth: 320,
            canvasHeight: 240,

            detections1: null,
            detections2: null,
            detections3: null,
            descriptions: [],


            btnClass: {
                'button': true,
                'is-primary': true,
                'is-loading': false
            }
        }
    },


    methods:{
        //address manangement
        loadProvince: function(){
            axios.get('/load-provinces').then(res=>{
                this.provinces = res.data;
            })
        },

        loadCity: function(){
            axios.get('/load-cities?prov=' + this.fields.province).then(res=>{
                this.cities = res.data;
            })
        },

        loadBarangay: function(){
            axios.get('/load-barangays?prov=' + this.fields.province + '&city_code='+this.fields.city).then(res=>{
                this.barangays = res.data;
            })
        },
        //address manangement



        async startCamera(){
            this.btnClass['is-loading'] = true;
            let stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
	        video.srcObject = stream;
            this.btnClass['is-loading'] = false;
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

            this.store().then(()=>{
                this.btnClass['is-loading'] = false;
                this.isLoading = false;
            }).catch(err=>{
                this.btnClass['is-loading'] = false;
                this.isLoading = false;

                this.$buefy.dialog.alert({
                    title: 'Error!',
                    message: 'Image scan error. Please be sure that the image is clear or have enough light.',
                    confirmText: 'OK',
                    type: 'is-danger',
                })
            
            });
        },

        async store(){
           // console.log('second');

            let canvas1 = document.getElementById('canvas1');
            let canvas2 = document.getElementById('canvas2');
            let canvas3 = document.getElementById('canvas3');


            this.detections1 = await faceapi.detectSingleFace(canvas1, new faceapi.TinyFaceDetectorOptions())
                                    .withFaceLandmarks().withFaceDescriptor();
            this.fields.descriptions.push(this.detections1.descriptor);

            this.detections2 = await faceapi.detectSingleFace(canvas2, new faceapi.TinyFaceDetectorOptions())
                                    .withFaceLandmarks().withFaceDescriptor();
            this.fields.descriptions.push(this.detections2.descriptor);

            this.detections3 = await faceapi.detectSingleFace(canvas3, new faceapi.TinyFaceDetectorOptions())
                                    .withFaceLandmarks().withFaceDescriptor();
            this.fields.descriptions.push(this.detections3.descriptor);

          
            //this.debug = this.descriptions;

            this.btnClass['is-loading'] = false;
            console.log(this.fields)
            axios.post('/store-descriptions', this.fields).then(res=>{

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
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;

                    this.$buefy.dialog.alert({
                        title: 'Check Fields!',
                        message: 'Error occured while saving the data. Please check your inputs.',
                        confirmText: 'OK',
                        type: 'is-warning',
                        onConfirm: ()=>{
                            this.activeTab = 0;
                        }
                    })

                }
                
                //alert(err)
            })
        },
        clearForm(){
           
            this.shutterCount = 0;

            this.fields = {
                username: '',
                password: '',
                password_confirmation: '',
                lname: '',
                fname: '',
                mname: '',
                suffix: '',
                sex: '',
                role: '',
                branch_id: 0,
                salary_level: '',
                contact_no: '',
                province: '',
                city: '',
                brangay: '',
                street: '',
                descriptions: [],
            }

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

            //this.btnClass['is-loading'] = false;
            //console.log(this.btnClass)
        },

        showSize(){
            let videoImg = document.getElementById('video');
            let canvas1 = document.getElementById('canvas1');
           // alert('Video Width: ' + canvas1.width);
           //alert('screen width: ' + screen.width);
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
        },
        initSalaryLevels(){
            this.salary_levels = JSON.parse(this.propSalaryLevels)
        },

        initBranches(){
            this.branches = JSON.parse(this.propBranches)
        },

        debugMe: function(){

            this.fields = {
                username: 'aa',
                password: 'aa',
                password_confirmation: 'aa',
                lname: 'Dela Cruz',
                fname: 'Juan',
                mname: '',
                suffix: '',
                sex: 'MALE',
                salary_level: 2,
                role: 'EMPLOYEE',
                contact_no: '1234',
                province: '',
                city: '',
                brangay: '',
                street: '',
                descriptions: [],
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
        this.loadProvince();
        this.startCamera();
        this.initFaceDetectionControls();
        this.run();
        this.initSalaryLevels();
        this.initBranches()
    }
}
</script>

<style scoped src="../../../css/face-register.css"></style>
