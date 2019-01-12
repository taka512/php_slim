var debug   = process.env.NODE_ENV !== "production";
var webpack = require('webpack');
var path    = require('path');

module.exports = {
  context: path.join(__dirname, "src"),
  // モード値を production に設定すると最適化された状態で、
  // development に設定するとソースマップ有効でJSファイルが出力される
  mode: 'production',

  // メインとなるJavaScriptファイル（エントリーポイント）
  entry: './js/client.js',
  // ファイルの出力設定
  output: {
    //  出力ファイルのディレクトリ名
    path: `${__dirname}/../public/dist`,
    // 出力ファイル名
    filename: 'client.js'
  },
  module: {
    rules: [
      {
        // 拡張子 .js|jsx の場合
        test: /\.(js|jsx)$/,
        use: [
          {
            // Babel を利用する
            loader: 'babel-loader',
            // Babel のオプションを指定する
            options: {
              presets: [
                // プリセットを指定することで、ES2018 を ES5 に変換
                '@babel/preset-env',
                // React の JSX を解釈
                '@babel/react'
              ]
            }
          }
        ]
      }
    ]
  },
  plugins: debug ? [] : [
    new webpack.optimize.OccurrenceOrderPlugin(),
    new webpack.optimize.UglifyJsPlugin({ mangle: false, sourcemap: false }),
  ],
  devtool: 'inline-source-map',//ブラウザでのデバッグ用にソースマップを出力する
  devServer: {
    open: false,//ブラウザを自動で開くかどうか
    openPage: "index.html",//自動で指定したページを開く
    contentBase: path.join(__dirname, 'public'),// HTML等コンテンツのルートディレクトリ
    watchContentBase: true,//コンテンツの変更監視をする
    port: 8082, // ポート番号
  }
};
