import dayjs from "dayjs";


export const filterDefaultValue = function (val, defaultValue) {
    return val ? val : defaultValue;
}


export const filterDateFormat = function (val, format = 'YYYY-MM-DD HH:mm:ss') {
    if (!isNaN(val)) {
        val = val.toString().length == 10 ? val * 1000 : val;
    }
    return dayjs(val).format(format)
}


export const filterNumber = function (val, defaultval = 0) {
    return val ? val : defaultval;
}

export const filterFormatPrice = function (val, decimals = 2) {
    return parseFloat(val).toFixed(decimals)
}


export const filterFormatCount = function (val) {
    let tmpInt = parseInt(val);
    let sum = tmpInt / 10000;
    return sum > 0 ? sum + "万" : tmpInt;
}
