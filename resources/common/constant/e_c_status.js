/**
 * 未认证
 * @type {number}
 */
export const STATUS_E_C_REVIEW_NOT = 0;

/**
 * 认证中
 * @type {number}
 */
export const STATUS_E_C_REVIEWING = 1;

/**
 * 认证通过
 * @type {number}
 */
export const STATUS_E_C_REVIEWED = 2;

/**
 * 认证失败
 * @type {number}
 */
export const STATUS_E_C_REVIEW_FAILD = 3;


export const STATUS_TO_LABEL_SYS = [
    {
        label: '待审核',
        value: STATUS_E_C_REVIEWING
    },
    {
        label: '驳回',
        value: STATUS_E_C_REVIEW_FAILD
    },
    {
        label: '通过',
        value: STATUS_E_C_REVIEWED
    },
]
