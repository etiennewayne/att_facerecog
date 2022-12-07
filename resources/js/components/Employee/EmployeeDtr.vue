<template>

    <div class="section">
        <div class="columns">
            <div class="column">
                <div class="filter-print">
                    <b-field label="Filter" label-position="on-border">
                        <b-select v-model="selectedMonth">
                            <option v-for="(item, index) in months" :key="index"
                                    :value="{ id: item.monthKey, monthName: item.monthName }">{{ item.monthName }}</option>
                        </b-select>
                        <b-select v-model="selectedYear">
                            <option v-for="(item, index) in years" :key="index"
                                    :value="item">{{ item }}</option>
                        </b-select>
                        <b-select v-model="half">
                            <option value="first">1st Half</option>
                            <option value="second">2nd Half</option>
                        </b-select>
                        <p class="control">
                            <b-button type="is-success" label="..." @click="loadDtr"></b-button>
                        </p>
                    </b-field>
                </div>

                <div class="print-form">
                    <div class="print-title">Daily Time Record</div>
                    <div class="dtr-name">{{ fullName }}</div>
                    <div class="dtr-month">Month of: 
                        <span style="font-weight: bold;">{{ selectedMonth.monthName }}, 
                            <span v-if="half==='first'">1 - 15</span>
                            <span v-else>16 - 31</span>
                        </span>
                    </div>

                    <div>
                        <table class="dtr-table">
                            <tr>
                                <td rowspan="1" style="font-weight: bold;">Days</td>
                                <td colspan="2" style="text-align: center; font-weight: bold;">AM</td>
                                <td colspan="2" style="text-align: center; font-weight: bold;">PM</td>
                                <td rowspan="1"  style="text-align: center; font-weight: bold;">Deduction</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="time-header">IN</td>
                                <td class="time-header">OUT</td>
                                <td class="time-header">IN</td>
                                <td class="time-header">OUT</td>
                                <td></td>
                            </tr>
                            <tr v-for="(item, index) in dtrSheet" :key="index">
                                <td style="text-align: center;">{{ item.day }}</td>
                                <td class="time">
                                    <span v-if="item.in_am">{{ item.in_am | formatTime  }}</span>
                                </td>
                                <td class="time">
                                    <span v-if="item.out_am">{{ item.out_am | formatTime  }}</span>
                                </td>
                                <td class="time">
                                    <span v-if="item.in_pm">{{ item.in_pm | formatTime  }}</span>
                                </td>
                                <td class="time">
                                    <span v-if="item.out_pm">{{ item.out_pm | formatTime  }}</span>
                                </td>
                                <td>
                                    <span>{{ item.deduction }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div><!--col-->

            <div class="column">
                <div class="payslip">
                    <div class="payslip-header">
                        <div class="payslip-header-text">PAYSLIP</div>
                        <table style="margin: 10px auto" class="table-payslip">
                            <tr>
                                <td>Name: </td>
                                <td>{{ fullName }}</td>
                            </tr>
                            <tr>
                                <td>Rate/Day: </td>
                                <td class="align-right">{{ user.salary_level.salary }}</td>
                            </tr>
                            <tr>
                                <td>No. of days: </td>
                                <td class="align-right">{{ totalDays }}</td>
                                
                            </tr>
                            <tr>
                                <td>Overtime: </td>
                                <td class="align-right">{{ totalOvertime }}</td>
                                
                            </tr>
                            <tr>
                                <td>Deduction: </td>
                                <td class="align-right">{{ totalDeduction }}</td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid gray;" colspan="2"></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">NET PAY: </td>
                                <td style="font-weight: bold;" class="align-right">{{ totalSalary }} </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div><!--cols -->
        
    </div>

</template>

<script>

export default {
    props: ['propUser'],
    data(){
        return{
            user: {
                salary_level: {
                    salary: 0
                }
            },
            
            selectedYear: new Date().getFullYear(),

            dtrs: [],
            dtrSheet: [],
            years: [],
            half: 'first',
            totalDays: 0,
            totalDeduction: 0,
            totalOvertime: 0,

            days: 31,

            months: [
                {
                    monthName: 1,
                    monthName: 'JANUARY'
                },
                {
                    'monthKey': 2,
                    monthName: 'FEBRUARY'
                },
                {
                    'monthKey': 1,
                    monthName: 'MARCH'
                },
                {
                    'monthKey': 4,
                    monthName: 'APRIL'
                },
                {
                    'monthKey': 5,
                    monthName: 'MAY'
                },
                {
                    'monthKey': 6,
                    monthName: 'JUNE'
                },
                {
                    'monthKey': 7,
                    monthName: 'JULY'
                },
                {
                    'monthKey': 8,
                    monthName: 'AUGUST'
                },
                {
                    'monthKey': 9,
                    monthName: 'SEPTEMBER'
                },
                {
                    'monthKey': 10,
                    monthName: 'OCTOBER'
                },
                {
                    'monthKey': 11,
                    monthName: 'NOVEMBER'
                },
                {
                    'monthKey': 12,
                    monthName: 'DECEMBER'
                },
            ],

            selectedMonth: {
                monthKey: new Date().getMonth(),
            },
        }
    },
    methods: {
        initData: function(){
            this.user = JSON.parse(this.propUser);
            //console.log(this.user);
        },

        loadDtr(){

            this.dtrSheet = [];

            const params = [
                `user=${this.user.user_id}`,
                `month=${this.selectedMonth.id}`,
                `year=${this.selectedYear}`,
                `half=${this.half}`,
            ].join('&');


            axios.get(`/get-user-dtr?${params}`).then(res=>{
                this.dtrs = res.data;
               this.createDTR()
            })
        },

        loadYears(){
            let n = new Date().getFullYear();

            for(let i = n - 2; i <= n; i ++){
                this.years.push(i)
            }

        },

        createDTR(){
            
            let salary = 0;
            this.totalDays = 0;
            let day = 1;
            this.totalOvertime = 0;

            if(this.dtrs.length > 0){
                this.totalDeduction = 0;

                for(day = 1; day <=31; day++){
                    let in_am = null;
                    let out_am = null;
                    let in_pm = null;
                    let out_pm = null;
                    let deduction = 0;
                    let overtime = 0;

                    //every day in am and pm is null

                    this.dtrs.forEach(el => {
                        //iterate records from database
                        let nday = new Date(el.dt_record).getDate();

                        if(nday === day){
                            //AM
                            if(el.time_status === 'in_am'){
                                in_am = new Date(el.dt_record).toTimeString();
                            }
                            if(el.time_status === 'out_am'){
                                out_am = new Date(el.dt_record).toTimeString();
                            }
                            //PM
                            if(el.time_status === 'in_pm'){
                                in_pm = new Date(el.dt_record).toTimeString();
                            }
                            if(el.time_status === 'out_pm'){
                                out_pm = new Date(el.dt_record).toTimeString();
                            }

                            //in AM computation
                            if(in_am != null && out_am != null){
                                
                                let inAm = new Date('2022-01-01 ' + in_am);
                                if(inAm > new Date('2022-01-01 08:00:00')){
                                    deduction = 20;
                                }
                                if(inAm > new Date('2022-01-01 08:15:00')){
                                    deduction += 20;
                                }
                                if(inAm >= new Date('2022-01-01 09:00:00')){
                                    deduction += 10;
                                }
                                if(inAm > new Date('2022-01-01 09:15:00')){
                                    deduction += 20;
                                }
                                if(inAm > new Date('2022-01-01 09:30:00')){
                                    deduction += 20;
                                }
                                if(inAm > new Date('2022-01-01 09:45:00')){
                                    deduction += 20;
                                }
                                
                                // let outAM = new Date('2022-01-01 ' + out_am);
                                // let msec =  outAM - inAm;
                                // let mm = Math.floor(msec/1000/60);

                                //console.log(mm)
                            }

                            //in PM computation
                            if(in_pm != null && out_pm != null){
                                
                                let inPM = new Date('2022-01-01 ' + in_pm);
                                if(inPM > new Date('2022-01-01 13:00:00')){
                                    deduction += 20;
                                }
                                if(inPM > new Date('2022-01-01 13:15:00')){
                                    deduction += 20;
                                }
                                if(inPM >= new Date('2022-01-01 14:00:00')){
                                    deduction += 10;
                                }
                                if(inPM > new Date('2022-01-01 14:15:00')){
                                    deduction += 20;
                                }
                                if(inPM > new Date('2022-01-01 14:30:00')){
                                    deduction += 20;
                                }
                                if(inPM > new Date('2022-01-01 14:45:00')){
                                    deduction += 20;
                                }
                                if(inPM >= new Date('2022-01-01 15:00:00')){
                                    deduction += 20;
                                }
                                
                                let outPM = new Date('2022-01-01 ' + out_pm);

                                if(outPM >= new Date('2022-01-01 18:00:00')){
                                    overtime = 50;
                                }

                                // let outAM = new Date('2022-01-01 ' + out_am);
                                // let msec =  outAM - inAm;
                                // let mm = Math.floor(msec/1000/60);

                                //console.log(mm)
                            }

                        } //if equal to day
                      
                    })//foreach

                    this.dtrSheet.push({
                        day: day,
                        in_am: in_am,
                        out_am: out_am,
                        in_pm: in_pm,
                        out_pm: out_pm,
                        deduction: deduction,
                        overtime: overtime
                    });

                    if(in_am != null && out_am != null){
                        this.totalDays += .5;
                    }
                    if( in_pm != null && out_pm != null){
                        this.totalDays += .5;
                    }

                    this.totalDeduction = this.totalDeduction + deduction;
                    this.totalOvertime = this.totalOvertime + overtime;
                }
                console.table(this.dtrSheet);
                //console.log('salary from db: ', this.user.salary_level.salary)
                console.log('salary: ', salary)
            }
        }
    },
    mounted() {
        this.loadYears()
        this.initData()
        //this.createDTR()
    },

    computed:{
        fullName(){
            if(this.user){
                return this.user.fname + ' ' + this.user.mname + ' ' + this.user.lname;
            }

        },
        salary(){
            if(this.user){
                return this.user.salary_level.salary;
            }else{
                return 0;
            }
        },

        totalSalary(){
            return ((this.totalDays * this.salary) - this.totalDeduction) + this.totalOvertime;
        }
    }
}
</script>
