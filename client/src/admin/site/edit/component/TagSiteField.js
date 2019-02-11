"use strict";
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
Object.defineProperty(exports, "__esModule", { value: true });
var React = require("react");
var TagSiteField = /** @class */ (function (_super) {
    __extends(TagSiteField, _super);
    function TagSiteField() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    TagSiteField.prototype.render = function () {
        var _this = this;
        var list = [];
        for (var i in this.props.tags) {
            var tag = this.props.tags[i];
            console.log(tag);
            list.push(React.createElement(React.Fragment, null,
                React.createElement("input", { type: "checkbox", name: "tags[]", value: "{tag.id}" }),
                tag.name));
        }
        return (React.createElement("div", { className: "form-group" },
            React.createElement("label", { htmlFor: "inputTagSite" }, "\u30BF\u30B0"),
            React.createElement("input", { type: "text", id: "inputTagSite", name: "tag_site", onChange: function (e) { return _this.props.actions.getTags(e.target.value); }, value: this.props.searchWord }),
            list));
    };
    return TagSiteField;
}(React.Component));
exports.default = TagSiteField;
//# sourceMappingURL=TagSiteField.js.map