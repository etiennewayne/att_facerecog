<template>
    <b-navbar shadow>
        <template #brand>
            <b-navbar-item>
                <h1 class="title is-4">ATTENDANCE SYSTEM</h1>
            </b-navbar-item>
        </template>

        <template #start>


        </template>

        <template #end>
            <b-navbar-item v-if="isAdmin" href="/home">
                Home
            </b-navbar-item>
            <b-navbar-item v-if="!isAdmin" href="/">
                Home
            </b-navbar-item>
            <!-- <b-navbar-item href="/about">
                ABOUT
            </b-navbar-item> -->

            <b-navbar-item v-if="isAdmin" href="/face-register" >
                Face Register
            </b-navbar-item>

            <b-navbar-item v-if="isAdmin" href="/daily-time-records" >
                DTR
            </b-navbar-item>

            <b-navbar-item v-if="isAdmin" href="/salary-level" >
                Salary Level
            </b-navbar-item>

            <b-navbar-item v-if="isAdmin" href="/accounts" >
                Accounts
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
   props: ['propUser'],

    data(){
        return{
            user: null,
        }
    },

    methods:{
        initData: async function(){
            await axios.get('/load-user').then(res=>{
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
        },
        isAdmin(){
            if(this.user)
                return this.user.role == 'ADMINISTRATOR';
        }
    }

}
</script>

<style>
    .navbar-item{
        font-weight: bold;
    }

    .navbar{
        border-bottom: 1px solid blue;
    }
</style>
