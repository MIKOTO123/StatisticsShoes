<template>
<div>
    <ul class="top">
        <li class="logo-con"><img src="~@/assets/images/logo-top-center.png"/></li>
    </ul>
    <Form ref="formCustom" :model="formCustom" :rules="ruleCustom" :label-width="120" class="form">
        <p>密码找回</p>
        <FormItem label="手机：" class="formitem-li">
            <Input placeholder="输入绑定的手机号码" style="width: 320px;"></Input>
        </FormItem>
        <FormItem label="验证码：" class="formitem-li">
            <Input placeholder="输入验证码" style="width: 215px;"></Input>
            <Button type="primary">获取验证码</Button>
        </FormItem>
        <FormItem label="输入新密码：" prop="passwd" class="formitem-li">
            <Input type="password" v-model="formCustom.passwd" style="width: 320px;"></Input>
        </FormItem>
        <FormItem label="确认密码：" prop="passwdCheck" class="formitem-li">
            <Input type="password" v-model="formCustom.passwdCheck" style="width: 320px;"></Input>
        </FormItem>
        <FormItem class="submit">
            <Button size="large" type="primary" long>提交修改</Button>
        </FormItem>
    </Form>
</div>




</template>

<script>
    import ABackTop from "../../components/main/components/a-back-top/index";
    export default {
        components: {ABackTop},
        data () {
            const validatePass = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请输入新密码'));
                } else {
                    //不能包含空格
                    if (value.indexOf(" ") != -1) {
                        callback(new Error('不能包含空格'))
                    }
                    callback();

                    if (this.formCustom.passwdCheck !== '') {
                        // 对第二个密码框单独验证
                        this.$refs.formCustom.validateField('passwdCheck');
                    }
                    callback();
                }
            };
            const validatePassCheck = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请再次确认密码'));
                } else if (value !== this.formCustom.passwd) {
                    callback(new Error('两次输入的密码不一致!'));
                } else {
                    callback();
                }
            };

            return {
                formCustom: {
                    passwd: '',
                    passwdCheck: ''
                },
                ruleCustom: {
                    passwd: [
                        { validator: validatePass, trigger: 'blur' }
                    ],
                    passwdCheck: [
                        { validator: validatePassCheck, trigger: 'blur' }
                    ]
                }
            }
        },
        methods: {
            handleSubmit (name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        this.$Message.success('Success!');
                    } else {
                        this.$Message.error('Fail!');
                    }
                })
            },
            handleReset (name) {
                this.$refs[name].resetFields();
            }
        }
    }
</script>

<style scoped>
    .top{
        background: #2d8cf0;
    }
    .top li{
        list-style: none;
        text-align: center;
    }
    .logo-con{
        height: 64px;
        padding: 10px;
    }
    .logo-con img{
        height: 44px;
        width: auto;
        display: block;
        margin: 0 auto;
    }
    .form{
        margin: 60px auto;
        min-height: 500px;
        width: 460px;
    }
    .submit{
        padding-right: 20px;
    }
    p{
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin: 30px 0;
    }
</style>
