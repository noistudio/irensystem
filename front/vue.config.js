const webpack = require('webpack');

module.exports = {

    // devServer: {
    //     open: process.platform === 'darwin',
    //     port: 443,
    //     host: 'example.com',
    //
    //     https: true,
    //
    //
    //     hotOnly: false,
    // },
    configureWebpack: {
        // Set up all the aliases we use in our app.
        plugins: [
            new webpack.optimize.LimitChunkCountPlugin({
                maxChunks: 6
            })
        ]
    },
    pwa: {
        name: 'ArtemDev.ru',
        themeColor: '#172b4d',
        msTileColor: '#172b4d',
        appleMobileWebAppCapable: 'yes',
        appleMobileWebAppStatusBarStyle: '#172b4d'
    },
    css: {
        // Enable CSS source maps.
        sourceMap: process.env.NODE_ENV !== 'production'
    }
};
