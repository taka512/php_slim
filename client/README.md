# clientコードを触る前に学ぶべき事

client環境はreact/typescript/babel/webpackの要素で構成されています。  
一気に学ぼうとすると、混乱すると思うので順を追って学ぶ術を記述します

## reactについて学ぶ

動かす前に、reactがどいうものなのか知ると良いです。本家のチュートリアルが解りやすいです。
https://reactjs.org/tutorial/tutorial.html

## reactに動作させる環境について学ぶ

ローカル環境にnode/npmを入れましょう。バージョンは最新版をインストールします  

**node/npm**  
https://qiita.com/tabolog/items/da18143e70f40e356b5d

babelはESxxを変換するツール/webpackは変換したjsを纏めるツールです。  
ビルド環境を学んでローカルでreactコードを書けるようになりましょう

**babel/webpack**  
https://ics.media/entry/16028

**webpack-dev-server**  
https://qiita.com/riversun/items/d27f6d3ab7aaa119deab

## reduxについて学ぶ

webページを作るにはreact+redux(flux)のように状態管理ライブラリを組み合わせて実装されます。  
reduxを使っているのでreduxについて学びましょう

**redux**  
https://qiita.com/erukiti/items/e16aa13ad81d5938374e

## react routerについて学ぶ

react-routerはjs内でurlを持たせる機能です
https://reacttraining.com/react-router/web/guides/quick-start

## typescriptについて学ぶ

typescriptはjavascriptに型を導入して、コードの品質を向上させます。 

## jestについて学ぶ

testはjestを使います。fluct_ms側で使用してるので採用しました

