<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-6-desktop is-8-tablet">
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
                </div><!-- column -->
            </div><!-- cols -->
        </div><!-- section s-->
    </div>

</template>

<script>

export default {
    props: ['propId', 'propData'],
    data(){
        return{
            fields:{
                user_id: 0,
                nDateTime: new Date(),
                t_status: null,
            },
            employee_fullname: '',

            id: 0,
        }
    },

    methods: {
        submit: function(){
            if(this.id > 0){
                axios.put('/daily-time-records/' + this.id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                window.location = '/daily-time-records';
                            }
                        })
                    }
                })
            }else{
                axios.post('/daily-time-records', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            onConfirm: () => {

                                window.location = '/daily-time-records';
                            }
                        })
                    }
                })
            }

        },

        emitBrowseEmployee: function(row){
            this.fields.user_id =  row.user_id;
            this.employee_fullname = row.lname + ', ' + row.fname + ' ' + row.mname;
            console.log(this.fields)
        },
        initData(){
            this.id = parseInt(this.propId);

            if(this.id > 0){
                //update
                const data = JSON.parse(this.propData);
                this.employee_fullname = data.user.lname + ', ' + data.user.fname + " " + data.user.mname;
                this.fields.nDateTime = new Date(data.date_record + ' ' + data.time_record);
                this.fields.t_status = data.time_status;
                this.fields.user_id = data.user_id;
            }
        }

    },

    mounted(){
        this.initData();
    }
}
</script>
