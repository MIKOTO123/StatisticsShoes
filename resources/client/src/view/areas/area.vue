<template>
    <div>
        <Card :bordered="false">
            <Form>
                <FormItem label="创建时间：">
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
        </Card>


        <Modal
            @on-ok="onOkAddArea"
            title="添加地区"
            v-model="modalAddArea"
            :loading="modalAddAreaLoading"
            :styles="{top: '20px'}">
            <Form ref="formAddArea" :model="formAddArea" :rules="formAddAreaRules"
                  :label-width="110">
                <FormItem label="地区名称:" prop="area">
                    <Input v-model="formAddArea.area" type="text" style="width: 300px;"></Input>
                </FormItem>
            </Form>
        </Modal>


        <Modal
            @on-ok="onOkUpdateArea"
            title="修改地区"
            v-model="modalUpdateArea"
            :loading="modalUpdateAreaLoading"
            :styles="{top: '20px'}">
            <Form ref="formUpdateArea" :model="formUpdateArea" :rules="formUpdateAreaRules"
                  :label-width="110">
                <FormItem label="地区名称:" prop="area">
                    <Input v-model="formUpdateArea.area" type="text" style="width: 300px;"></Input>
                </FormItem>
            </Form>
        </Modal>


    </div>
</template>

<script>
    import {mapMutations} from 'vuex'
    import dayjs from "dayjs";
    import {shopCheck, shopDel, shopIndex, shopStore, shopUpdate} from "@/api/shop";
    import {SELECT_AREA_CHSNGE, SELECT_CHSNGE} from "@common@/bus/bus-constant";
    import {areaCheck, areaDel, areaIndex, areaStore, areaUpdate} from "@/api/area";

    export default {
        data() {
            return {
                deleteId: 0,
                formAddArea: {
                    area: '',
                },
                formUpdateArea: {
                    id: 0,
                    area: '',
                },
                modalAddAreaLoading: false,
                modalUpdateAreaLoading: false,
                modalDel: false,
                modalAddArea: false,
                modalUpdateArea: false,
                formAddAreaRules: {
                    area: [
                        {required: true, message: '请输入地区名称', trigger: 'blur'},
                        {
                            asyncValidator(rule, value, callback) {
                                return areaCheck(value).then(result => {
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
                formUpdateAreaRules: {
                    area: [
                        {required: true, message: '请输入地区名称', trigger: 'blur'},
                        {
                            asyncValidator: (rule, value, callback) => {
                                return areaCheck(value, this.formUpdateArea.id).then(result => {
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
                dataTable: []
            }
        },
        name: 'shop',
        methods: {


            clickAdd() {
                this.modalAddArea = true;
            },
            clickEdit(row) {
                this.modalUpdateArea = true;
                this.formUpdateArea.id = row.id;
                this.formUpdateArea.area = row.area;
            },
            clickDel(id) {
                //弹出对话框是否删除
                areaDel(id).then(({data}) => {
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
            onOkAddArea() {
                this.$refs.formAddArea.validate((valid) => {
                    if (valid) {
                        areaStore(this.formAddArea).then(({data}) => {
                            this.modalAddAreaLoading = false;
                            if (data.code == 0) {
                                this.modalAddArea = false;//关闭对话框
                                this.formAddArea.area = '';
                                this.initData();
                                this.$Message.success(data.message);
                            } else {
                                this.$Message.error(data.message);
                            }

                        }).catch(error => {
                            this.modalAddAreaLoading = false;
                            let errros = error.response.data.errors;
                            for (let i in errros) {
                                this.$Message.error(errros[i][0]);
                            }
                        })
                    } else {
                        this.modalAddAreaLoading = false;
                    }

                });
            },

            onOkUpdateArea() {
                this.$refs.formUpdateArea.validate((valid) => {
                    if (valid) {
                        areaUpdate(this.formUpdateArea).then(({data}) => {
                            this.modalUpdateAreaLoading = false;
                            if (data.code == 0) {
                                //设置手机号码
                                this.modalUpdateArea = false;//关闭对话框
                                this.formUpdateArea.area = '';
                                this.initData();
                                this.$Message.success(data.message);
                            } else {
                                this.$Message.error(data.message);
                            }

                        }).catch(error => {
                            this.modalUpdateAreaLoading = false;
                            let errros = error.response.data.errors;
                            for (let i in errros) {
                                this.$Message.error(errros[i][0]);
                            }
                        })
                    } else {
                        this.modalUpdateAreaLoading = false;
                    }

                });
            },

            initData() {
                this.$bus.$emit(SELECT_AREA_CHSNGE);
                //提交之前对表单的时间操作一下
                areaIndex().then(({data}) => {
                    if (data.code == 0) {
                        this.dataTable = data.data
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
