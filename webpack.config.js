require("@babel/polyfill");
const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
var LiveReloadPlugin = require('webpack-livereload-plugin');

const config = {
  devtool: 'source-map',
  mode: 'development',
  entry: {
    ebl: ['@babel/polyfill', './index.js'],
    eblAdmin: ['@babel/polyfill', './index.admin.js']
  },
  output: {
    path: path.resolve(__dirname + '/assets/js/build'),
    filename: '[name].js',
    sourceMapFilename: '[name].map',
    chunkFilename: '[id].js'
  },
  resolve: {
    modules: [path.resolve(__dirname, 'node_modules'), 'node_modules']
  },
  module: {
    rules: [
      //Babel
      {
        test: /\.js$/,
        exclude: /node_modules\/(?!nav)/,
        loader: "babel-loader",
        query: {
          "presets": ["@babel/preset-env", "@babel/preset-react"]
        }
      },
      //SCSS
      {
        test: /\.scss$/,
        use: ExtractTextPlugin.extract({
          publicPath: '/build/assets',
          use: [
            {
              loader: "css-loader",
              options: {
                sourceMap: true,
              }
            },
            {
              loader: "sass-loader",
              options: {
                // Import all variables. This is required for server-side compiler compatibility.
                data: '@import "customizer-variables"; @import "variables";',
                includePaths: [
                  path.join(__dirname, 'assets/css')
                ]
              }
            },
            {
              loader: 'postcss-loader'
            }],
        }),
      },
      //Images
      {
        test: /\.(png|jpg|gif)$/,
        use: [
          {
            loader: 'file-loader',
            options: {}
          }
        ]
      }
    ],

  },
  plugins: [
    new ExtractTextPlugin('./build/assets/style.css'),
    new LiveReloadPlugin({
      hostname: 'localhost',
    }),
  ],
  node: {
    fs: 'empty'
  }
};

module.exports = config;