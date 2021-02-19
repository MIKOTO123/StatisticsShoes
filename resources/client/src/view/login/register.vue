<template>
    <div>
        <ul class="top">
            <li class="logo-con"><img src="~@/assets/images/logo-top-center.png"/></li>
        </ul>
        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="100" class="form">
            <p>注册</p>
            <FormItem label="用户名：" prop="name">
                <Input v-model="formValidate.name" placeholder="用户名" style="width: 320px;"></Input>
            </FormItem>
            <FormItem label="密码：" prop="password">
                <Input type="password" v-model="formValidate.password" placeholder="密码" style="width: 320px;"></Input>
            </FormItem>
            <FormItem label="手机号码：" prop="mobile">
                <Input v-model="formValidate.mobile" maxlength="11" placeholder="手机号" style="width: 320px;"></Input>
            </FormItem>
            <FormItem label="验证码：" prop="valid_code">
                <Input v-model="formValidate.valid_code" placeholder="密码"
                       maxlength="6"
                       style="width: 215px;"></Input>
                <count-down-button
                    local-storage-key="regist_send_code"
                    type="primary"
                    @startCount="sendRegisterCode"
                    :beforClick="beforClick"
                >

                </count-down-button>
            </FormItem>
            <FormItem prop="protocol">
                <CheckboxGroup v-model="formValidate.protocol">
                    <Checkbox label="Eat">我已阅读并同意《注册服务协议》</Checkbox>
                </CheckboxGroup>
            </FormItem>


            <FormItem class="submit">
                <Button :loading="registerLoading"  size="large" type="primary" @click="handleSubmit('formValidate')" long>注册</Button>                <p class="existing-account"><a href="javascript:void(0);" @click=" $router.push({ name: 'login' })">已有帐号，立即登录>></a>
                </p>
            </FormItem>

        </Form>
    </div>


</template>

<script>
    import InforCard from "../../components/info-card/infor-card";
    import CountDownButton from "_c/count-down-button/count-down-button";
    import {checkUserNameExist, registerUser, sendRegisterCode} from "@/api/user";

    import {mapActions, mapMutations} from 'vuex'

    export default {
        components: {InforCard, CountDownButton},
        data() {
            return {
                registerLoading: false,
                formValidate: {
                    name: '',
                    password: '',
                    mobile: '',
                    valid_code: '',
                    protocol: [],
                    code_id: '',
                },
                ruleValidate: {
                    name: [
                        {required: true, message: '请输入您的用户名', trigger: 'blur'},
                        {
                            validator: (rule, value, callback, options) => {

                                //一部请求,校验手机已经被占用
                                checkUserNameExist(value).then(result => {
                                    if (result.data.code > 0) {
                                        callback(new Error(result.data.message));
                                    } else {
                                        callback();
                                    }
                                    // if(data.reponse)
                                }).catch(err => {
                                    callback(new Error('未知原因'));
                                });

                            }
                            , trigger: 'blur'

                        }
                    ],
                    password: [
                        {required: true, message: '请输入您的密码', trigger: 'blur'},
                        {min: 6, max: 255, message: '至少填写6个字符'},
                        {
                            validator: (rule, value, callback, options) => {
                                //不能包含空格
                                if (value.indexOf(" ") != -1) {
                                    callback(new Error('不能包含空格'))
                                }
                                callback();
                            },
                        }
                    ],
                    mobile: [
                        {required: true, message: '请输入您的手机号码', trigger: 'change'},
                        {
                            validator: (rule, value, callback, options) => {
                                if (value && !(/^1[123456789]\d{9}$/.test(value))) {
                                    callback(new Error('手机号码有误，请重填'))
                                }

                                //一部请求,校验手机已经被占用
                                checkUserNameExist(value).then(result => {
                                    if (result.data.code > 0) {
                                        callback(new Error(result.data.message));
                                    } else {
                                        callback();
                                    }
                                    // if(data.reponse)
                                }).catch(err => {
                                    callback(err.response.data.errors.mobile[0]);
                                });

                            }
                            , trigger: 'blur'

                        }
                    ],
                    valid_code: [
                        {
                            required: true, message: '验证码不能为空', trigger: 'blur'
                        },
                        {
                            type: "integer", message: '至少填写6个数字', transform: (value) => {
                                return parseInt(value)
                            }
                            , trigger: 'blur'
                        }
                    ],
                    protocol: [
                        {required: true, type: 'array', min: 1, message: '请勾选服务协议', trigger: 'change'},
                    ]
                }
            }
        },
        methods: {


            ...mapMutations([
                'setToken'
            ]),

            ...mapActions([
                'getUserInfo'
            ]),


            beforClick: function (){
                let _this = this;
                //检测手机号码是否正确,符合要求的话,才能开始计时
                return new Promise(function (resolve, reject) {
                    let result = false;
                    _this.$refs.formValidate.validateField('mobile', (error) => {
                        if (error) {
                            result = false;
                        } else {
                            result = true;
                        }
                        resolve(result)
                    });
                });

            },

            sendRegisterCode() {
                sendRegisterCode(this.formValidate.mobile).then(({data}) => {
                    if (data.code == 0) {
                        this.formValidate.code_id = data.data.code_id;
                        this.$Message.success(data.message)
                    } else {
                        this.$Message.error(data.message)
                    }
                }).catch(error => {


                })
            },
            handleSubmit(name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        this.registerLoading = true;
                        registerUser(this.formValidate).then(({data}) => {
                            new Promise((resolve, reject) => {
                                this.registerLoading = false;
                                this.setToken(data.access_token)
                                this.$Message.success('注册成功,登录中');
                                resolve()
                            }).then(() => {
                                this.getUserInfo().then(res => {
                                    this.$router.push({
                                        name: this.$config.homeName
                                    })
                                })
                            });

                        }).catch(error => {

                            let errros = error.response.data.errors;
                            for (let i in errros) {
                                this.$Message.error(errros[i][0]);
                            }
                            this.registerLoading = false;
                        })


                    } else {
                        this.registerLoading = false;
                        this.$Message.error('Fail!');
                    }
                })
            },
            handleReset(name) {
                this.$refs[name].resetFields();
            }
        }
    }
</script>

<style scoped>
    .top {
        background: #2d8cf0;
    }

    .top li {
        list-style: none;
        text-align: center;
    }

    .logo-con {
        height: 64px;
        padding: 10px;
    }

    .logo-con img {
        height: 44px;
        width: auto;
        display: block;
        margin: 0 auto;
    }

    .form {
        margin: 60px auto;
        min-height: 500px;
        width: 460px;
    }

    .submit {
        padding-right: 20px;
    }

    p {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin: 30px 0;
    }

    p.existing-account {
        text-align: right;
        font-size: 14px;
        margin: 10px 0;
    }
</style>
