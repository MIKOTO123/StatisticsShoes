import axios from "@/libs/api.request";


export const importErrorIndex = (formData) => {
    return axios.request({
        url: 'importError',
        params: formData,
        method: 'get'
    })
}


export const importErrorDel = (id) => {
    return axios.request({
        url: 'importError/' + id,
        method: 'delete'
    })

}
