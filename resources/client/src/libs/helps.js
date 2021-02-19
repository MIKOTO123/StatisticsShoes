import axios from "@/libs/api.request";

export function downloadImg(url) {
    return new Promise((resolve, reject) => {
        axios.request({
            url: url,
            method: 'get',
            responseType: 'blob'
        }).then(({data}) => {
            resolve(window.URL.createObjectURL(data))
        }).catch(error => {
            reject(error)
        })
    })
}
