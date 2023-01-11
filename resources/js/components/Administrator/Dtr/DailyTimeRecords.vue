<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8-desktop is-10-tablet">
                    <div class="box">

                        <div class="is-flex mb-2" style="font-size: 20px; font-weight: bold;">DAILY TIME RECORDS</div>

                        <div class="level">
                            <div class="level-left">
                                <b-field label="Page">
                                    <b-select v-model="perPage" @input="setPerPage">
                                        <option value="5">5 per page</option>
                                        <option value="10">10 per page</option>
                                        <option value="15">15 per page</option>
                                        <option value="20">20 per page</option>
                                    </b-select>
                                </b-field>
                            </div>

                            <div class="level-right">
                                <div class="level-item">
                                    <b-field label="Search">
                                        <b-input type="text"
                                                 v-model="search.lname" placeholder="Search Lastname"
                                                 @keyup.native.enter="loadAsyncData"/>
                                        <p class="control">
                                            <b-tooltip label="Search" type="is-success">
                                                <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                            </b-tooltip>
                                        </p>
                                    </b-field>
                                </div>
                            </div>
                        </div>
                        <div class="level">
                            <div class="level-left">
                                <b-field label="Select Date" label-position="on-border">
                                    <b-datepicker v-model="search.searchdate">
                                        <b-button label="Clear"
                                            type="is-danger"
                                            icon-left="close"
                                            outlined
                                            @click="search.searchdate = null"></b-button>
                                    </b-datepicker>
                                    <p class="control">
                                        <b-tooltip label="Search" type="is-success">
                                            <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                        </b-tooltip>
                                    </p>
                                </b-field>
                            </div>

                            <div class="level-right">
                                <b-field label="Select Branch" label-position="on-border">
                                    <b-select v-model="search.branch">
                                        <option v-for="(item,index) in branches" :value="item.branch_id" :key="index">{{ item.branch_name }}</option>
                                    </b-select>
                                    <p class="control">
                                        <b-tooltip label="Search" type="is-success">
                                            <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                        </b-tooltip>
                                    </p>
                                </b-field>
                            </div>
                        </div>

                        <div class="buttons mt-3">
                            <b-button @click="newDTR" icon-left="plus" class="is-success">NEW</b-button>
                        </div>


                        <b-table
                            :data="data"
                            :loading="loading"
                            paginated
                            backend-pagination
                            :total="total"
                            :pagination-rounded="true"
                            :per-page="perPage"
                            @page-change="onPageChange"
                            aria-next-label="Next page"
                            aria-previous-label="Previous page"
                            aria-page-label="Page"
                            aria-current-label="Current page"
                            backend-sorting
                            :default-sort-direction="defaultSortDirection"
                            @sort="onSort">

                            <b-table-column field="id" label="ID" sortable v-slot="props">
                                {{ props.row.id }}
                            </b-table-column>

                            <b-table-column field="name" label="Name" sortable v-slot="props">
                                {{ props.row.user.lname }}, {{  props.row.user.fname }} {{ props.row.user.mname }}
                            </b-table-column>

                            <b-table-column field="sex" label="Sex" v-slot="props">
                                {{ props.row.user.sex }}
                            </b-table-column>

                            <b-table-column field="date_record" label="Record" v-slot="props">
                                {{ new Date(props.row.dt_record).toLocaleDateString() }} - {{ new Date(props.row.dt_record).toTimeString() | formatTime }}
                            </b-table-column>

                            <b-table-column field="time_status" label="Time Status" v-slot="props">
                                <span v-if="props.row.time_status == 'in_am'">IN AM</span>
                                <span v-if="props.row.time_status == 'out_am'">OUT AM</span>
                                <span v-if="props.row.time_status == 'in_pm'">IN PM</span>
                                <span v-if="props.row.time_status == 'out_pm'">OUT PM</span>

                            </b-table-column>

                            <b-table-column label="Action" v-slot="props">
                                <div class="is-flex">
                                    <b-tooltip label="Edit" type="is-warning">
                                        <b-button class="button is-small mr-1" tag="a" icon-right="pencil" @click="openUpdate(props.row.id)"></b-button>
                                    </b-tooltip>
                                    <b-tooltip label="Delete" type="is-danger">
                                        <b-button class="button is-small mr-1" icon-right="delete" @click="confirmDelete(props.row.id)"></b-button>
                                    </b-tooltip>
                                    <b-tooltip label="DTR" type="is-warning">
                                        <b-button class="button is-small mr-1" icon-right="note-multiple-outline" @click="displayDTR(props.row.user.user_id)"></b-button>
                                    </b-tooltip>

                                </div>
                            </b-table-column>
                        </b-table>

                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->



    </div>
</template>

<script>

export default{
    props: ['propBranches'],

    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',


            global_id : 0,

            search: {
                lname: '',
                searchdate: null,
                branch: '',
            },

            isModalCreate: false,
            modalResetPassword: false,

            fields: {},
            errors: {},


            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

            branches: [],
        }
    },

    methods: {
        /*
        * Load async data
        */
    
        loadAsyncData() {
            let sdate = null;
            if(this.search.searchdate){
               sdate = new Date(this.search.searchdate);
               sdate = sdate.getFullYear() + '-' + (sdate.getMonth() + 1)  + '-' + sdate.getDate();
            }
            

            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `lname=${this.search.lname}`,
                `searchdate=${sdate}`,
                `branch=${this.search.branch}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')


            this.loading = true
            axios.get(`/get-daily-time-records?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total
                    if (data.total / this.perPage > 1000) {
                        currentTotal = this.perPage * 1000
                    }

                    this.total = currentTotal
                    data.data.forEach((item) => {
                        //item.release_date = item.release_date ? item.release_date.replace(/-/g, '/') : null
                        this.data.push(item)
                    })
                    this.loading = false
                })
                .catch((error) => {
                    this.data = []
                    this.total = 0
                    this.loading = false
                    throw error
                })
        },
        /*
        * Handle page-change event
        */
        onPageChange(page) {
            this.page = page
            this.loadAsyncData()
        },

        onSort(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadAsyncData()
        },

        setPerPage(){
            this.loadAsyncData()
        },

        openModal(){
            this.isModalCreate=true;
            this.fields = {};
            this.errors = {};
        },

        newDTR(){
            window.location = '/daily-time-records/create';
        },



        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/daily-time-records/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },


        //update code here
        openUpdate: function(data_id){
            window.location = '/daily-time-records/' + data_id + '/edit';
        },

        displayDTR(dataId){
            window.location = '/display-dtr/' + dataId;
        },

        initData(){
            this.branches = JSON.parse(this.propBranches)
        }




    },

    mounted() {
        //this.loadOffices();
        this.loadAsyncData();
        this.initData();

    }
}
</script>


<style>

.table > tbody > tr {
    /* background-color: blue; */
    transition: background-color 0.5s ease;
}

.table > tbody > tr:hover {
    background-color: rgb(233, 233, 233);

}

</style>
