<template>
    <div>
        <div class="admin-container">
            <div class="welcome-text">
                ADMIN PANEL
            </div>


      
        </div>

        <!-- <div class="section">
            <div class="buttons">
                <b-button type="is-info" @click="sendSMS" label="Send"></b-button>
            </div>
        </div> -->


    </div>
</template>

<script>
export default {
	data(){
		return{
            info: {},

		}
	},

	methods:{
        

	},

    mounted() {
        
    }
}
</script>

<style scoped>
    .w-head-title{
        font-weight: bold;
    }
    .w-content{
        text-align: center;
        font-weight: bold;
        font-size: 2em;
    }

    .admin-container{
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: -25px;
        /* border: 1px solid red; */
    }
    .welcome-text{
        font-weight: bold;
        font-size: 1.8em;
    }
</style>
