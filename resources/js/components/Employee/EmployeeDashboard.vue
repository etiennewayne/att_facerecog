<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-6">
                    <div class="box">
                        <h1 class="title is-5">WELCOME 
                            <span style="font-style: italic;">{{ fullName }}</span></h1>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
export default {
    props: ['propUser'],
    data(){
        return{
            user: {},
        }
    },

    methods: {
        initData: function(){
            this.user = JSON.parse(this.propUser);
        }
    },
    mounted() {
        this.initData();
    },

    computed:{
        fullName: function(){
           return this.firstName + ' ' +  this.middleName + ' ' + this.lastName;
        },

        lastName(){
            return this.user ? this.user.lname : '';
        },
        firstName(){
            return this.user ? this.user.fname : '';
        },
        middleName(){
            return this.user ? this.user.mname : '';
        }
    }
}
</script>
<style scoped>
  
</style>
