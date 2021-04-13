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
                    <Button type="primary" ghost @click="pageChange(1)">查询</Button>
                    <Button type="primary" ghost @click="clickAdd">增加</Button>
                    <div style="display: inline-block;">
                        <Upload
                            :show-upload-list="false"
                            :on-success="UploadSuccess"
                            :on-exceeded-size="handleMaxSize"
                            :max-size="10240"
                            :format="['xls','xlsx']"
                            :on-format-error="handleFormatError"
                            action="/client/shop/shopImport"
                        >
                            <Button>导入</Button>
                        </Upload>
                    </div>
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

                    <div style="display: inline-block;">
                        <Upload
                            :show-upload-list="false"
                            :on-success="UploadSuccess"
                            :on-exceeded-size="handleMaxSize"
                            :max-size="10240"
                            :format="['xls','xlsx']"
                            :on-format-error="handleFormatError"
                            :action="'/client/good/goodImport/'+row.id"
                        >
                            <Button type="primary" size="small">导入</Button>
                        </Upload>
                    </div>


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
            @on-ok="onOkAddShop"
            title="添加商店"
            v-model="modalAddShop"
            :loading="modalAddShopLoading"
            :styles="{top: '20px'}">
            <Form ref="formAddShop" :model="formAddShop" :rules="formAddShopRules"
                  :label-width="110">
                <FormItem label="商店名称:" prop="shop_name">
                    <Input v-model="formAddShop.shop_name" type="text" style="width: 300px;"></Input>
                </FormItem>

                <FormItem label="地区名称:" prop="area_id">
                    <Select
                        placeholder="请输入地区名称"
                        style="width: 200px;"
                        v-model="formAddShop.area_id"
                        filterable
                    >
                        <Option v-for="(option, index) in areaOptions" :value="option.id" :key="index">
                            {{option.area}}
                        </Option>
                    </Select>
                </FormItem>
                <FormItem label="说明：">
                    <Input
                        maxlength="255"
                        v-model="formAddShop.mark"
                        prop="mark"
                        type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请填写说明"
                        style="width: 300px;"></Input>
                </FormItem>
            </Form>
        </Modal>


        <Modal
            @on-ok="onOkUpdateShop"
            title="修改商店"
            v-model="modalUpdateShop"
            :loading="modalUpdateShopLoading"
            :styles="{top: '20px'}">
            <Form ref="formUpdateShop" :model="formUpdateShop" :rules="formUpdateShopRules"
                  :label-width="110">
                <FormItem label="商店名称:" prop="shop_name">
                    <Input v-model="formUpdateShop.shop_name" type="text" style="width: 300px;"></Input>
                </FormItem>

                <FormItem label="地区名称:" prop="area_id">
                    <Select
                        placeholder="请输入地区名称"
                        style="width: 200px;"
                        v-model="formUpdateShop.area_id"
                        filterable
                    >
                        <Option v-for="(option, index) in areaOptions" :value="option.id" :key="index">{{option.area}}</Option>
                    </Select>
                </FormItem>

                <FormItem label="说明：">
                    <Input
                        maxlength="255"
                        v-model="formUpdateShop.mark"
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
            @on-ok="deleteShop"
        >
            <Row style="padding: 20px 0;">
                <Col span="6" style="text-align: right; padding-right: 10px;">
                    <Icon class="wenhao-icon" custom="iconfont icon-wenhao" size="56"/>
                </Col>
                <Col span="18">
                    <h3 style=" padding-bottom: 10px;">数据删除后无法恢复，确定删除商店,包括商店底下的产品么？</h3>
                </Col>
            </Row>
        </Modal>


    </div>
</template>

<script>
    import {mapMutations} from 'vuex'
    import dayjs from "dayjs";
    import {shopCheck, shopDel, shopIndex, shopStore, shopUpdate} from "@/api/shop";
    import {SELECT_CHSNGE} from "@common@/bus/bus-constant";
    import {areaIndex} from "@/api/area";

    export default {
        data() {
            return {
                deleteId: 0,
                formData: {
                    page: 1,
                    searchValue: null,
                    beginDate: dayjs().subtract(12, "month").format('YYYY-MM-DD'),
                    endDate: dayjs().format('YYYY-MM-DD'),
                },
                formAddShop: {
                    area_id: 0,
                    shop_name: '',
                    mark: '',
                },
                formUpdateShop: {
                    id: 0,
                    area_id: 0,
                    shop_name: '',
                    mark: '',
                },
                modalAddShopLoading: false,
                modalUpdateShopLoading: false,
                modalDel: false,
                modalAddShop: false,
                modalUpdateShop: false,
                formAddShopRules: {
                    shop_name: [
                        {required: true, message: '请输入店铺名称', trigger: 'blur'},
                        {
                            asyncValidator(rule, value, callback) {
                                return shopCheck(value).then(result => {
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
                formUpdateShopRules: {
                    shop_name: [
                        {required: true, message: '请输入店铺名称', trigger: 'blur'},
                        {
                            asyncValidator: (rule, value, callback) => {
                                return shopCheck(value, this.formUpdateShop.id).then(result => {
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
                        title: '创建时间',
                        slot: 'created_at'
                    },
                    {
                        title: '店铺名称',
                        key: 'shop_name'
                    },
                    {
                        title: '地区',
                        key: 'area'
                    },
                    {
                        title: '备注',
                        key: 'mark'
                    },
                    {
                        title: ' ',
                        slot: 'action',
                        align: 'center'
                    }
                ],

                page_info: {},
                dataTable: [],
                areaOptions: [],
            }
        },
        name: 'shop',
        methods: {


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

            clickAdd() {
                this.modalAddShop = true;
            },
            clickEdit(row) {
                this.modalUpdateShop = true;
                this.formUpdateShop.id = row.id;
                this.formUpdateShop.shop_name = row.shop_name;
                this.formUpdateShop.area_id = parseInt(row.area_id);
                this.formUpdateShop.mark = row.mark;
            },
            clickDel(id) {
                this.modalDel = true;
                this.deleteId = id;
                //弹出对话框是否删除
            },
            deleteShop() {
                shopDel(this.deleteId).then(({data}) => {
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
            onOkAddShop() {
                this.$refs.formAddShop.validate((valid) => {
                    if (valid) {
                        shopStore(this.formAddShop).then(({data}) => {
                            this.modalAddShopLoading = false;
                            if (data.code == 0) {
                                //设置手机号码
                                this.modalAddShop = false;//关闭对话框
                                this.formAddShop.shop_name = '';
                                this.formAddShop.area_id = 0;
                                this.formAddShop.mark = '';
                                this.initData();
                                this.$Message.success(data.message);
                            } else {
                                this.$Message.error(data.message);
                            }

                        }).catch(error => {
                            this.modalAddShopLoading = false;
                            let errros = error.response.data.errors;
                            for (let i in errros) {
                                this.$Message.error(errros[i][0]);
                            }
                        })
                    } else {
                        this.modalAddShopLoading = false;
                    }

                });
            },

            onOkUpdateShop() {
                this.$refs.formUpdateShop.validate((valid) => {
                    if (valid) {
                        shopUpdate(this.formUpdateShop).then(({data}) => {
                            this.modalUpdateShopLoading = false;
                            if (data.code == 0) {
                                //设置手机号码
                                this.modalUpdateShop = false;//关闭对话框
                                this.formUpdateShop.shop_name = '';
                                this.formUpdateShop.mark = '';
                                this.formUpdateShop.area_id = 0;
                                this.initData();
                                this.$Message.success(data.message);
                            } else {
                                this.$Message.error(data.message);
                            }

                        }).catch(error => {
                            this.modalUpdateShopLoading = false;
                            let errros = error.response.data.errors;
                            for (let i in errros) {
                                this.$Message.error(errros[i][0]);
                            }
                        })
                    } else {
                        this.modalUpdateShopLoading = false;
                    }

                });
            },

            pageChange(pageNo) {
                this.formData.page = pageNo;
                this.initData()
            },

            getAreaOption() {
                areaIndex().then(({data}) => {
                    if (data.code == 0) {
                        this.areaOptions = data.data;
                    } else {
                        this.$Message.error(data.message)
                    }
                }).catch(error => {
                    this.$Message.error('请求有误');

                })
            },
            initData() {


                this.getAreaOption();

                this.$bus.$emit(SELECT_CHSNGE);

                //提交之前对表单的时间操作一下
                this.formData.beginDate = dayjs(this.formData.beginDate).format('YYYY-MM-DD 00:00:00')
                this.formData.endDate = dayjs(this.formData.endDate).format('YYYY-MM-DD 23:59:59')
                shopIndex(this.formData).then(({data}) => {
                    if (data.code == 0) {
                        this.dataTable = data.data.data
                        this.page_info = data.data
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
