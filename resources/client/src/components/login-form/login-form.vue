<template>
    <Form ref="loginForm" :model="form" :rules="rules" @keydown.enter.native="handleSubmit" style="padding-top: 10px;">
        <FormItem prop="userName">
            <Input v-model="form.userName" :placeholder="usernamePh" size="large">
        <span slot="prepend">
          <Icon :size="16" type="ios-person" color="gray"></Icon>
        </span>
            </Input>
        </FormItem>
        <FormItem prop="password">
            <Input type="password" v-model="form.password" :placeholder="pwdPh" size="large">
        <span slot="prepend">
          <Icon :size="14" type="md-lock" color="gray" style="margin: 0 1px;"></Icon>
        </span>
            </Input>
        </FormItem>
        <FormItem>
            <Button size="large" style="margin-top: 10px;" @click="handleSubmit" type="primary" long>登录</Button>
        </FormItem>

    </Form>
</template>
<script>
    export default {
        name: 'LoginForm',
        props: {
            userNameRules: {
                type: Array,
                default: () => {
                    return [
                        {required: true, message: '账号不能为空', trigger: 'blur'}
                    ]
                }
            },
            usernamePh: {
                type: String,
                default: () => {
                    return '请输入用户名'
                }
            },
            pwdPh: {
                type: String,
                default: () => {
                    return '请输入密码'
                }
            },
            passwordRules: {
                type: Array,
                default: () => {
                    return [
                        {required: true, message: '密码不能为空', trigger: 'blur'}
                    ]
                }
            }
        },
        data() {
            return {
                form: {
                    userName: '', //这里的username应该是从cookie里面获取的,后main有空记得处理一下
                    password: ''
                }
            }
        },
        computed: {
            rules() {
                return {
                    userName: this.userNameRules,
                    password: this.passwordRules
                }
            }
        },
        methods: {
            handleSubmit() {
                this.$refs.loginForm.validate((valid) => {
                    if (valid) {
                        this.$emit('on-success-valid', {
                            userName: this.form.userName,
                            password: this.form.password
                        })
                    }
                })
            }
        }
    }
</script>
