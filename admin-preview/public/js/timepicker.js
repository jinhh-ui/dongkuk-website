/**
 * 커스텀 타임 피커 (드럼 스크롤)
 * 사용법: new TimePicker('#inputTime')
 * 옵션: { defaultTime: '00:00', onChange: function(time){} }
 */
(function(root){
    function TimePicker(selector, opts) {
        opts = opts || {};
        this.input = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!this.input) return;

        var parts = (this.input.value || opts.defaultTime || '00:00').split(':');
        this.selectedH = parseInt(parts[0]) || 0;
        this.selectedM = parseInt(parts[1]) || 0;
        this.onChange = opts.onChange || null;

        this._build();
        this._bind();
    }

    TimePicker.prototype._build = function() {
        // 드롭다운 컨테이너
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'tp-dropdown';
        this.dropdown.style.display = 'none';

        // 바디
        var body = document.createElement('div');
        body.className = 'tp-body';

        this.hourCol = document.createElement('div');
        this.hourCol.className = 'tp-col';

        var sep = document.createElement('div');
        sep.className = 'tp-sep';
        sep.textContent = ':';

        this.minCol = document.createElement('div');
        this.minCol.className = 'tp-col';

        body.appendChild(this.hourCol);
        body.appendChild(sep);
        body.appendChild(this.minCol);
        this.dropdown.appendChild(body);

        // 시간 아이템 생성
        var self = this;
        for (var h = 0; h < 24; h++) {
            var el = document.createElement('div');
            el.className = 'tp-item' + (h === this.selectedH ? ' tp-selected' : '');
            el.textContent = String(h).padStart(2, '0');
            el.dataset.val = h;
            el.addEventListener('click', (function(v){ return function(){ self._pickHour(v); }; })(h));
            this.hourCol.appendChild(el);
        }
        for (var m = 0; m < 60; m++) {
            var el2 = document.createElement('div');
            el2.className = 'tp-item' + (m === this.selectedM ? ' tp-selected' : '');
            el2.textContent = String(m).padStart(2, '0');
            el2.dataset.val = m;
            el2.addEventListener('click', (function(v){ return function(){ self._pickMin(v); }; })(m));
            this.minCol.appendChild(el2);
        }

        // 위치: input 부모 기준
        this.input.parentElement.style.position = 'relative';
        this.input.parentElement.appendChild(this.dropdown);
    };

    TimePicker.prototype._bind = function() {
        var self = this;

        this.input.addEventListener('click', function(e) {
            e.stopPropagation();
            var show = self.dropdown.style.display === 'none';
            self.dropdown.style.display = show ? '' : 'none';
            if (show) self._scrollToSelected();
        });

        document.addEventListener('click', function(e) {
            if (!self.dropdown.contains(e.target) && e.target !== self.input) {
                self.dropdown.style.display = 'none';
            }
        });
    };

    TimePicker.prototype._pickHour = function(v) {
        this.selectedH = v;
        this._update();
    };

    TimePicker.prototype._pickMin = function(v) {
        this.selectedM = v;
        this._update();
    };

    TimePicker.prototype._update = function() {
        var time = String(this.selectedH).padStart(2,'0') + ':' + String(this.selectedM).padStart(2,'0');
        this.input.value = time;

        this.hourCol.querySelectorAll('.tp-item').forEach(function(el){
            el.classList.toggle('tp-selected', parseInt(el.dataset.val) === this.selectedH);
        }.bind(this));
        this.minCol.querySelectorAll('.tp-item').forEach(function(el){
            el.classList.toggle('tp-selected', parseInt(el.dataset.val) === this.selectedM);
        }.bind(this));

        if (this.onChange) this.onChange(time);
    };

    TimePicker.prototype._scrollToSelected = function() {
        var selH = this.hourCol.querySelector('.tp-selected');
        var selM = this.minCol.querySelector('.tp-selected');
        if (selH) this.hourCol.scrollTop = selH.offsetTop - this.hourCol.offsetHeight / 2 + selH.offsetHeight / 2;
        if (selM) this.minCol.scrollTop = selM.offsetTop - this.minCol.offsetHeight / 2 + selM.offsetHeight / 2;
    };

    TimePicker.prototype.getValue = function() {
        return String(this.selectedH).padStart(2,'0') + ':' + String(this.selectedM).padStart(2,'0');
    };

    root.TimePicker = TimePicker;
})(window);
