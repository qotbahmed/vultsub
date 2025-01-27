const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    entry: {
        // style: path.resolve(__dirname, 'frontend/scss/style.scss'),
        app: path.resolve(__dirname, 'frontend/js/app.js'),
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'frontend/web/build'),
    },
    plugins: [new MiniCssExtractPlugin()],
    module: {
        rules: [
            {
                test: /\.s[ac]ss$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    // Translates CSS into CommonJS
                    "css-loader",
                    // Compiles Sass to CSS
                    "sass-loader",
                ],
            },
            {
                test: /\.(png|woff|woff2|eot|ttf|svg|jpe?g|gif)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
                loader: "file-loader",
                
            },
        ],
    },
};