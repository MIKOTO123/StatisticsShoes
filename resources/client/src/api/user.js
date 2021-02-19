import axios from '@/libs/api.request'

export const login = ({userName, password}) => {
    const data = {
        name: userName,
        password
    }
    return axios.request({
        url: 'login',
        data,
        method: 'post'
    })
}

export const getUserInfo = (token) => {
    return axios.request({
        url: 'get_user_info',
        // params: {
        //   token
        // },
        method: 'get'
    })
}

export const logout = (token) => {
    return axios.request({
        url: 'logout',
        method: 'post'
    })
}

export const getUnreadCount = () => {
    return axios.request({
        url: 'message/count',
        method: 'get'
    })
}

export const getMessage = () => {
    return axios.request({
        url: 'message/init',
        method: 'get'
    })
}

export const getContentByMsgId = msg_id => {
    return axios.request({
        url: 'message/content',
        method: 'get',
        params: {
            msg_id
        }
    })
}

export const hasRead = msg_id => {
    return axios.request({
        url: 'message/has_read',
        method: 'post',
        data: {
            msg_id
        }
    })
}

export const removeReaded = msg_id => {
    return axios.request({
        url: 'message/remove_readed',
        method: 'post',
        data: {
            msg_id
        }
    })
}

export const restoreTrash = msg_id => {
    return axios.request({
        url: 'message/restore',
        method: 'post',
        data: {
            msg_id
        }
    })
}


export const applyAuthentication = formData => {
    return axios.request({
        url: 'usersinfo/applyAuthentication',
        method: 'post',
        data: formData
    })
}

export const detailInfo = formData => {
    return axios.request({
        url: 'usersinfo/detailInfo',
        method: 'post',
        data: formData
    })
}


export const resetPwd = formData => {
    return axios.request({
        url: 'usersinfo/resetPwd',
        method: 'post',
        data: formData
    })
}


export const resetMobile = formData => {
    return axios.request({
        url: 'usersinfo/resetMobile',
        method: 'post',
        data: formData
    })
}


export const sendModifyMobileNewCode = mobile => {
    return axios.request({
        url: 'usersinfo/sendModifyMobileNewCode',
        method: 'post',
        data: {
            mobile
        }
    })
}

export const sendModifyMobileOldCode = () => {
    return axios.request({
        url: 'usersinfo/sendModifyMobileOldCode',
        method: 'post',
    })
}


export const getECInfo = () => {
    return axios.request({
        url: 'usersinfo/getECInfo',
        method: 'get',
    })
}


export const checkNewMobileValid = mobile => {
    return axios.request({
        url: 'usersinfo/checkNewMobileValid',
        method: 'post',
        data: {
            mobile
        }
    })
}


export const sendRegisterCode = mobile => {
    return axios.request({
        url: 'sendRegisterCode',
        method: 'post',
        data: {
            mobile
        }
    })
}


export const checkMobileExist = mobile => {
    return axios.request({
        url: 'checkMobileExist',
        method: 'post',
        data: {
            mobile
        }
    })
}


export const checkUserNameExist = name => {
    return axios.request({
        url: 'checkUserNameExist',
        method: 'post',
        data: {
            name
        }
    })
}


export const registerUser = formData => {
    return axios.request({
        url: 'register',
        method: 'post',
        data: formData
    })
}


export const getCreditInfo = () => {
    return axios.request({
        url: 'usersinfo/getCreditInfo',
        method: 'get',
    })
}



export const getInvoiceSumEnable = () => {
    return axios.request({
        url: 'usersinfo/getInvoiceSumEnable',
        method: 'get',
    })
}

