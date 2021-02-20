import axios from "@/libs/api.request";


export const statisticsSingleIndex = (formData) => {
    return axios.request({
        url: 'statisticsSingle',
        params: formData,
        method: 'get'
    })
}


export const statisticsSingleCreate = () => {
    return axios.request({
        url: 'statisticsSingle/create',
        method: 'get'
    })
}


export const statisticsSingleStore = (formData) => {
    return axios.request({
        url: 'statisticsSingle',
        data: formData,
        method: 'post'
    })
}



export const statisticsSingleDecrement = (formData) => {
    return axios.request({
        url: 'statisticsSingle/decrement',
        data: formData,
        method: 'post'
    })
}
