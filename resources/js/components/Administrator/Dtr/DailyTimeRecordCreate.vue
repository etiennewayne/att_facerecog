<template>
    <div>
        <div class="section">
            <div class="box">
                <div class="has-text-weight-bold mb-2" style="font-size: 1.3em;">
                    ADD RECORD
                </div>

                <div>
                    <form @submit.prevent="submit">
                        <div class="columns">
                            <div class="column">
                                <b-field label="Select Date & Time">
                                    <b-datetimepicker v-model="fields.nDateTime" placeholder="Select Date & Time"></b-datetimepicker>
                                </b-field>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <b-field label="Time Status" expanded>
                                    <b-select v-model="fields.t_status" expanded>
                                        <option value="in_am">IN AM</option>
                                        <option value="out_am">OUT AM</option>

                                        <option value="in_pm">IN PM</option>
                                        <option value="out_pm">OUT PM</option>

                                    </b-select>
                                </b-field>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <modal-browse-employees :prop-employee="employee_fullname"
                                                        @browseEmployees="emitBrowseEmployee($event)"></modal-browse-employees>
                            </div>
                        </div>
                        <div class="buttons">
                            <button class="button is-primary">SAVE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

export default {

    data(){
        return{
            fields:{
                user_id: 0,
                nDateTime: new Date(),
                t_status: null,
            },
            employee_fullname: '',

        }
    },

    methods: {
        submit: function(){
            axios.post('/daily-time-records', this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'SAVED!',
                        message: 'Successfully saved.',
                        type: 'is-success',
                        onConfirm: () => {
                            this.loadAsyncData();
                            this.clearFields();
                            this.global_id = 0;
                            this.isModalCreate = false;
                        }
                    })
                }
            })
        },

        emitBrowseEmployee: function(row){
            this.fields.user_id =  row.user_id;
            this.employee_fullname = row.lname + ', ' + row.fname + ' ' + row.mname;
            console.log(this.fields)
        }
    }
}
</script>
