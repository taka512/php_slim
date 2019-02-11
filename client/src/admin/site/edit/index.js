"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var React = require("react");
var ReactDOM = require("react-dom");
var react_redux_1 = require("react-redux");
var TagSiteField_1 = require("./container/TagSiteField");
var store_1 = require("./store");
ReactDOM.render(React.createElement(react_redux_1.Provider, { store: store_1.store },
    React.createElement(TagSiteField_1.default, null)), document.getElementById('root'));
//# sourceMappingURL=index.js.map