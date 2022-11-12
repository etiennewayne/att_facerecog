<template>

    <div>
        <div class="form-container">
            <div class="form-header">
                <div class="form-title">REGISTER FACE HERE</div>
            </div>
            <div class="form-body">
                <div class="webcam-container">
                    <video class="webcam" id="video" width="320" height="240" autoplay></video>
                </div>
                <div class="buttons is-centered mt-2">
                    <button class="button is-primary" @click="snap">Snap</button>
                </div>

                <div class="capture-container">
                    <div class="canvas-container">
                        <canvas id="canvas1" width="320" height="240"></canvas>
                    </div>
                    
                    <canvas id="canvas2" width="320" height="240"></canvas>
                    <canvas id="canvas3" width="320" height="240"></canvas>


                    <img id="img" />
                </div>
            </div>
            <div class="footer">

            </div>
        </div>

        
        <!-- Webcam.min.js -->
    </div>



</template>    

<!-- <script src='/js/webcam/webcam.js' defer></script> -->
<script>
import { tSMethodSignature } from '@babel/types';


export default {
    data(){
        return{
            shutterCount: 0,
        }
    },
    methods:{

        async startCamera(){
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
        }
    },

    mounted(){
        this.startCamera();
    }
}        
</script>

<style scoped src="../../../css/face-register.css"></style>