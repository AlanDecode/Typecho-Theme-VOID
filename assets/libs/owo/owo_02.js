/* eslint-disable linebreak-style */
/* eslint-disable no-console */
/* eslint-disable no-undef */
/* eslint-disable quotes */
/* eslint-disable no-unused-vars */
'use strict';

function insertAtCursor(myField, myValue) {
    var textTop = myField.scrollTop;
    var documentTop = document.documentElement.scrollTop;

    //IE 浏览器
    if (document.selection) {
        myField.focus();
        var sel = document.selection.createRange();
        sel.text = myValue;
        sel.select();
    }

    //FireFox、Chrome等
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;

        myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);

        myField.focus();
        myField.selectionStart = startPos + myValue.length;
        myField.selectionEnd = startPos + myValue.length;
    } else {
        
        myField.value += myValue;
        myField.focus();
    }

    myField.scrollTop = textTop;
    document.documentElement.scrollTop=documentTop;
}

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

(function () {
    var OwO = function () {
        function OwO(option) {
            var _this = this;

            _classCallCheck(this, OwO);

            var defaultOption = {
                logo: 'OwO',
                container: document.getElementsByClassName('OwO')[0],
                target: document.getElementsByTagName('textarea')[0],
                position: 'down',
                width: '100%',
                maxHeight: '250px',
                api: 'https://api.anotherhome.net/OwO/OwO.json'
            };
            for (var defaultKey in defaultOption) {
                if (defaultOption.hasOwnProperty(defaultKey) && !option.hasOwnProperty(defaultKey)) {
                    option[defaultKey] = defaultOption[defaultKey];
                }
            }
            this.container = option.container;
            this.target = option.target;
            if (option.position === 'up') {
                this.container.classList.add('OwO-up');
            }

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status >= 200 && xhr.status < 300 || xhr.status === 304) {
                        _this.odata = JSON.parse(xhr.responseText);
                        _this.init(option);
                    } else {
                        console.log('OwO data request was unsuccessful: ' + xhr.status);
                    }
                }
            };
            xhr.open('get', option.api, true);
            xhr.send(null);
        }

        _createClass(OwO, [{
            key: 'init',
            value: function init(option) {
                var _this2 = this;

                this.area = option.target;
                this.packages = Object.keys(this.odata);

                // fill in HTML
                var html = '\n            <div class="OwO-logo"><span>' + option.logo + '</span></div>\n            <div class="OwO-body" style="width: ' + option.width + '">';

                for (var i = 0; i < this.packages.length; i++) {

                    html += '\n                <ul class="OwO-items OwO-items-' + this.odata[this.packages[i]].type + '" style="max-height: ' + (parseInt(option.maxHeight) - 53 + 'px') + ';">';
                    var type = this.odata[this.packages[i]].type;
                    var opackage = this.odata[this.packages[i]].container;
                    for (var _i = 0; _i < opackage.length; _i++) {
                        if (type == "image") {
                            html += '\n                    <li class="OwO-item" data-id="' + opackage[_i].data + '" title="' + opackage[_i].text + '">' + opackage[_i].icon + '</li>';
                        } else {
                            html += '\n                    <li class="OwO-item" data-id="not-given" title="' + opackage[_i].text + '">' + opackage[_i].icon + '</li>';
                        }
                    }

                    html += '\n                </ul>';
                }

                html += '\n                <div class="OwO-bar">\n                    <ul class="OwO-packages">';

                for (var _i2 = 0; _i2 < this.packages.length; _i2++) {

                    html += '\n                        <li><span>' + this.packages[_i2] + '</span></li>';
                }

                html += '\n                    </ul>\n                </div>\n            </div>\n            ';
                this.container.innerHTML = html;

                // bind event
                this.logo = this.container.getElementsByClassName('OwO-logo')[0];
                this.logo.addEventListener('click', function () {
                    _this2.toggle();
                });

                this.container.getElementsByClassName('OwO-body')[0].addEventListener('click', function (e) {
                    var target = null;
                    if (e.target.classList.contains('OwO-item')) {
                        target = e.target;
                    } else if (e.target.parentNode.classList.contains('OwO-item')) {
                        target = e.target.parentNode;
                    }
                    if (target) {
                        var cursorPos = _this2.area.selectionEnd;
                        var areaValue = _this2.area.value;
                        if (target.dataset.id == "not-given") {
                            insertAtCursor(_this2.area, ' ' + target.innerHTML + ' ');
                            //_this2.area.value = areaValue.slice(0, cursorPos) + target.innerHTML + areaValue.slice(cursorPos) + ' ';
                        } else {
                            insertAtCursor(_this2.area, ' ' + target.dataset.id + ' ');
                            //_this2.area.value = areaValue.slice(0, cursorPos) + target.dataset.id + areaValue.slice(cursorPos) + ' ';
                        }
                        _this2.area.focus();
                        _this2.toggle();
                    }
                });

                this.packagesEle = this.container.getElementsByClassName('OwO-packages')[0];

                var _loop = function _loop(_i3) {
                    (function (index) {
                        _this2.packagesEle.children[_i3].addEventListener('click', function () {
                            _this2.tab(index);
                        });
                    })(_i3);
                };

                for (var _i3 = 0; _i3 < this.packagesEle.children.length; _i3++) {
                    _loop(_i3);
                }

                this.tab(0);
            }
        }, {
            key: 'toggle',
            value: function toggle() {
                if (this.container.classList.contains('OwO-open')) {
                    this.container.classList.remove('OwO-open');
                } else {
                    this.container.classList.add('OwO-open');
                }
            }
        }, {
            key: 'tab',
            value: function tab(index) {
                var itemsShow = this.container.getElementsByClassName('OwO-items-show')[0];
                if (itemsShow) {
                    itemsShow.classList.remove('OwO-items-show');
                }
                this.container.getElementsByClassName('OwO-items')[index].classList.add('OwO-items-show');
                
                if(!this.container.getElementsByClassName('OwO-items')[index].classList.contains('OwO-image-items-load')
                    &&this.container.getElementsByClassName('OwO-items')[index].classList.contains('OwO-items-image'))
                {
                    this.container.getElementsByClassName('OwO-items')[index].classList.add('OwO-image-items-load');
                    var imgs = this.container.getElementsByClassName('OwO-items')[index].getElementsByTagName('img');
                    for (var i = 0; i < imgs.length; i++) {
                        imgs[i].setAttribute('src',imgs[i].dataset.src);
                    }
                }

                var packageActive = this.container.getElementsByClassName('OwO-package-active')[0];
                if (packageActive) {
                    packageActive.classList.remove('OwO-package-active');
                }
                this.packagesEle.getElementsByTagName('li')[index].classList.add('OwO-package-active');
            }
        }]);

        return OwO;
    }();

    if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
        module.exports = OwO;
    } else {
        window.OwO = OwO;
    }
})();