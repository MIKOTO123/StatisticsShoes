<style lang="less">
    @import './login.less';
</style>

<template>
    <div class="login">
        <div style="padding-top: 50px; text-align: center;">
            <img src="~@/assets/images/login-logo-client.png" style="display: block; margin: 0 auto;"/>
            <p class="login-logo-txt">商品统计系统</p>
        </div>
        <div class="login-con">
            <Card icon="log-in" title="欢迎登录" :bordered="false">
                <div class="form-con">
                    <login-form
                        @on-success-valid="handleSubmit"
                        usernamePh="请输入用户名手机号"
                        pwdPh="请输入登录密码"
                        :userNameRules="userNameRules"
                    ></login-form>
                    <p class="login-tip">
                        <a href="javascript:void(0);" @click="clickForgetpwd">忘记密码</a>
                        <a href="javascript:void(0);" @click="clickRegist">免费注册</a>
                    </p>
                </div>
            </Card>
        </div>
    </div>
</template>

<script>
    import LoginForm from '_c/login-form'
    import {mapActions} from 'vuex'

    export default {
        data() {
            // const validateUsername = (rule, value, callback) => {
            //     // if (value.test)
            //     callback();
            // }
            return {
                userNameRules: [
                    {
                        required: true, message: '账号不能为空', transform(value) {
                            return value ? value.trim() : "";
                        }, trigger: 'blur', type: "string"
                    },
                ]
            }
        },
        components: {
            LoginForm
        },
        methods: {
            ...mapActions([
                'handleLogin',
                'getUserInfo'
            ]),
            clickForgetpwd() {
                this.$router.push({name: 'forgetpwd'})
            },

            clickRegist() {
                this.$router.push({name: 'register'})
            },
            handleSubmit({userName, password}) {
                this.handleLogin({userName, password}).then(res => {
                    this.getUserInfo().then(res => {
                        this.$router.push({
                            name: this.$config.homeName
                        })
                    })
                }).catch(err => {
                    //对err进行处理
                    // err.data.errors.name[0]//具体字段错误
                    // err.data.message//message大体错误
                    // console.log(err);

                    //校验头部
                    if (err.response.status === 429) {
                        //请求次数过于频繁
                        this.$Message.error('您的登录次数过于频繁,请稍后再试')
                    }

                    if (err.response.data) {
                        if (err.response.data.errors) {
                            this.$Message.error(err.response.data.errors.name[0])
                        }
                    }
                })
            }
        }
    }
</script>

<style scoped>
    .login-logo-txt{
        color: #ffffff;
        font-size: 18px;
        font-weight: bold;
        line-height: 42px;
        margin-bottom: 10px;
    }
    p.login-tip {
        text-align: right;
        font-size: 14px;
        margin: 10px 0;
    }
    p.login-tip a{
        margin-left: 10px;
    }
</style>
