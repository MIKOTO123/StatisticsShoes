<template>
    <div>
        <Card :bordered="false">
            <Form :model="formData" ref="formData">
                <FormItem label="创建时间：">
                    <DatePicker type="date" placeholder="开始日期" v-model="formData.beginDate"></DatePicker>
                    -
                    <DatePicker type="date" placeholder="结束日期" v-model="formData.endDate"></DatePicker>
                    <Input v-model="formData.searchValue" placeholder="请输入统计名称"
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
                            @click="clickShow(row)">查看
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
            @on-ok="onOkAddStatistics"
            title="添加商店"
            v-model="modalAddStatistics"
            :loading="modalAddStatisticsLoading"
            :styles="{top: '20px'}">
            <Form ref="formAddStatistics" :model="formAddStatistics" :rules="formAddStatisticsRules"
                  :label-width="110">
                <FormItem label="统计名称:" prop="statistics_name">
                    <Input v-model="formAddStatistics.statistics_name" type="text" style="width: 300px;"></Input>
                </FormItem>
            </Form>
        </Modal>


    </div>
</template>

<script>
    import {mapMutations} from 'vuex'
    import dayjs from "dayjs";
    import {statisticsCheck, statisticsDel, statisticsIndex, statisticsStore, statisticsUpdate} from "@/api/statistics";

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
                formAddStatistics: {
                    statistics_name: '',
                    mark: '',
                },
                formUpdateStatistics: {
                    id: 0,
                    statistics_name: '',
                    mark: '',
                },
                modalAddStatisticsLoading: false,
                modalAddStatistics: false,
                formAddStatisticsRules: {
                    statistics_name: [
                        {required: true, message: '请输入统计名称', trigger: 'blur'},
                        {
                            asyncValidator(rule, value, callback) {
                                return statisticsCheck(value).then(result => {
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
                columnsTable: [
                    {
                        title: '下单时间',
                        slot: 'created_at'
                    },
                    {
                        title: '统计名称',
                        key: 'statistics_name'
                    },
                    // {
                    //     title: '备注',
                    //     key: 'mark'
                    // },
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
        name: 'statistics_good',
        methods: {

            onOkAddStatistics() {
                this.$refs.formAddStatistics.validate((valid) => {
                    if (valid) {
                        statisticsStore(this.formAddStatistics).then(({data}) => {
                            this.modalAddStatisticsLoading = false;
                            if (data.code == 0) {
                                //设置手机号码
                                this.modalAddStatistics = false;//关闭对话框
                                this.formAddStatistics.statistics_name = '';
                                this.formAddStatistics.mark = '';
                                this.initData();
                                this.$Message.success(data.message);
                            } else {
                                this.$Message.error(data.message);
                            }

                        }).catch(error => {
                            this.modalAddStatisticsLoading = false;
                            let errros = error.response.data.errors;
                            for (let i in errros) {
                                this.$Message.error(errros[i][0]);
                            }
                        })
                    } else {
                        this.modalAddStatisticsLoading = false;
                    }

                });
            },
            clickAdd() {
                this.modalAddStatistics = true;
            },
            clickShow(row) {
                this.$router.push({
                    name: 'statistics_single',
                    query: {
                        id:row.id,
                        name:row.statistics_name
                    }
                })
            },
            pageChange(pageNo) {
                this.formData.page = pageNo;
                this.initData()
            },
            initData() {
                //提交之前对表单的时间操作一下
                this.formData.beginDate = dayjs(this.formData.beginDate).format('YYYY-MM-DD 00:00:00')
                this.formData.endDate = dayjs(this.formData.endDate).format('YYYY-MM-DD 23:59:59')
                statisticsIndex(this.formData).then(({data}) => {
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
        },
        activated() {
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
