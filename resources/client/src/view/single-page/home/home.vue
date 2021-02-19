<template>
    <div class="test-content">
        <Card :bordered="false" class="card-Hi">
            <p slot="title">Hi, {{userName}}</p>

        </Card>
    </div>
</template>


<script>

    import {mapState} from 'vuex';
    import {STATUS_E_C_REVIEW_NOT, STATUS_E_C_REVIEWED, STATUS_E_C_REVIEWING} from "@common@/constant/e_c_status";

    const ICON_COLOR_SELECTED = '#2d8cf0';
    const ICON_COLOR_DEFAULT = '#666666';


    export default {
        data() {
            return {
                e_c_text: '企业认证',
                mobile_text: '',
                e_c_color: ICON_COLOR_DEFAULT,
                switch1: true,
                buttonSize: 'large',
            }
        },
        methods: {
            mouseOver() {
                if (this.e_c_status == STATUS_E_C_REVIEWED) {
                    this.e_c_text = '查看设置'
                }
            },
            mouseOut() {
                if (this.e_c_status == STATUS_E_C_REVIEWED) {
                    this.e_c_text = '已认证'
                } else {
                    this.e_c_text = '企业认证'
                }
            },
            mouseOverMobile() {
                this.mobile_text = '修改手机'
            },
            mouseOutMobile() {
                this.mobile_text = this.mobile
            },
            rowClassName(row, index) {
                return 'demo-table-info-row';
            },
            change(status) {
                // this.$Message.info('开关状态：' + status);
            },
            setECPerformance(value) {
                switch (value) {
                    case STATUS_E_C_REVIEW_NOT:
                        this.e_c_text = '企业认证';
                        this.e_c_color = ICON_COLOR_DEFAULT;
                        break
                    case STATUS_E_C_REVIEWING:
                        this.e_c_text = '企业认证';
                        this.e_c_color = ICON_COLOR_DEFAULT;
                        break
                    case STATUS_E_C_REVIEWED:
                        this.e_c_text = '已认证';
                        this.e_c_color = ICON_COLOR_SELECTED;
                        break
                }
            }
        }
        ,
        computed: {
            ...mapState({
                smsCredit: state => state.remain_credit.smsCredit,
                contact_count: state => state.user.contact_count,
                userName: state => state.user.userName,
                mobile: state => state.user.mobile,
                e_c_status: state => state.user.e_c_status,
            })


        }
        ,
        watch: {
            e_c_status(value) {
                this.setECPerformance(value);
            }
        }
        ,
        mounted() {
            this.setECPerformance(this.e_c_status);
            this.mobile_text = this.mobile;
        }
    }

</script>


<style>
    @import "~icon_cus/iconfont.css";

    .Hi-icon {
        display: block;
        margin: 0 auto;
        height: 30px;
    }

    .card-Hi a.hover, .card-Hi a.hover .Hi-icon {
        color: #2d8cf0;
    }

    .card-Hi .cus-row {
        text-align: center;
        line-height: 3;
    }

    .card-Hi .cus-row a {
        color: #222222;
    }

    .card-Hi .cus-row a:hover {
        color: #2d8cf0;
    }

    .card-content {
        margin: 10px 0;
    }

    .ivu-card {
        border: 1px solid #e7e7e7;
    }

    .card-content .ivu-card-body {
        padding: 0;
    }

    .col-m1 .Balance {
        margin: 40px 10px 40px 20px;
    }

    .col-m2 .Balance {
        margin: 40px 10px;
    }

    .col-m3 .Balance {
        margin: 40px 20px 40px 10px;
    }

    .Balance {
        border: 1px solid #e7e7e7;
        text-align: center;
    }

    .Balance .ivu-card-body {
        padding: 0;
    }

    .Balance h3 {
        font-size: 28px;
        color: #ffffff;
        font-weight: bold;
        line-height: 30px;
        padding: 12px 0;
    }

    .Balance h3.color1 {
        background: #f79535;
    }

    .Balance h3.color2 {
        background: #30a5ff;
    }

    .Balance h3.color3 {
        background: #1ebfae;
    }

    .Balance p {
        font-size: 14px;
        color: #666666;
        line-height: 40px;
    }

    .switch-col {
        text-align: center;
        background-color: #f6f6f6;
        padding: 40px 0;
    }

    .switch-col p {
        font-size: 16px;
        color: #444444;
        display: inline-block;
        vertical-align: top;
        line-height: 38px;
    }

    .switch-col .ivu-switch {
        width: 56px;
        height: 32px;
        border: 3px solid #cccccc;
        background-color: #ffffff;
        margin: 0 15px;
    }

    .switch-col .ivu-switch-checked {
        border: 3px solid #2d8cf0;
    }

    .switch-col .ivu-switch:after {
        background-color: #cccccc;
        left: 3px;
        top: 3px;
        width: 20px;
        height: 20px;
    }

    .switch-col .ivu-switch-checked:after {
        left: 26px;
        background-color: #2d8cf0;
    }

    .switch-col .ivu-btn {
        display: block;
        margin: 20px auto;
        border-radius: 0;
        height: 40px;
        padding: 0 25px;
        font-size: 16px;
    }

    .switch-col .ivu-btn-primary {
        background-color: #f6652b;
        border-color: #f6652b;
    }

    .switch-col .ivu-btn-primary:hover {
        background-color: #f6812b;
        border-color: #f6812b;
    }

    .tab-box.ivu-card {
        background: none;
        border-radius: 0;
        box-shadow: none;
    }

    .tab-box.ivu-card-bordered, .tab-box.ivu-card:hover {
        border: none;
        box-shadow: none;
    }

    .tab-box .ivu-card-body {
        padding: 0;
    }

    .tab-box .tab-menu .ivu-tabs-bar {
        display: inline-block;
        margin-left: 90px;
        vertical-align: middle;
    }

    .tab-box .tab-menu .ivu-tabs-nav {
        background: #ffffff;
    }

    .tab-box p.title {
        position: absolute;
        padding: 8px 5px;
        font-size: 16px;
        color: #222222;
    }

    .mail-send .ivu-col .content {
        border: 1px solid #e7e7e7;
        background: #ffffff;
        margin-right: 20px;
        border-radius: 4px;
        transition: all .2s ease-in-out;
    }

    .mail-send .ivu-col .content:hover {
        box-shadow: 0 1px 6px rgba(0, 0, 0, .2);
        border-color: #eee;
    }

    .SMS-send .ivu-col {
        background: #ffffff;
    }

    .SMS-send .send-title {
        text-align: left;
    }

    .demo-Circle-custom {
        top: 0;
        position: relative;
        height: 190px;
        width: 190px;
    }

    .demo-Circle-custom h1 {
        position: absolute;
        right: 0;
        left: 0;
        text-align: center;
        width: 100%;
        line-height: 190px;
    }

    .demo-Circle-custom p {
        background: #fff4c0;
        padding: 5px 0;
        position: absolute;
        right: 0;
        top: 20px;
    }

    .demo-Circle-custom {

    &
    h1 {
        color: #3f414d;
        font-size: 28px;
        font-weight: normal;
    }

    &
    p {
        color: #657180;
        font-size: 14px;
        margin: 10px 0 15px;
    }

    &
    span {
        display: block;
        padding-top: 15px;
        color: #657180;
        font-size: 14px;

    &
    :before {
        content: '';
        display: block;
        width: 50px;
        height: 1px;
        margin: 0 auto;
        background: #e0e3e6;
        position: relative;
        top: -15px;
    }

    ;
    }
    &
    span i {
        font-style: normal;
        color: #3f414d;
    }

    }
    .mail-result {
        background: #f6f6f6;
        border-top: 1px solid #dfdfdf;
        padding: 20px;
    }

    .mail-result .ivu-col {
        text-align: center;
    }

    .mail-result .ivu-col {
        line-height: 26px;
    }

    .mail-result .send-icon {
        width: 56px;
        height: 56px;
        margin: 10px auto;
        text-align: center;
        color: #0b87dd;
        border: 2px solid #30a5ff;
        border-radius: 50%;
    }

    .mail-result .ivu-col .percentage {
        border: 2px solid #30a5ff;
        border-radius: 50%;
        width: 56px;
        height: 56px;
        line-height: 56px;
        text-align: center;
        font-size: 16px;
        color: #0b87dd;
        margin: 10px auto;
    }

    .mail-result .ivu-col h3 {
        font-size: 20px;
        color: #333333;
    }

    .mail-result .ivu-col p {
        font-size: 14px;
        color: #888888;
    }

    .send-result {
        background: #ffffff;
        border-left: 1px solid #dfdfdf;
        border-top: 1px solid #dfdfdf;
        margin: 20px 0;
    }

    .SMS-send .send-result .ivu-col {
        line-height: 36px;
        border-bottom: 1px solid #dfdfdf;
        border-right: 1px solid #dfdfdf;
    }

    .SMS-send .send-result .ivu-col i {
        vertical-align: middle;
        display: inline-block;
        width: 4px;
        height: 14px;
        margin-right: 5px;
    }

    .SMS-send .send-result .ivu-col i.green {
        background: #19be6b;
    }

    .SMS-send .send-result .ivu-col i.orange {
        background: #f6652b;
    }

    .ivu-chart-circle {
        margin: 60px auto 40px;
    }
</style>
