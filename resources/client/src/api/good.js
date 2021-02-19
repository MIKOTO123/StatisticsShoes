import axios from "@/libs/api.request";


export const goodIndex = (formData) => {
    return axios.request({
        url: 'good',
        params: formData,
        method: 'get'
    })
}


export const goodStore = (formData) => {
    return axios.request({
        url: 'good',
        data: formData,
        method: 'post'
    })
}


export const goodDel = (id) => {
    return axios.request({
        url: 'good/' + id,
        method: 'delete'
    })

}




export const goodUpdate = (formData) => {
    return axios.request({
        url: 'good/' + formData.id,
        data: formData,
        method: 'put'
    })
}


export const goodCheck = (g_name,shop_id, expectId = null) => {
    return axios.request({
        url: 'good/checkExist',
        params: {
            g_name,
            shop_id,
            expectId
        },
        method: 'get'
    })
}
