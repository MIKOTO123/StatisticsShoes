import axios from "@/libs/api.request";


export const statisticsIndex = (formData) => {
    return axios.request({
        url: 'statistics',
        params: formData,
        method: 'get'
    })
}


export const statisticsStore = (formData) => {
    return axios.request({
        url: 'statistics',
        data: formData,
        method: 'post'
    })
}


export const statisticsCheck = (statistics_name, expectId = null) => {
    return axios.request({
        url: 'statistics/checkExist',
        params: {
            statistics_name,
            expectId
        },
        method: 'get'
    })
}







