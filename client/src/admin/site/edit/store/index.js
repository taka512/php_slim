"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var redux_thunk_1 = require("redux-thunk");
var redux_logger_1 = require("redux-logger");
var redux_1 = require("redux");
var reducer_1 = require("../reducer");
exports.store = redux_1.createStore(reducer_1.default, redux_1.applyMiddleware(redux_thunk_1.default, redux_logger_1.createLogger()));
//# sourceMappingURL=index.js.map