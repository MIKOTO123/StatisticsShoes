<template>
    <div>
        <Card :bordered="false">
            <h1 v-if="$route.query.name">{{$route.query.name}}</h1>
            <!--            <div>-->
            <!--                <Input @on-enter="onEnter" v-model="inputValue" placeholder="请输入码数"-->
            <!--                       style="width: 100px; margin-right: 10px;"></Input>-->
            <!--            </div>-->
            <Form :model="formData" ref="formData" :rules="formDataRules" inline>
                <FormItem label="商品：" prop="g_name">

                    <Select
                        @on-select="selectGoodEvent"
                        placeholder="请输入商品名称"
                        style="width: 200px;"
                        v-model="formData.g_name"
                        filterable
                    >
                        <Option v-for="(option, index) in goods" :value="option.g_name" :key="index">
                            {{option.g_name}}
                        </Option>
                    </Select>


                </FormItem>
                <FormItem label="商店：" prop="shop_name">

                    <Select
                        placeholder="请输入商店名称"
                        style="width: 300px;"
                        v-model="formData.shop_name"
                        filterable
                    >
                        <Option v-for="(option, index) in shopsOptions" :value="option.shop_name" :key="index">
                            {{option.shop_name}}
                        </Option>
                    </Select>


                </FormItem>
                <FormItem label="颜色：" prop="color">
                    <Input v-model="formData.color" placeholder="请输入颜色,不输入,会自动取'默认'"
                           style="width: 100px; margin-right: 10px;"></Input>
                </FormItem>
                <FormItem label="码数：" prop="size">
                    <Input v-model="formData.size" placeholder="请输入码数"
                           style="width: 100px; margin-right: 10px;"></Input>
                </FormItem>
                <FormItem label="数量：" prop="count">

                    <Input v-model="formData.count" placeholder="请输入数量"
                           type="number"
                           style="width: 100px; margin-right: 10px;"></Input>

                </FormItem>


            </Form>

            <Button :loading="addLoading" type="primary" @click="clickAdd">增加</Button>
            <Button :loading="delLoading" type="primary" @click="clickDel">减少</Button>
            <div style="margin-top: 10px">
                <Upload
                    :show-upload-list="false"
                    :on-success="UploadSuccess"
                    :on-exceeded-size="handleMaxSize"
                    :max-size="10240"
                    :format="['xls','xlsx']"
                    :on-format-error="handleFormatError"
                    :action="action"
                >
                    <Button>导入</Button>
                </Upload>
            </div>
        </Card>
        <!--        <Card  v-for="(item, index) in alldata" :value="index" :key="index">-->
        <!--            <strong slot="title" style="background: black;">{{index}}</strong>-->
        <!--            <p  v-for="(item2, index2) in item" :value="index2" :key="index2">-->
        <!--                <sapn>{{item2.g_name}}</sapn>-->
        <!--                <sapn>{{item2.color}}</sapn>-->
        <!--                <sapn>{{item2.count}}</sapn>-->
        <!--            </p>-->
        <!--        </Card>-->
        <Collapse v-if="errordata.length">
            <Panel name="1">
                导入错误
                <div slot="content">
                    <div v-for="(item, index) in errordata" :value="index" :key="index">
                        <Button type="primary" size="small" @click="clickErrorDel(item.id)">删除</Button>
                        <strong>{{item.error_row}}</strong>
                        错误原因:
                        <strong>{{item.error_reason}}</strong>
                    </div>
                </div>
            </Panel>
        </Collapse>

<!--        <Collapse>-->
<!--            <Panel v-for="(item, index) in alldata" :key="index">-->
<!--                {{index}}-->

<!--                <div slot="content">-->
<!--                    <div v-for="(item2, index2) in item" :value="index2" :key="index2">-->
<!--                        <strong>{{item2.g_name}}</strong>-->
<!--                        <strong>{{item2.color}}</strong>-->
<!--                        <strong>{{item2.size}}*{{item2.count}}</strong>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </Panel>-->

<!--        </Collapse>-->


        <Collapse simple>
            <Panel v-for="(item, index) in alldata" :key="index">
                {{index}}
                <div slot="content">
                    <Collapse v-for="(item2, index2) in item" :key="index2">
                        <Panel name="1-1">
                            {{index2}}
                            <div slot="content">
                                <div v-for="(item3, index3) in item2" :key="index3">
                                    <strong>{{item3.g_name}}</strong>
                                    <strong>{{item3.color}}</strong>
                                    <strong>{{item3.size}}*{{item3.count}}</strong>
                                </div>
                            </div>
                        </Panel>
                    </Collapse>
                </div>
            </Panel>

        </Collapse>


    </div>

</template>

<script>
    import {shopStore} from "@/api/shop";
    import {
        statisticsSingleCreate,
        statisticsSingleDecrement,
        statisticsSingleIndex,
        statisticsSingleStore
    } from "@/api/statistics_single";
    import {SELECT_CHSNGE} from "@common@/bus/bus-constant";
    import {importErrorDel, importErrorIndex} from "@/api/import_error";

    export default {
        name: "statistics_single",
        data() {
            return {
                inputValue: '',
                alldata: [],
                errordata: [],
                value1: 1,
                action: '/client/statisticsSingle/uploadImport/' + this.$route.query.id,
                addLoading: false,
                delLoading: false,
                goods: [],
                shops: [],
                shopsOptions: [],
                formData: {
                    s_id: this.$route.query.id,
                    shop_name: '',
                    g_name: '',
                    color: '默认',
                    size: '',
                    count: ''
                },
                formDataRules: {
                    shop_name: [
                        {required: true, message: '请输入商店名称', trigger: 'blur'},
                    ],
                    g_name: [
                        {required: true, message: '请输入商品名称', trigger: 'blur'},
                    ],
                    count: [
                        {required: true, message: '请输入数量', trigger: 'blur'},
                    ],
                    size: [
                        {required: true, message: '请输入码数', trigger: 'blur'},
                    ],
                },
            }
        },
        methods: {

            // onEnter(smgui, hha, nani) {
            //     // console.log(this.inputValue)
            //     this.inputValue
            //     let tmparr = this.inputValue.trim().split(" ");
            //     tmparr = tmparr.filter(function (s) {
            //         return s && s.trim(); // 注：IE9(不包含IE9)以下的版本没有trim()方法
            //     });
            //     this.formData.g_name = tmparr[0];
            //     this.formData.color = tmparr[1];
            //     console.log(tmparr)
            // },

            UploadSuccess(res, file) {
                this.$Message.success(res);
                this.initData()
            },
            handleMaxSize(file) {
                this.$Notice.warning({
                    title: '超出文件限制大小',
                    desc: '文件:' + file.name + '超过10M.'
                });
            },
            handleFormatError(file) {
                this.$Notice.warning({
                    title: '文件格式不正确',
                    desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
                });
            },
            selectGoodEvent({value}) {
                //
                let tmparr = [];
                this.goods.forEach(function (item) {
                    if (item.g_name == value) {
                        tmparr.push(this.shops.find(function (item2) {
                            return item.shop_id == item2.id
                        }))
                    }
                }.bind(this))
                tmparr.length == 1 ? this.formData.shop_name = tmparr[0].shop_name : this.formData.shop_name = "";
                this.shopsOptions = tmparr;
            },
            clickAdd() {
                //一遍检测
                this.$refs.formData.validate((valid) => {
                    this.addLoading = true;
                    if (valid) {
                        statisticsSingleStore(this.formData).then(({data}) => {
                            this.addLoading = false;
                            if (data.code == 0) {
                                //设置手机号码
                                this.initData();
                                this.$Message.success(data.message);
                            } else {
                                this.$Message.error(data.message);
                            }

                        }).catch(error => {
                            this.addLoading = false;
                            let errros = error.response.data.errors;
                            for (let i in errros) {
                                this.$Message.error(errros[i][0]);
                            }
                        })
                    } else {
                        this.addLoading = false;
                    }

                });
            },
            clickErrorDel(id) {
                importErrorDel(id).then(({data}) => {
                    if (data.code == 0) {
                        this.initError()
                        this.$Message.success(data.message)
                    } else {
                        this.$Message.error(data.message)
                    }
                }).catch(error => {
                    this.$Message.error('请求有误');

                })
            },
            clickDel() {
                //一遍检测
                this.$refs.formData.validate((valid) => {
                    this.delLoading = true;
                    if (valid) {
                        statisticsSingleDecrement(this.formData).then(({data}) => {
                            this.delLoading = false;
                            if (data.code == 0) {
                                //设置手机号码
                                this.initData();
                                this.$Message.success(data.message);
                            } else {
                                this.$Message.error(data.message);
                            }

                        }).catch(error => {
                            this.delLoading = false;
                            let errros = error.response.data.errors;
                            for (let i in errros) {
                                this.$Message.error(errros[i][0]);
                            }
                        })
                    } else {
                        this.delLoading = false;
                    }

                });
            },
            initData() {


                statisticsSingleIndex({id: this.formData.s_id}).then(({data}) => {
                    if (data.code == 0) {
                        this.alldata = data.data;
                        this.$Message.success(data.message)
                    } else {
                        this.$Message.error(data.message)
                    }
                }).catch(error => {
                    this.$Message.error('请求有误');

                })
                this.initSelect();
                this.initError();
            },
            initError() {
                importErrorIndex({id: this.formData.s_id}).then(({data}) => {
                    if (data.code == 0) {
                        this.errordata = data.data;
                        this.$Message.success(data.message)
                    } else {
                        this.$Message.error(data.message)
                    }
                }).catch(error => {
                    this.$Message.error('请求有误');

                })

            },
            initSelect() {
                statisticsSingleCreate({id: this.formData.s_id}).then(({data}) => {
                    if (data.code == 0) {
                        this.shops = data.data.shops;
                        this.goods = data.data.goods;
                        this.shopsOptions = data.data.shops;
                        // this.$Message.success(data.message)
                    } else {
                        this.$Message.error(data.message)
                    }
                }).catch(error => {
                    this.$Message.error('请求有误');
                })
            },
        },
        mounted() {
            this.initData();
            this.$bus.$on(SELECT_CHSNGE, function () {
                this.initSelect();
            }.bind(this));
        },
        activated() {
            this.action = '/client/statisticsSingle/uploadImport/' + this.$route.query.id
            this.formData.s_id = this.$route.query.id;
            this.initData()
        }

    }
</script>

<style scoped>

</style>
