(() => {
    class OwO {
        constructor(option) {
            const defaultOption = {
                logo: 'OwO表情',
                container: document.getElementsByClassName('OwO')[0],
                target: document.getElementsByTagName('textarea')[0],
                position: 'down',
                width: '100%',
                maxHeight: '250px',
                api: 'https://api.anotherhome.net/OwO/OwO.json'
            };
            for (let defaultKey in defaultOption) {
                if (defaultOption.hasOwnProperty(defaultKey) && !option.hasOwnProperty(defaultKey)) {
                    option[defaultKey] = defaultOption[defaultKey];
                }
            }
            this.container = option.container;
            this.target = option.target;
            if (option.position === 'up') {
                this.container.classList.add('OwO-up');
            }

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    if (xhr.status >= 200 && xhr.status < 300 || xhr.status === 304) {
                        this.odata = JSON.parse(xhr.responseText);
                        this.init(option);
                    }
                    else {
                        console.log('OwO data request was unsuccessful: ' + xhr.status);
                    }
                }
            };
            xhr.open('get', option.api, true);
            xhr.send(null);
        }

        init(option) {
            this.area = option.target;
            this.packages = Object.keys(this.odata);

            // fill in HTML
            let html = `
            <div class="OwO-logo"><span>${option.logo}</span></div>
            <div class="OwO-body" style="width: ${option.width}">`;
            
            for (let i = 0; i < this.packages.length; i++) {

                html += `
                <ul class="OwO-items OwO-items-${this.odata[this.packages[i]].type}" style="max-height: ${parseInt(option.maxHeight) - 53 + 'px'};">`;
                var type=this.odata[this.packages[i]].type;
                let opackage = this.odata[this.packages[i]].container;
                for (let i = 0; i < opackage.length; i++) {
                    if(type=="image"){
                        html += `
                    <li class="OwO-item" data-id="${opackage[i].data}" title="${opackage[i].text}">${opackage[i].icon}</li>`;
                    }else{
                        html += `
                    <li class="OwO-item" data-id="not-given" title="${opackage[i].text}">${opackage[i].icon}</li>`;
                    }
                }

                html += `
                </ul>`;
            }
            
            html += `
                <div class="OwO-bar">
                    <ul class="OwO-packages">`;

                    for (let i = 0; i < this.packages.length; i++) {

                        html += `
                        <li><span>${this.packages[i]}</span></li>`

                    }

            html += `
                    </ul>
                </div>
            </div>
            `;
            this.container.innerHTML = html;

            // bind event
            this.logo = this.container.getElementsByClassName('OwO-logo')[0];
            this.logo.addEventListener('click', () => {
                this.toggle();
            });

            this.container.getElementsByClassName('OwO-body')[0].addEventListener('click', (e)=> {
                let target = null;
                if (e.target.classList.contains('OwO-item')) {
                    target = e.target;
                }
                else if (e.target.parentNode.classList.contains('OwO-item')) {
                    target = e.target.parentNode;
                }
                if (target) {
                    const cursorPos = this.area.selectionEnd;
                    let areaValue = this.area.value;
                    if(target.dataset.id=="not-given"){
                        this.area.value = areaValue.slice(0, cursorPos) + target.innerHTML + areaValue.slice(cursorPos)+` `;
                    }else{
                        this.area.value = areaValue.slice(0, cursorPos) + target.dataset.id + areaValue.slice(cursorPos)+` `;
                    }
                    this.area.focus();
                    this.toggle();
                }
            });

            this.packagesEle = this.container.getElementsByClassName('OwO-packages')[0];
            for (let i = 0; i < this.packagesEle.children.length; i++) {
                ((index) =>{
                    this.packagesEle.children[i].addEventListener('click', () => {
                        this.tab(index);
                    });
                })(i);
            }

            this.tab(0);
        }

        toggle() {
            if (this.container.classList.contains('OwO-open')) {
                this.container.classList.remove('OwO-open');
            }
            else {
                this.container.classList.add('OwO-open');
            }
        }

        tab(index) {
            const itemsShow = this.container.getElementsByClassName('OwO-items-show')[0];
            if (itemsShow) {
                itemsShow.classList.remove('OwO-items-show');
            }
            this.container.getElementsByClassName('OwO-items')[index].classList.add('OwO-items-show');

            const packageActive = this.container.getElementsByClassName('OwO-package-active')[0];
            if (packageActive) {
                packageActive.classList.remove('OwO-package-active');
            }
            this.packagesEle.getElementsByTagName('li')[index].classList.add('OwO-package-active');
        }
    }
    if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
        module.exports = OwO;
    }
    else {
        window.OwO = OwO;
    }
})();
