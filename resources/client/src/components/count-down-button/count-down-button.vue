<template>
    <Button :disabled="disable" :type="type" @click.native="click">
        {{getCode}}
    </Button>
</template>

<script>
    import {debounce} from "@/libs/tools";
    import {localRead, localSave} from "@/libs/util";

    export default {
        name: "count-down-button",
        props: {
            type: {
                type: String,
                default: 'default'
            },
            buttonContent: {
                type: String,
                default: '获取验证码'
            },
            buttonContentResend: {
                type: String,
                default: ''
            },
            localStorageKey: {
                type: String,
                default: 'count_time'
            },
            countIngContent: {
                type: String,
                default: 's后重发'
            },
            countTime: {
                type: Number,
                default: 60
            },
            beforClick: {
                type: Function,
                default() {
                    return new Promise((resolve, reject) => {
                        resolve(true)
                    })
                }
            }

        },
        data() {
            return {
                disable: false,
                count: this.countTime,
                getCode: this.buttonContent,
            }
        },
        methods: {


            countMethod() {
                var countDown = setInterval(() => {
                    //最开始时候分发一次事件,提供回调
                    if (this.count == this.countTime) {
                        this.$emit('startCount');
                    }
                    if (this.count < 1) {
                        this.disable = false;
                        this.getCode = this.buttonContentResend ? this.buttonContentResend : this.buttonContent;
                        this.count = this.countTime;
                        clearInterval(countDown);
                    } else {
                        localSave(this.localStorageKey, this.count--);
                        this.disable = true;
                        this.getCode = this.count + this.countIngContent;
                    }
                }, 1000);
            },
            //用了2个闭包?
            click: debounce(function () {
                this.beforClick().then((result) => {
                    if (result) {
                        this.countMethod();
                    }
                }).catch(error => {
                })
            }, 2000, true),

        }
        ,
        mounted() {
            let count = localRead(this.localStorageKey);
            this.count = count ? count : this.countTime;
            if (this.count != this.countTime) {
                this.countMethod();
            }
        }
    }
</script>

<style scoped>

</style>
