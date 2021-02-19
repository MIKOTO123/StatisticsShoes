<template>
    <div>
        <Card :bordered="false">
            <Form :model="formData" ref="formData">
                <FormItem label="创建时间：">
                    <DatePicker type="date" placeholder="开始日期" v-model="formData.beginDate"></DatePicker>
                    -
                    <DatePicker type="date" placeholder="结束日期" v-model="formData.endDate"></DatePicker>
                    <Input v-model="formData.searchValue" placeholder="请输入商店名称"
                           style="width: 200px; margin-right: 10px;"></Input>
                    <Input v-model="formData.g_name" placeholder="请输入商品名称"
                           style="width: 200px; margin-right: 10px;"></Input>
                    <Button type="primary" ghost @click="pageChange(1)">查询</Button>
                    <Button type="primary" ghost @click="clickAdd">增加</Button>
                </FormItem>
            </Form>
            <Table border :columns="columnsTable" :data="dataTable">

                <template v-slot:created_at="{ row, index }">
                    {{ row.created_at|filterDateFormat }}
                </template>

                <template v-slot:action="{ row, index }">
                    <Button type="primary" size="small" style="margin-right: 5px"
                            @click="clickEdit(row)">修改
                    </Button>
                    <!--                                百度的目前也是不给修改的-->
                    <!--                                <Button type="primary" size="small" style="margin-right: 5px"-->
                    <!--                                        @click="clickEditTemplate(row.id)">编辑-->
                    <!--                                </Button>-->
                    <Button type="primary" size="small" style="margin-right: 5px"
                            @click="clickDel(row.id)">删除
                    </Button>


                </template>


            </Table>
            <Row>
                <Col span="24">
                    <Page
                        v-if="page_info"
                        :total="page_info.total"
                        :current="page_info.current_page"
                        :page-size="page_info.per_page"
                        @on-change="pageChange"
                        show-elevator class="page"/>
                </Col>
            </Row>
        </Card>


        <Modal
            @on-ok="onOkAddGood"
            title="添加商品"
            v-model="modalAddGood"
            :loading="modalAddGoodLoading"
            :styles="{top: '20px'}">
            <Form ref="formAddGood" :model="formAddGood" :rules="formAddGoodRules"
                  :label-width="110">
                <FormItem label="商品名称:" prop="g_name">
                    <Input v-model="formAddGood.g_name" type="text" style="width: 300px;"></Input>
                </FormItem>
                <FormItem label="说明：">
                    <Input
                        maxlength="255"
                        v-model="formAddGood.mark"
                        prop="mark"
                        type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请填写说明"
                        style="width: 300px;"></Input>
                </FormItem>
                <FormItem label="商店：" prop="shop_id">
                    <Select
                        style="width: 300px;"
                        v-model="formAddGood.shop_id"
                        filterable
                    >
                        <Option v-for="(option, index) in OptionsShop" :value="option.id" :key="index">
                            {{option.shop_name}}
                        </Option>
                    </Select>
                </FormItem>
            </Form>
        </Modal>


        <Modal
            @on-ok="onOkUpdateGood"
            title="修改商品"
            v-model="modalUpdateGood"
            :loading="modalUpdateGoodLoading"
            :styles="{top: '20px'}">
            <Form ref="formUpdateGood" :model="formUpdateGood" :rules="formUpdateGoodRules"
                  :label-width="110">
                <FormItem label="商品名称:" prop="g_name">
                    <Input v-model="formUpdateGood.g_name" type="text" style="width: 300px;"></Input>
                </FormItem>
                <FormItem label="说明：">
                    <Input
                        maxlength="255"
                        v-model="formUpdateGood.mark"
                        prop="mark"
                        type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请填写说明"
                        style="width: 300px;"></Input>
                </FormItem>

            </Form>

        </Modal>


        <Modal
            title="系统提示"
            v-model="modalDel"
            class-name="vertical-center-modal"
            @on-ok="deleteGood"
        >
            <Row style="padding: 20px 0;">
                <Col span="6" style="text-align: right; padding-right: 10px;">
                    <Icon class="wenhao-icon" custom="iconfont icon-wenhao" size="56"/>
                </Col>
                <Col span="18">
                    <h3 style=" padding-bottom: 10px;">数据删除后无法恢复，确定删除商品？</h3>
                </Col>
            </Row>
        </Modal>


    </div>
</template>

<script>
    import {mapMutations} from 'vuex'
    import dayjs from "dayjs";
    import {goodCheck, goodDel, goodIndex, goodStore, goodUpdate} from "@/api/good";
    import {getOptionsShop} from "@/api/shop";

    export default {
        data() {
            return {
                deleteId: 0,
                OptionsShop: [],
                formData: {
                    page: 1,
                    searchValue: null,
                    g_name: null,
                    beginDate: dayjs().subtract(12, "month").format('YYYY-MM-DD'),
                    endDate: dayjs().format('YYYY-MM-DD'),
                },
                formAddGood: {
                    shop_id: 0,
                    g_name: '',
                    mark: '',
                },
                formUpdateGood: {
                    id: 0,
                    shop_id: 0,
                    g_name: '',
                    mark: '',
                },
                modalAddGoodLoading: false,
                modalUpdateGoodLoading: false,
                modalDel: false,
                modalAddGood: false,
                modalUpdateGood: false,
                formAddGoodRules: {
                    shop_id: [
                        {type: 'number',required: true, message: '请填写商店', trigger: 'change'},
                    ],
                    g_name: [
                        {required: true, message: '请输入商品名称', trigger: 'blur'},
                        {
                            asyncValidator:(rule, value, callback) =>{
                                return goodCheck(value, this.formUpdateGood.shop_id).then(result => {
                                    if (result.data.code > 0) {
                                        callback(new Error(result.data.message));
                                    } else {
                                        callback();
                                    }
                                }).catch(err => {
                                    callback(err);
                                    console.log(err);
                                });
                            }
                        }
                    ],
                },
                formUpdateGoodRules: {
                    g_name: [
                        {required: true, message: '请输入商品名称', trigger: 'blur'},
                        {
                            asyncValidator: (rule, value, callback) => {
                                return goodCheck(value, this.formUpdateGood.shop_id, this.formUpdateGood.id).then(result => {
                                    // console.log(result);//{data:{code,data,message},status:200}
                                    if (result.data.code > 0) {
                                        callback(new Error(result.data.message));
                                    } else {
                                        callback();
                                    }
                                    // if(data.reponse)
                                }).catch(err => {
                                    callback(err);
                                    console.log(err);
                                });
                            }
                        }
                    ],
                },
                columnsTable: [
                    {
                        title: '下单时间',
                        slot: 'created_at'
                    },
                    {
                        title: '商品名称',
                        key: 'g_name'
                    },
                    {
                        title: '备注',
                        key: 'mark'
                    },
                    {
                        title: '店铺名称',
                        key: 'shop_name'
                    },
                    {
                        title: ' ',
                        slot: 'action',
                        align: 'center'
                    }
                ],

                page_info: {},
                dataTable: []
            }
        },
        name: 'good',
        methods: {

            clickAdd() {
                this.modalAddGood = true;
            },
            clickEdit(row) {
                this.modalUpdateGood = true;
                this.formUpdateGood.id = row.id;
                this.formUpdateGood.shop_id = row.shop_id;
                this.formUpdateGood.g_name = row.g_name;
                this.formUpdateGood.mark = row.mark;
            },
            clickDel(id) {
                this.modalDel = true;
                this.deleteId = id;
                //弹出对话框是否删除
            },
            deleteGood() {
                goodDel(this.deleteId).then(({data}) => {
                    if (data.code == 0) {
                        this.initData();
                        this.$Message.success(data.message)
                    } else {
                        this.$Message.error(data.message)
                    }
                }).catch(error => {
                    this.$Message.error('请求有误');
                })
            },
            onOkAddGood() {
                this.$refs.formAddGood.validate((valid) => {
                    if (valid) {
                        goodStore(this.formAddGood).then(({data}) => {
                            this.modalAddGoodLoading = false;
                            if (data.code == 0) {
                                //设置手机号码
                                this.modalAddGood = false;//关闭对话框
                                this.formAddGood.g_name = '';
                                this.formAddGood.mark = '';
                                // this.formAddGood.shop_id = '';//添加之后商店不清空,方便继续添加商品
                                this.initData();
                                this.$Message.success(data.message);
                            } else {
                                this.$Message.error(data.message);
                            }

                        }).catch(error => {
                            this.modalAddGoodLoading = false;
                            let errros = error.response.data.errors;
                            for (let i in errros) {
                                this.$Message.error(errros[i][0]);
                            }
                        })
                    } else {
                        this.modalAddGoodLoading = false;
                    }

                });
            },

            onOkUpdateGood() {
                this.$refs.formUpdateGood.validate((valid) => {
                    if (valid) {
                        goodUpdate(this.formUpdateGood).then(({data}) => {
                            this.modalUpdateGoodLoading = false;
                            if (data.code == 0) {
                                //设置手机号码
                                this.modalUpdateGood = false;//关闭对话框
                                this.formUpdateGood.g_name = '';
                                this.formUpdateGood.mark = '';
                                this.initData();
                                this.$Message.success(data.message);
                            } else {
                                this.$Message.error(data.message);
                            }

                        }).catch(error => {
                            this.modalUpdateGoodLoading = false;
                            let errros = error.response.data.errors;
                            for (let i in errros) {
                                this.$Message.error(errros[i][0]);
                            }
                        })
                    } else {
                        this.modalUpdateGoodLoading = false;
                    }

                });
            },

            pageChange(pageNo) {
                this.formData.page = pageNo;
                this.initData()
            },
            initData() {
                //提交之前对表单的时间操作一下
                this.formData.beginDate = dayjs(this.formData.beginDate).format('YYYY-MM-DD 00:00:00')
                this.formData.endDate = dayjs(this.formData.endDate).format('YYYY-MM-DD 23:59:59')
                goodIndex(this.formData).then(({data}) => {
                    if (data.code == 0) {
                        this.dataTable = data.data.data
                        this.page_info = data.data
                    } else {
                        this.$Message.error(data.message)
                    }
                }).catch(error => {
                    this.$Message.error('请求有误');

                })

                getOptionsShop().then(({data}) => {
                    if (data.code == 0) {
                        this.OptionsShop = data.data;
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
        }, activated() {
            this.initData();
        }
    }
</script>

<style scoped>
    @import "~icon_cus/iconfont.css";

    .page {
        text-align: right;
        margin: 20px 0;
        width: 100%;
    }

</style>
