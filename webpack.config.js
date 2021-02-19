const path = require('path')

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/client/src'),
            '@common@': path.resolve(__dirname, 'resources/common'),
            '_c': path.resolve(__dirname, 'resources/client/src/components'),
            'icon_cus': path.resolve(__dirname, 'resources/client/src/assets/icon_custom'),
        },
    },
}
