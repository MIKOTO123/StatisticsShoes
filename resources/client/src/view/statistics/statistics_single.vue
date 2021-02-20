<template>
    <div>
        <Card :bordered="false">
            <h1 v-if="$route.query.name">{{$route.query.name}}</h1>
            <Form :model="formData" ref="formData" :rules="formDataRules" inline>
                <FormItem label="商品：" prop="g_name">

                    <Select
                        @on-select="selectGoodEvent"
                        placeholder="请输入商品名称"
                        style="width: 300px;"
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
                           style="width: 200px; margin-right: 10px;"></Input>
                </FormItem>
                <FormItem label="数量：" prop="count">

                    <Input v-model="formData.count" placeholder="请输入数量"
                           type="number"
                           style="width: 200px; margin-right: 10px;"></Input>

                </FormItem>

                <Button :loading="addLoading" type="primary" @click="clickAdd">增加</Button>
                <Button :loading="delLoading" type="primary" @click="clickDel">减少</Button>

            </Form>
        </Card>
        <!--        <Card  v-for="(item, index) in alldata" :value="index" :key="index">-->
        <!--            <strong slot="title" style="background: black;">{{index}}</strong>-->
        <!--            <p  v-for="(item2, index2) in item" :value="index2" :key="index2">-->
        <!--                <sapn>{{item2.g_name}}</sapn>-->
        <!--                <sapn>{{item2.color}}</sapn>-->
        <!--                <sapn>{{item2.count}}</sapn>-->
        <!--            </p>-->
        <!--        </Card>-->
        <Collapse>
            <Panel v-for="(item, index) in alldata" :key="index">
                {{index}}
                <div slot="content">
                    <div v-for="(item2, index2) in item" :value="index2" :key="index2">
                        <strong>{{item2.g_name}}</strong>
                        <strong>{{item2.color}}</strong>
                        <strong>*{{item2.count}}</strong>
                    </div>
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

    export default {
        name: "statistics_single",
        data() {
            return {
                alldata: [],
                value1: 1,
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
                },
            }
        },
        methods: {


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


                statisticsSingleIndex({id: this.$route.query.id}).then(({data}) => {
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
            },
            initSelect() {
                statisticsSingleCreate().then(({data}) => {
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
            this.formData.s_id = this.$route.query.id;
            this.initData()
        }

    }
</script>

<style scoped>

</style>
