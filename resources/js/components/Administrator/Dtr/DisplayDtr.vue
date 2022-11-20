<template>

    <div>
        <div style="display: flex; justify-content: center; margin-bottom: 20px;">
            <b-select v-model="selectedMonth">
                <option v-for="(index, item) in months" :key="index"
                    :value="item.index">{{ item.monthName }}</option>

            </b-select>
        </div>

        <div class="print-form">
            <div class="print-title">Daily Time Record</div>
            <div class="dtr-name">{{ fullName }}</div>
            <div class="dtr-month">Month of: <span style="font-weight: bold;">{{ November }}</span></div>

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
export default {
    props: ['propId', 'propData'],
    data(){
        return{
            data: null,
            selectedMonth: 1,

            months: [
                {
                    index: 1,
                    monthName: 'JANUARY'
                },
                {
                    index: 2,
                    monthName: 'FEBRUARY'
                },
                {
                    index: 1,
                    monthName: 'MARCH'
                },
                {
                    index: 4,
                    monthName: 'APRIL'
                },
                {
                    index: 5,
                    monthName: 'MAY'
                },
                {
                    index: 6,
                    monthName: 'JUNE'
                },
                {
                    index: 7,
                    monthName: 'JULY'
                },
                {
                    index: 8,
                    monthName: 'AUGUST'
                },
                {
                    index: 9,
                    monthName: 'SEPTEMBER'
                },
                {
                    index: 10,
                    monthName: 'OCTOBER'
                },
                {
                    index: 11,
                    monthName: 'NOVEMBER'
                },
                {
                    index: 12,
                    monthName: 'DECEMBER'
                },
            ],
        }
    },
    methods: {
        initData: function(){
            this.data = JSON.parse(this.propData);
            console.log(this.data)
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
