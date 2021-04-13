import axios from "@/libs/api.request";


export const areaIndex = (formData) => {
    return axios.request({
        url: 'area',
        params: formData,
        method: 'get'
    })
}


export const areaStore = (formData) => {
    return axios.request({
        url: 'area',
        data: formData,
        method: 'post'
    })
}


export const areaDel = (id) => {
    return axios.request({
        url: 'area/' + id,
        method: 'delete'
    })

}


export const areaUpdate = (formData) => {
    return axios.request({
        url: 'area/' + formData.id,
        data: formData,
        method: 'put'
    })
}


export const areaCheck = (area, expectId = null) => {
    return axios.request({
        url: 'area/checkExist',
        params: {
            area,
            expectId
        },
        method: 'get'
    })
}


export const getOptionsArea = () => {
    return axios.request({
        url: 'area/getOptionsArea',
        method: 'get'
    })
}


