<template>

    <div>
        <div style="display: flex; justify-content: center; margin-bottom: 20px;">
            <b-select v-model="selectedMonth" @input="loadDtr">
                <option v-for="(item, index) in months" :key="index"
                    :value="{ id: item.monthKey, monthName: item.monthName }">{{ item.monthName }}</option>
            </b-select>
        </div>

        <div class="print-form">
            <div class="print-title">Daily Time Record</div>
            <div class="dtr-name">{{ fullName }}</div>
            <div class="dtr-month">Month of: <span style="font-weight: bold;">{{ selectedMonth.monthName }}</span></div>

            <div>
                <table class="dtr-table">
                    <tr>
                        <td></td>
                    </tr>
                </table>
            </div>

        </div>
    </div>

</template>

<script>
import { assertExpressionStatement } from '@babel/types';

export default {
    props: ['propId', 'propData'],
    data(){
        return{
            data: null,
            selectedMonth: 1,
            
            dtrs: [],

            months: [
                {
                    'monthKey': 1,
                    'monthName': 'JANUARY'
                },
                {
                    'monthKey': 2,
                    'monthName': 'FEBRUARY'
                },
                {
                    'monthKey': 1,
                    'monthName': 'MARCH'
                },
                {
                    'monthKey': 4,
                    'monthName': 'APRIL'
                },
                {
                    'monthKey': 5,
                    'monthName': 'MAY'
                },
                {
                    'monthKey': 6,
                    'monthName': 'JUNE'
                },
                {
                    'monthKey': 7,
                    'monthName': 'JULY'
                },
                {
                    'monthKey': 8,
                    'monthName': 'AUGUST'
                },
                {
                    'monthKey': 9,
                    'monthName': 'SEPTEMBER'
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
        }
    },
    methods: {
        initData: function(){
            this.data = JSON.parse(this.propData);
            console.log(this.data)
        },

        loadDtr(){
            axios.get('/get-user-dtr/' + this.data[0].user.user_id).then(res=>{
                this.dtrs = res.data;
            })
        }
    },
    mounted() {
        this.initData()
    },

    computed:{
        fullName(){
            if(this.data){
                return this.data[0].user.fname + ' ' + this.data[0].user.mname + ' ' + this.data[0].user.lname;
            }

        }
    }
}
</script>
