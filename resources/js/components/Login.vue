<template>
    <div class="login-wrapper">
        <div class="columns is-centered m-2">
            <div class="column is-8-desktop is-10-tablet">

                <form @submit.prevent="submit">
                    <div class="box box-up-line">
                        <div class="title is-4">
                            SECURITY CHECK
                        </div>
                        <hr>
    
                        <div class="panel-body">
    
                            <div class="columns is-centered">
                                <div class="column">
                                    <img src="/img/login-img.png" />
                                </div>
    
                                <div class="column">
                                    <b-field label="Username" label-position="on-border"
                                        :type="this.errors.username ? 'is-danger':''"
                                        :message="this.errors.username ? this.errors.username[0] : ''">
                                        <b-input type="text" v-model="fields.username" placeholder="Username" />
                                    </b-field>
    
                                    <b-field label="Password" label-position="on-border">
                                        <b-input type="password" v-model="fields.password" password-reveal placeholder="Password" />
                                    </b-field>
                                    <div class="buttons is-right">
                                        <button class="button is-primary is-fullwidth">
                                            <b-icon icon="login-variant" class="mr-3"></b-icon>
                                            LOGIN
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
    
                            
                        </div>
                    </div>
                </form>
            </div> <!--col-->

        </div><!--cols-->
    </div>
</template>

<script>

export default {
    data(){
        return {
            fields: {
                username: null,
                password: null,
            },

            errors: {},
        }
    },

    methods: {
        submit: function(){
            axios.post('/login', this.fields).then(res=>{
                console.log(res.data)
                if(res.data.role === 'ADMINISTRATOR'){
                    window.location = '/home';
                }
                if(res.data.role === 'STAFF'){
                    window.location = '/face-recog';
                }
                if(res.data.role === 'EMPLOYEE'){
                    window.location = '/employee/dtr';
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
            });
        }
    }
}
</script>


<style scoped>
    .login-wrapper{
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

 

    .box{
        border: 1px solid rgb(223, 223, 223);
    }

    .box-up-line{
        border-top: 2px solid blue;
    }


</style>
