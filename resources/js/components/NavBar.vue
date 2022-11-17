<template>
    <b-navbar>
        <template #brand>
            <b-navbar-item>
                <h1 class="title is-4">ATTENDANCE SYSTEM</h1>
            </b-navbar-item>
        </template>

        <template #start>


        </template>

        <template #end>
            <b-navbar-item href="/">
                HOME
            </b-navbar-item>
            <!-- <b-navbar-item href="/about">
                ABOUT
            </b-navbar-item> -->
            <b-navbar-item href="/face-register">
               Face Register
            </b-navbar-item>
            <b-navbar-item tag="div">
                <div v-if="!currentLogin" class="buttons">
                    <a class="button is-light" href="/login">
                        Log in
                    </a>
                </div>
                <div v-else class="buttons">
                    <b-button label="LOGOUT" icon-left="logout" @click="logout">
                    </b-button>
                </div>
            </b-navbar-item>
        </template>
    </b-navbar>

</template>

<script>
export default {
   

    data(){
        return{
            user: null,
        }
    },

    methods:{
        initData: function(){
            axios.get('/load-user').then(res=>{
                this.user = res.data;
            })
        },

     

        logout(){
            axios.post('/logout').then(()=>{
                window.location = '/';
            })
        }
    },

    mounted(){
        this.initData();
    },

    computed: {
        showName(){
            if(this.user){
                return this.user.fname.toUpperCase();
            }
            return '';
        },
        currentLogin(){
            return !!this.user;
        }
    }

}
</script>

<style>
    .navbar-item{
        font-weight: bold;
    }
</style>
