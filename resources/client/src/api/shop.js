import axios from "@/libs/api.request";


export const shopIndex = (formData) => {
    return axios.request({
        url: 'shop',
        params: formData,
        method: 'get'
    })
}


export const shopStore = (formData) => {
    return axios.request({
        url: 'shop',
        data: formData,
        method: 'post'
    })
}


export const shopDel = (id) => {
    return axios.request({
        url: 'shop/' + id,
        method: 'delete'
    })

}


export const shopUpdate = (formData) => {
    return axios.request({
        url: 'shop/' + formData.id,
        data: formData,
        method: 'put'
    })
}


export const shopCheck = (shop_name, expectId = null) => {
    return axios.request({
        url: 'shop/checkExist',
        params: {
            shop_name,
            expectId
        },
        method: 'get'
    })
}


export const getOptionsShop = () => {
    return axios.request({
        url: 'shop/getOptionsShop',
        method: 'get'
    })
}


