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

## react routerについて学ぶ

react-routerはjs内でurlを持たせる機能です
https://reacttraining.com/react-router/web/guides/quick-start

## reduxについて学ぶ

webページを作るにはreact+redux(flux)のように状態管理ライブラリを組み合わせて実装されます。  
reduxを使っているのでreduxについて学びましょう

**redux**  
https://redux.js.org/introduction/getting-started


基本的な流れとしては「Actions -> Reducers -> Store」でStoreが状態（state）を持ち、Actionが発生した際に、Reducerを使ってStoreの状態（State）を更新する

### action

Actionとは、アクションの種別を表すtypeと、任意のパラメータを持ったアクション時に発生するデータの定義

Action Creatorsは、アクションを作成する関数

```
ex)
/*
 * action types
 */

export const ADD_TODO = 'ADD_TODO'

/*
 * action creators
 */

export function addTodo(text) {
  return { type: ADD_TODO, text }
}
```

### reducer

「Reducer」は、Actionに呼応してアプリケーションの状態（state）をどのように変化させるか指定する役割を持った関数です。  
Reducerは、「現在の状態（state）」と「受け取ったAction」を引数に取り、新しい状態を返す関数として実装  

```
ex)
function todoApp(state = initialState, action) {
  switch (action.type) {
    case ADD_TODO:
      return Object.assign({}, state, {
        todos: [
          ...state.todos,
          {
            text: action.text,
            completed: false
          }
        ]
      })
    default:
      return state
  }
}
```

### store

* アプリケーションの状態（state）を保持します
* getState()メソッドを通して状態（state）へのアクセスを許可します
* dispatch(action)メソッドを通して状態（state）の更新を許可します
* subscribe(listener)メソッドを通してリスナーを登録します
* subscribe(listener)メソッドによって返された関数を通してリスナーの登録解除をハンドリングします

### Presentational Components

データがどこから来たのか、どのように変更するのかを知らずレンダリングするだけのコンポーネントでreduxに依存しない

### Container Components

プレゼンテーションコンポーネントをReduxに接続するためのコンテナコンポーネント

### react-redux

reactとreduxの連携に必要

https://react-redux.js.org/introduction/quick-start

## typescriptについて学ぶ

typescriptはjavascriptに型を導入して、コードの品質を向上させます。 

## jestについて学ぶ

testはjestを使います。

