<script>
    Array.prototype.compare = function (testArr) {
        if (this.length != testArr.length) {
            return false;
        }

        for (var i = 0; i < testArr.length; i++) {
            if (this[i].compare) {
                if (!this[i].compare(testArr[i])) {
                    return false;
                }
            }
            if (this[i] !== testArr[i]) {
                return false;
            }
        }
        return true;
    };

// Compute the intersection of n arrays
    Array.prototype.intersect = function () {
        if (!arguments.length) {
            return [];
        }

        var a1 = this;
        var a = null;
        var a2 = null;
        var n = 0;
        while (n < arguments.length) {
            a = [];
            a2 = arguments[n];
            var l = a1.length;
            var l2 = a2.length;
            for (var i = 0; i < l; i++) {
                for (var j = 0; j < l2; j++) {
                    if (a1[i] === a2[j]) {
                        a.push(a1[i]);
                    }
                }
            }
            a1 = a;
            n++;
        }
        return a.unique();
    };

    Array.prototype.union = function () {
        var a = [].concat(this);
        var l = arguments.length;
        for (var i = 0; i < l; i++) {
            a = a.concat(arguments[i]);
        }
        return a.unique();
    };

// Return new array with duplicate values removed
    Array.prototype.unique = function () {
        var a = [];
        var l = this.length;
        for (var i = 0; i < l; i++) {
            for (var j = i + 1; j < l; j++) {
                // If this[i] is found later in the array
                if (this[i] === this[j]) {
                    j = ++i;
                }
            }
            a.push(this[i]);
        }
        return a;
    };

// Checks whether a value exists in an array
    Array.prototype.exists = function (x) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] == x) {
                return true;
            }
        }
        return false;
    };

// creates an array with val repeated num times
    Array.prototype.append = function (val, num) {
        var a = [];
        for (var i = 0; i < num; i++) {
            a.push(val);
        }
        return a;
    };

    String.prototype.isInteger = function () {
        return parseInt(this, 10).toString() == this;
    };

    String.prototype.isFloat = function () {
        return !isNaN(parseFloat(this));
    };

    Number.prototype.isInteger = function () {
        return Math.floor(this) == this;
    };

    String.prototype.ucfirst = function () {
        return(this.charAt(0).toUpperCase() + this.substr(1).toLowerCase());
    };
    Ext.ns('iSimulate', 'iSimulate.Toolbar');
    Ext.BLANK_IMAGE_URL = '/js/ext-2.2.1/resources/images/default/s.gif';
    Ext.ux.clone = function (o) {
        if (!o || 'object' !== typeof o) {
            return o;
        }
        var c = 'function' === typeof o.pop ? [] : {};
        var p, v;
        for (p in o) {
            if (o.hasOwnProperty(p)) {
                v = o[p];
                if (v && 'object' === typeof v) {
                    c[p] = Ext.ux.clone(v);
                } else {
                    c[p] = v;
                }
            }
        }
        return c;
    };

    /*global Ext */

    Ext.ns('Ext.ux.tree');

    /**
     * @class Ext.ux.tree.ArrayTree
     * @extends Ext.tree.TreePanel
     */
    Ext.ux.tree.ArrayTree = Ext.extend(Ext.tree.TreePanel, {

        // {{{
        // configurables
        collapseAllText: 'Collapse All'

                /**
                 * @cfg {Object} defaultRootConfig Default configuration of root node 
                 * @private
                 */
        , defaultRootConfig: {
            loaded: true
            , expanded: true
            , leaf: false
            , id: Ext.id()
        }

        /**
         * @cfg {Boolean} defaultTools true to create Expand All/Collapse All tools
         */
        , defaultTools: true

        , expandAllText: 'Expand All'

                /**
                 * @cfg {Object} expandedNodes keeps currently expanded nodes paths for state keeping
                 * @private
                 */
        , expandedNodes: {}

        /**
         * @cfg {Object} rootConfig Configuration for the root node
         */

        /**
         * @cfg {Object} stateEvents allows us to keep expanded state
         * @private
         */
        , stateEvents: ['expandnode', 'collapsenode']
                // }}}
                // {{{
                /**
                 * initialize component
                 * @private
                 */
        , initComponent: function () {

            // create root config
            var cfg = Ext.apply(this.defaultRootConfig, this.rootConfig, {children: this.children});

            // configure ourselves
            Ext.apply(this, {
                root: new Ext.tree.AsyncTreeNode(cfg)
                , loader: new Ext.tree.TreeLoader({preloadChildren: true, clearOnLoad: false})
                , sorter: this.sort ? new Ext.tree.TreeSorter(this) : undefined
            }); // e/o apply

            if (this.defaultTools) {
                Ext.apply(this, {
                    tools: [{
                            id: 'minus'
                            , qtip: this.collapseAllText
                            , scope: this
                            , handler: this.collapseAll
                        }, {
                            id: 'plus'
                            , qtip: this.expandAllText
                            , scope: this
                            , handler: this.expandAll
                        }]
                });
            }

            // call parent
            Ext.ux.tree.ArrayTree.superclass.initComponent.apply(this, arguments);

            // handle expanded/collapsed state for state keeping
            if (false !== this.stateful) {
                this.on({
                    scope: this
                    , beforeexpandnode: this.beforeExpandNode
                    , beforecollapsenode: this.beforeCollapseNode
                });
            }

        } // e/o function initComponent
        // }}}
        // {{{
        /**
         * @private
         * Load root node on render. Required for upcoming Ext 2.2
         */
        , onRender: function () {
            Ext.ux.tree.ArrayTree.superclass.onRender.apply(this, arguments);
            this.loader.load(this.root);
        } // eo function onRender
        // }}}
        // {{{
        /**
         * restores tree state (expands nodes)
         * @private
         */
        , afterRender: function () {
            // call parent
            Ext.ux.tree.ArrayTree.superclass.afterRender.apply(this, arguments);

            // restore tree state
            for (var id in this.expandedNodes) {
                if (this.expandedNodes.hasOwnProperty(id)) {
                    this.expandPath(this.expandedNodes[id]);
                }
            }
        } // eo function onRender
        // }}}
        // {{{
        /**
         * saves path of the node
         * @private
         */
        , beforeExpandNode: function (n) {
            if (n.id) {
                this.expandedNodes[n.id] = n.getPath();
            }
        } // eo function beforeExpandNode
        // }}}
        // {{{
        /**
         * deletes expanded state
         */
        , beforeCollapseNode: function (n) {
            if (n.id) {
                delete(this.expandedNodes[n.id]);
                n.cascade(function (child) {
                    if (child.id) {
                        delete(this.expandedNodes[child.id]);
                    }
                }, this);
            }
        } // eo function beforeCollapseNode
        // }}}
        // {{{
        /**
         * returns the expandedNodes hash
         * @private
         */
        , getState: function () {
            return {expandedNodes: this.expandedNodes};
        } // eo function getState
        // }}}

    }); // eo extend

// register xtype
    Ext.reg('arraytree', Ext.ux.tree.ArrayTree);

// eof
    Ext.grid.LockingGridPanel = Ext.extend(Ext.grid.GridPanel, {
        getView: function () {
            if (!this.view) {
                this.view = new Ext.grid.LockingGridView(this.viewConfig);
            }
            return this.view;
        },

        initComponent: function () {
            if (!this.cm) {
                this.cm = new Ext.grid.LockingColumnModel(this.columns);
                delete this.columns;
            }
            Ext.grid.LockingGridPanel.superclass.initComponent.call(this);
        }
    });

    Ext.grid.LockingEditorGridPanel = Ext.extend(Ext.grid.EditorGridPanel, {
        getView: function () {
            if (!this.view) {
                this.view = new Ext.grid.LockingGridView(this.viewConfig);
            }
            return this.view;
        },

        initComponent: function () {
            if (!this.cm) {
                this.cm = new Ext.grid.LockingColumnModel(this.columns);
                delete this.columns;
            }
            Ext.grid.LockingEditorGridPanel.superclass.initComponent.call(this);
        }
    });

    Ext.grid.LockingGridView = Ext.extend(Ext.grid.GridView, {

        lockText: "Lock",
        unlockText: "Unlock",

        initTemplates: function () {
            if (!this.templates) {
                this.templates = {};
            }
            if (!this.templates.master) {
                this.templates.master = new Ext.Template(
                        '<div class="x-grid3" hidefocus="true">',
                        '<div class="x-grid3-locked">',
                        '<div class="x-grid3-header"><div class="x-grid3-header-inner"><div class="x-grid3-header-offset">{lockedHeader}</div></div><div class="x-clear"></div></div>',
                        '<div class="x-grid3-scroller"><div class="x-grid3-body">{lockedBody}</div><div class="x-grid3-scroll-spacer"></div></div>',
                        '</div>',
                        '<div class="x-grid3-viewport">',
                        '<div class="x-grid3-header"><div class="x-grid3-header-inner"><div class="x-grid3-header-offset">{header}</div></div><div class="x-clear"></div></div>',
                        '<div class="x-grid3-scroller"><div class="x-grid3-body">{body}</div><a href="#" class="x-grid3-focus" tabIndex="-1"></a></div>',
                        '</div>',
                        '<div class="x-grid3-resize-marker">&#160;</div>',
                        '<div class="x-grid3-resize-proxy">&#160;</div>',
                        '</div>'
                        );
            }
            Ext.grid.LockingGridView.superclass.initTemplates.call(this);
        },

        initElements: function () {
            var E = Ext.Element;
            var el = this.grid.getGridEl();
            el = el.dom.firstChild;
            var cs = el.childNodes;
            this.el = new E(el);
            this.lockedWrap = new E(cs[0]);
            this.lockedHd = new E(this.lockedWrap.dom.firstChild);
            this.lockedInnerHd = this.lockedHd.dom.firstChild;
            this.lockedScroller = new E(this.lockedWrap.dom.childNodes[1]);
            this.lockedBody = new E(this.lockedScroller.dom.firstChild);
            this.mainWrap = new E(cs[1]);
            this.mainHd = new E(this.mainWrap.dom.firstChild);
            this.innerHd = this.mainHd.dom.firstChild;
            this.scroller = new E(this.mainWrap.dom.childNodes[1]);
            if (this.forceFit) {
                this.scroller.setStyle('overflow-x', 'hidden');
            }
            this.mainBody = new E(this.scroller.dom.firstChild);
            this.focusEl = new E(this.scroller.dom.childNodes[1]);
            this.focusEl.swallowEvent("click", true);
            this.resizeMarker = new E(cs[2]);
            this.resizeProxy = new E(cs[3]);
        },

        getLockedRows: function () {
            return this.hasRows() ? this.lockedBody.dom.childNodes : [];
        },

        getLockedRow: function (row) {
            return this.getLockedRows()[row];
        },

        getCell: function (rowIndex, colIndex) {
            var locked = this.cm.getLockedCount();
            var row;
            if (colIndex < locked) {
                row = this.getLockedRow(rowIndex);
            } else {
                row = this.getRow(rowIndex);
                colIndex -= locked;
            }
            return row.getElementsByTagName('td')[colIndex];
        },

        getHeaderCell: function (index) {
            var locked = this.cm.getLockedCount();
            if (index < locked) {
                return this.lockedHd.dom.getElementsByTagName('td')[index];
            } else {
                return this.mainHd.dom.getElementsByTagName('td')[(index - locked)];
            }
        },

        scrollToTop: function () {
            Ext.grid.LockingGridView.superclass.scrollToTop.call(this);
            this.syncScroll();
        },

        syncScroll: function (e) {
            Ext.grid.LockingGridView.superclass.syncScroll.call(this, e);
            var mb = this.scroller.dom;
            this.lockedScroller.dom.scrollTop = mb.scrollTop;
        },

        processRows: function (startRow, skipStripe) {
            if (this.ds.getCount() < 1) {
                return;
            }
            skipStripe = skipStripe || !this.grid.stripeRows;
            startRow = startRow || 0;
            var cls = ' x-grid3-row-alt ';
            var rows = this.getRows();
            var lrows = this.getLockedRows();
            for (var i = startRow, len = rows.length; i < len; i++) {
                var row = rows[i];
                var lrow = lrows[i];
                row.rowIndex = i;
                lrow.rowIndex = i;
                if (!skipStripe) {
                    var isAlt = ((i + 1) % 2 === 0);
                    var hasAlt = (' ' + row.className + ' ').indexOf(cls) != -1;
                    if (isAlt == hasAlt) {
                        continue;
                    }
                    if (isAlt) {
                        row.className += " x-grid3-row-alt";
                        lrow.className += " x-grid3-row-alt";
                    } else {
                        row.className = row.className.replace("x-grid3-row-alt", "");
                        lrow.className = lrow.className.replace("x-grid3-row-alt", "");
                    }
                }
            }
        },

        updateSortIcon: function (col, dir) {
            var sc = this.sortClasses;
            var clen = this.cm.getColumnCount();
            var lclen = this.cm.getLockedCount();
            var hds = this.mainHd.select('td').removeClass(sc);
            var lhds = this.lockedHd.select('td').removeClass(sc);
            if (lclen > 0 && col < lclen) {
                lhds.item(col).addClass(sc[dir == "DESC" ? 1 : 0]);
            } else {
                hds.item(col - lclen).addClass(sc[dir == "DESC" ? 1 : 0]);
            }
        },

        updateColumnHidden: function (col, hidden) {
            var tw = this.cm.getTotalWidth();
            var lw = this.cm.getTotalLockedWidth();
            var lclen = this.cm.getLockedCount();
            this.innerHd.firstChild.firstChild.style.width = tw + 'px';
            var display = hidden ? 'none' : '';
            var hd = this.getHeaderCell(col);
            hd.style.display = display;
            var ns, gw;
            if (col < lclen) {
                ns = this.getLockedRows();
                gw = lw;
                this.lockedHd.dom.firstChild.firstChild.style.width = gw + 'px';
                this.mainWrap.dom.style.left = this.cm.getTotalLockedWidth() + 'px';
            } else {
                ns = this.getRows();
                gw = tw - lw;
                col -= lclen;
                this.innerHd.firstChild.firstChild.style.width = gw + 'px';
            }
            for (var i = 0, len = ns.length; i < len; i++) {
                ns[i].style.width = gw + 'px';
                ns[i].firstChild.style.width = gw + 'px';
                ns[i].firstChild.rows[0].childNodes[col].style.display = display;
            }
            this.onColumnHiddenUpdated(col, hidden, tw);
            delete this.lastViewWidth;
            this.layout();
        },

        syncHeaderHeight: function () {
            if (this.lockedInnerHd === undefined || this.innerHd === undefined) {
                return;
            }
            this.lockedInnerHd.firstChild.firstChild.style.height = "auto";
            this.innerHd.firstChild.firstChild.style.height = "auto";
            var height = (this.lockedInnerHd.firstChild.firstChild.offsetHeight > this.innerHd.firstChild.firstChild.offsetHeight) ?
                    this.lockedInnerHd.firstChild.firstChild.offsetHeight : this.innerHd.firstChild.firstChild.offsetHeight;
            this.lockedInnerHd.firstChild.firstChild.style.height = height + 'px';
            this.innerHd.firstChild.firstChild.style.height = height + 'px';
        },

        doRender: function (cs, rs, ds, startRow, colCount, stripe) {
            var ts = this.templates, ct = ts.cell, rt = ts.row, last = colCount - 1;
            var tw = this.cm.getTotalWidth();
            var lw = this.cm.getTotalLockedWidth();
            var clen = this.cm.getColumnCount();
            var lclen = this.cm.getLockedCount();
            var tstyle = 'width:' + this.getTotalWidth() + ';';
            var buf = [], lbuf = [], cb, lcb, c, p = {}, rp = {tstyle: tstyle}, r;
            for (var j = 0, len = rs.length; j < len; j++) {
                r = rs[j];
                cb = [];
                lcb = [];
                var rowIndex = (j + startRow);
                for (var i = 0; i < colCount; i++) {
                    c = cs[i];
                    p.id = c.id;
                    p.css = i === 0 ? 'x-grid3-cell-first ' : (i == last ? 'x-grid3-cell-last ' : '');
                    p.attr = p.cellAttr = "";
                    p.value = c.renderer(r.data[c.name], p, r, rowIndex, i, ds);
                    p.style = c.style;
                    if (p.value === undefined || p.value === "") {
                        p.value = "&#160;";
                    }
                    if (r.dirty && typeof r.modified[c.name] !== 'undefined') {
                        p.css += ' x-grid3-dirty-cell';
                    }
                    if (c.locked) {
                        lcb[lcb.length] = ct.apply(p);
                    } else {
                        cb[cb.length] = ct.apply(p);
                    }
                }
                var alt = [];
                if (stripe && ((rowIndex + 1) % 2 === 0)) {
                    alt[0] = "x-grid3-row-alt";
                }
                if (r.dirty) {
                    alt[1] = " x-grid3-dirty-row";
                }
                rp.cols = colCount;
                if (this.getRowClass) {
                    alt[2] = this.getRowClass(r, rowIndex, rp, ds);
                }
                rp.alt = alt.join(" ");
                rp.cells = lcb.join("");
                rp.tstyle = 'width:' + lw + 'px;';
                lbuf[lbuf.length] = rt.apply(rp);
                rp.cells = cb.join("");
                rp.tstyle = 'width:' + (tw - lw) + 'px;';
                buf[buf.length] = rt.apply(rp);
            }
            return [buf.join(""), lbuf.join("")];
        },

        layout: function () {
            if (!this.mainBody) {
                return;
            }
            var g = this.grid;
            var c = g.getGridEl(), cm = this.cm,
                    expandCol = g.autoExpandColumn,
                    gv = this;
            var lw = cm.getTotalLockedWidth();
            var csize = c.getSize(true);
            var vw = csize.width;
            if (vw < 20 || csize.height < 20) {
                return;
            }
            this.syncHeaderHeight();
            if (g.autoHeight) {
                this.scroller.dom.style.overflow = 'visible';
                this.lockedScroller.dom.style.overflow = 'visible';
            } else {
                this.el.setSize(csize.width, csize.height);
                var vh = csize.height - this.mainHd.getHeight();
                this.lockedScroller.setSize(lw, vh);
                this.scroller.setSize(vw - lw, vh);
                if (this.innerHd) {
                    this.innerHd.style.width = (vw) + 'px';
                }
            }
            if (this.forceFit) {
                if (this.lastViewWidth != vw) {
                    this.fitColumns(false, false);
                    this.lastViewWidth = vw;
                }
            } else {
                this.autoExpand();
                lw = cm.getTotalLockedWidth();
            }
            this.mainWrap.dom.style.left = lw + 'px';
            this.onLayout(vw, vh);
        },

        renderHeaders: function () {
            var cm = this.cm, ts = this.templates;
            var ct = ts.hcell;
            var tw = this.cm.getTotalWidth();
            var lw = this.cm.getTotalLockedWidth();
            var cb = [], lb = [], sb = [], lsb = [], p = {};
            for (var i = 0, len = cm.getColumnCount(); i < len; i++) {
                p.id = cm.getColumnId(i);
                p.value = cm.getColumnHeader(i) || "";
                p.style = this.getColumnStyle(i, true);
                p.tooltip = this.getColumnTooltip(i);
                if (cm.config[i].align == 'right') {
                    p.istyle = 'padding-right:16px';
                }
                if (cm.isLocked(i)) {
                    lb[lb.length] = ct.apply(p);
                } else {
                    cb[cb.length] = ct.apply(p);
                }
            }
            return [ts.header.apply({cells: cb.join(""), tstyle: 'width:' + (tw - lw) + ';'}),
                ts.header.apply({cells: lb.join(""), tstyle: 'width:' + (lw) + ';'})];
        },

        getColumnTooltip: function (i) {
            var tt = this.cm.getColumnTooltip(i);
            if (tt) {
                if (Ext.QuickTips.isEnabled()) {
                    return 'ext:qtip="' + tt + '"';
                } else {
                    return 'title="' + tt + '"';
                }
            }
            return "";
        },

        updateHeaders: function () {
            var hd = this.renderHeaders();
            this.innerHd.firstChild.innerHTML = hd[0];
            this.lockedInnerHd.firstChild.innerHTML = hd[1];
        },

        insertRows: function (dm, firstRow, lastRow, isUpdate) {
            if (firstRow === 0 && lastRow == dm.getCount() - 1) {
                this.refresh();
            } else {
                if (!isUpdate) {
                    this.fireEvent("beforerowsinserted", this, firstRow, lastRow);
                }
                var html = this.renderRows(firstRow, lastRow);
                var before = this.getRow(firstRow);
                if (before) {
                    Ext.DomHelper.insertHtml('beforeBegin', before, html[0]);
                } else {
                    Ext.DomHelper.insertHtml('beforeEnd', this.mainBody.dom, html[0]);
                }
                var beforeLocked = this.getLockedRow(firstRow);
                if (beforeLocked) {
                    Ext.DomHelper.insertHtml('beforeBegin', beforeLocked, html[1]);
                } else {
                    Ext.DomHelper.insertHtml('beforeEnd', this.lockedBody.dom, html[1]);
                }
                if (!isUpdate) {
                    this.fireEvent("rowsinserted", this, firstRow, lastRow);
                    this.processRows(firstRow);
                }
            }
        },

        removeRow: function (row) {
            Ext.removeNode(this.getRow(row));
            if (this.cm.getLockedCount() > 0) {
                Ext.removeNode(this.getLockedRow(row));
            }
        },

        getColumnData: function () {
            var cs = [], cm = this.cm, colCount = cm.getColumnCount();
            for (var i = 0; i < colCount; i++) {
                var name = cm.getDataIndex(i);
                cs[i] = {
                    name: (typeof name == 'undefined' ? this.ds.fields.get(i).name : name),
                    renderer: cm.getRenderer(i),
                    id: cm.getColumnId(i),
                    style: this.getColumnStyle(i),
                    locked: cm.isLocked(i)
                };
            }
            return cs;
        },

        renderBody: function () {
            var markup = this.renderRows();
            return [this.templates.body.apply({rows: markup[0]}), this.templates.body.apply({rows: markup[1]})];
        },

        refresh: function (headersToo) {
            this.fireEvent("beforerefresh", this);
            this.grid.stopEditing();
            var result = this.renderBody();
            this.mainBody.update(result[0]);
            this.lockedBody.update(result[1]);
            if (headersToo === true) {
                this.updateHeaders();
                this.updateHeaderSortState();
            }
            this.processRows(0, true);
            this.layout();
            this.applyEmptyText();
            this.fireEvent("refresh", this);
        },

        handleLockChange: function () {
            this.refresh(true);
        },

        onDenyColumnHide: function () {
        },

        onColumnLock: function () {
            this.handleLockChange.apply(this, arguments);
        },

        addRowClass: function (row, cls) {
            var r = this.getRow(row);
            if (r) {
                this.fly(r).addClass(cls);
                r = this.getLockedRow(row);
                this.fly(r).addClass(cls);
            }
        },

        removeRowClass: function (row, cls) {
            var r = this.getRow(row);
            if (r) {
                this.fly(r).removeClass(cls);
                r = this.getLockedRow(row);
                this.fly(r).removeClass(cls);
            }
        },

        handleHdMenuClick: function (item) {
            var index = this.hdCtxIndex;
            var cm = this.cm, ds = this.ds, lc;
            switch (item.id) {
                case "asc":
                    ds.sort(cm.getDataIndex(index), "ASC");
                    break;
                case "desc":
                    ds.sort(cm.getDataIndex(index), "DESC");
                    break;
                case "lock":
                    lc = cm.getLockedCount();
                    if (cm.getColumnCount(true) <= lc + 1) {
                        this.onDenyColumnLock();
                        return;
                    }
                    if (lc != index) {
                        cm.setLocked(index, true, true);
                        cm.moveColumn(index, lc);
                        this.grid.fireEvent("columnmove", index, lc);
                    } else {
                        cm.setLocked(index, true);
                    }
                    break;
                case "unlock":
                    lc = cm.getLockedCount();
                    if ((lc - 1) != index) {
                        cm.setLocked(index, false, true);
                        cm.moveColumn(index, lc - 1);
                        this.grid.fireEvent("columnmove", index, lc - 1);
                    } else {
                        cm.setLocked(index, false);
                    }
                    break;
                default:
                    index = cm.getIndexById(item.id.substr(4));
                    if (index != -1) {
                        if (item.checked && cm.getColumnsBy(this.isHideableColumn, this).length <= 1) {
                            this.onDenyColumnHide();
                            return false;
                        }
                        cm.setHidden(index, item.checked);
                    }
            }
            return true;
        },

        handleHdDown: function (e, t) {
            if (Ext.fly(t).hasClass('x-grid3-hd-btn')) {
                e.stopEvent();
                var hd = this.findHeaderCell(t);
                Ext.fly(hd).addClass('x-grid3-hd-menu-open');
                var index = this.getCellIndex(hd);
                this.hdCtxIndex = index;
                var ms = this.hmenu.items, cm = this.cm;
                ms.get("asc").setDisabled(!cm.isSortable(index));
                ms.get("desc").setDisabled(!cm.isSortable(index));
                if (this.grid.enableColLock !== false) {
                    ms.get("lock").setDisabled(cm.isLocked(index));
                    ms.get("unlock").setDisabled(!cm.isLocked(index));
                }
                this.hmenu.on("hide", function () {
                    Ext.fly(hd).removeClass('x-grid3-hd-menu-open');
                }, this, {single: true});
                this.hmenu.show(t, "tl-bl?");
            }
        },

        renderUI: function () {
            var header = this.renderHeaders();
            var body = this.templates.body.apply({rows: ''});
            var html = this.templates.master.apply({
                body: body,
                header: header[0],
                lockedBody: body,
                lockedHeader: header[1]
            });
            var g = this.grid;
            g.getGridEl().dom.innerHTML = html;
            this.initElements();
            Ext.fly(this.innerHd).on("click", this.handleHdDown, this);
            Ext.fly(this.lockedInnerHd).on("click", this.handleHdDown, this);
            this.mainHd.on("mouseover", this.handleHdOver, this);
            this.mainHd.on("mouseout", this.handleHdOut, this);
            this.mainHd.on("mousemove", this.handleHdMove, this);
            this.lockedHd.on("mouseover", this.handleHdOver, this);
            this.lockedHd.on("mouseout", this.handleHdOut, this);
            this.lockedHd.on("mousemove", this.handleHdMove, this);
            this.mainWrap.dom.style.left = this.cm.getTotalLockedWidth() + 'px';
            this.scroller.on('scroll', this.syncScroll, this);
            if (g.enableColumnResize !== false) {
                this.splitone = new Ext.grid.GridView.SplitDragZone(g, this.lockedHd.dom);
                this.splitone.setOuterHandleElId(Ext.id(this.lockedHd.dom));
                this.splitone.setOuterHandleElId(Ext.id(this.mainHd.dom));
            }
            if (g.enableColumnMove) {
                this.columnDrag = new Ext.grid.GridView.ColumnDragZone(g, this.innerHd);
                this.columnDrop = new Ext.grid.HeaderDropZone(g, this.mainHd.dom);
            }
            if (g.enableHdMenu !== false) {
                if (g.enableColumnHide !== false) {
                    this.colMenu = new Ext.menu.Menu({id: g.id + "-hcols-menu"});
                    this.colMenu.on("beforeshow", this.beforeColMenuShow, this);
                    this.colMenu.on("itemclick", this.handleHdMenuClick, this);
                }
                this.hmenu = new Ext.menu.Menu({id: g.id + "-hctx"});
                this.hmenu.add(
                        {id: "asc", text: this.sortAscText, cls: "xg-hmenu-sort-asc"},
                        {id: "desc", text: this.sortDescText, cls: "xg-hmenu-sort-desc"}
                );
                if (this.grid.enableColLock !== false) {
                    this.hmenu.add('-',
                            {id: "lock", text: this.lockText, cls: "xg-hmenu-lock"},
                            {id: "unlock", text: this.unlockText, cls: "xg-hmenu-unlock"}
                    );
                }
                if (g.enableColumnHide !== false) {
                    this.hmenu.add('-',
                            {id: "columns", text: this.columnsText, menu: this.colMenu, iconCls: 'x-cols-icon'}
                    );
                }
                this.hmenu.on("itemclick", this.handleHdMenuClick, this);
            }
            if (g.enableDragDrop || g.enableDrag) {
                var dd = new Ext.grid.GridDragZone(g, {
                    ddGroup: g.ddGroup || 'GridDD'
                });
            }
            this.updateHeaderSortState();
        },

        afterRender: function () {
            var bd = this.renderRows();
            if (bd === '') {
                bd = ['', ''];
            }
            this.mainBody.dom.innerHTML = bd[0];
            this.lockedBody.dom.innerHTML = bd[1];
            this.processRows(0, true);
            if (this.deferEmptyText !== true) {
                this.applyEmptyText();
            }


            // DAVID ADDED TO SCROLL
            this.ds.each(function (record) {
                this.ds.renderRecordFromDisplayType(record, record.data['displayType']);
            }, this);

            this.scrollRight();
            this.refresh();
            // DAVID ADDED TO SCROLL
        },

        updateAllColumnWidths: function () {
            var tw = this.cm.getTotalWidth();
            var lw = this.cm.getTotalLockedWidth();
            var clen = this.cm.getColumnCount();
            var lclen = this.cm.getLockedCount();
            var ws = [];
            var i;
            for (i = 0; i < clen; i++) {
                ws[i] = this.getColumnWidth(i);
            }
            this.innerHd.firstChild.firstChild.style.width = (tw - lw) + 'px';
            this.mainWrap.dom.style.left = lw + 'px';
            this.lockedInnerHd.firstChild.firstChild.style.width = lw + 'px';
            for (i = 0; i < clen; i++) {
                var hd = this.getHeaderCell(i);
                hd.style.width = ws[i] + 'px';
            }
            var ns = this.getRows();
            var lns = this.getLockedRows();
            for (i = 0, len = ns.length; i < len; i++) {
                ns[i].style.width = (tw - lw) + 'px';
                ns[i].firstChild.style.width = (tw - lw) + 'px';
                lns[i].style.width = lw + 'px';
                lns[i].firstChild.style.width = lw + 'px';
                var j, row;
                for (j = 0; j < lclen; j++) {
                    row = lns[i].firstChild.rows[0];
                    row.childNodes[j].style.width = ws[j] + 'px';
                }
                for (j = lclen; j < clen; j++) {
                    row = ns[i].firstChild.rows[0];
                    row.childNodes[j].style.width = ws[j] + 'px';
                }
            }
            this.onAllColumnWidthsUpdated(ws, tw);
        },

        updateColumnWidth: function (col, width) {
            var w = this.getColumnWidth(col);
            var tw = this.cm.getTotalWidth();
            var lclen = this.cm.getLockedCount();
            var lw = this.cm.getTotalLockedWidth();
            var hd = this.getHeaderCell(col);
            hd.style.width = w;
            var ns, gw;
            var ncol = col;
            if (col < lclen) {
                ns = this.getLockedRows();
                gw = lw;
                this.lockedInnerHd.firstChild.firstChild.style.width = gw + 'px';
                this.mainWrap.dom.style.left = this.cm.getTotalLockedWidth() + 'px';
                this.mainWrap.dom.style.display = 'none';
                this.mainWrap.dom.style.display = '';
            } else {
                ns = this.getRows();
                gw = tw - lw;
                ncol -= lclen;
                this.innerHd.firstChild.firstChild.style.width = gw + 'px';
            }
            for (var i = 0, len = ns.length; i < len; i++) {
                ns[i].style.width = gw + 'px';
                ns[i].firstChild.style.width = gw + 'px';
                ns[i].firstChild.rows[0].childNodes[ncol].style.width = w;
            }
            this.onColumnWidthUpdated(col, w, tw);
            this.layout();
        },

        getEditorParent: function (ed) {
            return this.el.dom;
        },

        refreshRow: function (record) {
            Ext.grid.LockingGridView.superclass.refreshRow.call(this, record);
            var index = this.ds.indexOf(record);
            this.getLockedRow(index).rowIndex = index;
        },

        scrollRight: function () {
            this.scroller.scrollTo('left', this.scroller.dom.scrollWidth - this.scroller.dom.clientWidth);
            this.syncScroll();
        }
    });

    Ext.grid.LockingColumnModel = Ext.extend(Ext.grid.ColumnModel, {
        getTotalLockedWidth: function () {
            var totalWidth = 0;
            for (var i = 0; i < this.config.length; i++) {
                if (this.isLocked(i) && !this.isHidden(i)) {
                    totalWidth += this.getColumnWidth(i);
                }
            }
            return totalWidth;
        }
    });
    iSimulate.GenericWindow = Ext.extend(Ext.Window, {
        width: 800,
        height: 500,
        shadow: true,
        minWidth: 300,
        minHeight: 300,
        resizable: true,
        resizeHandles: 'se',
        proxyDrag: true,
        initComponent: function () {
            Ext.apply(this, {
                buttons: [{
                        text: 'Close',
                        scope: this,
                        handler: function () {
                            this.hide();
                        }
                    }],
                listeners: [{
                        scope: this,
                        hide: function (win) {
                            iSimulate.enableSync();
                        }
                    }]
            });

            iSimulate.GenericWindow.superclass.initComponent.apply(this, arguments);
        }
    });

    Ext.reg('isimulate-genericwindow', iSimulate.GenericWindow);
    iSimulate.Sync = function () {
        Ext.apply(this, {
            task: {
                interval: 5000,
                scope: this,
                run: function () {
                    Ext.Ajax.request({
                        url: '<?php echo url_for("@checkSync?groupName=" . $group->getGroupName() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>',
                        scope: this,
                        params: {
                            transactionID: iSimulate.info.transactionID
                        },
                        success: function (response, options) {
                            if (response.responseText.length > 0) {
                                this.stop();
                                var newTransactionInfo = Ext.decode(response.responseText);
                                if (newTransactionInfo.isAggregate) {
                                    Ext.Msg.alert("Sync Notice", "A country in this aggregate has been updated.  Data will now refresh.", function () {
                                        this.fireEvent('acceptsSync', this, newTransactionInfo);
                                    }, this);
                                } else if (iSimulate.info.transactionID != newTransactionInfo.transactionID) {
                                    var msg;
                                    if (newTransactionInfo.isUpdate) {
                                        msg = newTransactionInfo.userName + ' has updated this country.';
                                    } else if (iSimulate.info.transactionID < newTransactionInfo.transactionID) {
                                        msg = newTransactionInfo.userName + ' has solved the model for this country.';
                                    } else {
                                        msg = 'Your group has reverted to a previous solution, originally by ' + newTransactionInfo.userName + '.';
                                    }
                                    msg += '<br><br>Comment: \"' + newTransactionInfo.comment + '\"<br><br>Would you like to refresh with the new data?';
                                    if (newTransactionInfo.isUpdate) {
                                        msg += "<br><br>Note: If you press no, the solve, update, and revert buttons will be disabled until you refresh with the new dataset.";
                                    }

                                    Ext.Msg.confirm('Sync Notice', msg, function (btn) {
                                        if (btn == 'yes') {
                                            this.fireEvent('acceptsSync', this, newTransactionInfo);
                                        } else {
                                            this.fireEvent('declinesSync', this, newTransactionInfo);
                                        }
                                    }, this);
                                }
                            }
                        }
                    });
                }
            }
        });

        iSimulate.Sync.superclass.constructor.call(this);

        this.addEvents('acceptsSync', 'declinesSync');
    };

    Ext.extend(iSimulate.Sync, Ext.util.Observable, {
        start: function () {
            Ext.TaskMgr.start(this.task);
        },
        stop: function () {
            Ext.TaskMgr.stop(this.task);
        }
    });

    iSimulate.SyncMgr = new iSimulate.Sync(); // singleton SyncMgr
    iSimulate.NewCountryCombo = Ext.extend(Ext.form.ComboBox, {
        emptyText: 'Load new country...',
        selectOnFocus: true,
        forceSelection: true,
        triggerAction: 'all',
        typeAhead: true,
        width: 300,
        initComponent: function () {
            var valueField = "code";
            var displayField = "name";

            Ext.apply(this, {
                tpl: '<tpl for=".">' +
                        '<div class="x-combo-list-item">' +
                        '<img align="absmiddle" style="margin-right: 7px;" src="/images/flags/{' + valueField + '}.png"/>' +
                        '{' + displayField + '}' +
                        '</div>' +
                        '</tpl>',
                valueField: valueField,
                displayField: displayField,
                mode: 'remote',
                minChars: 3,
                store: new Ext.data.JsonStore({
                    url: '<?php echo url_for("@entityListForApplication?applicationName=" . $app->getApplicationName()) ?>',
                    root: 'entities',
                    fields: [{name: 'code'}, {name: 'name'}]
                })
            });

            iSimulate.NewCountryCombo.superclass.initComponent.apply(this, arguments);

            this.on({
                scope: this,
                select: function (combo, record, index) {
                    Ext.MessageBox.confirm('Confirm', 'Are you sure you want to load ' + record.get(combo.displayField) + '?', function (btn, text) {
                        if (btn == 'yes') {
                            var url = '<?php echo url_for("@displayEntity?groupName=" . $group->getGroupname() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>';
                            location.href = url.substr(0, url.length - 3) + combo.getValue();
                        } else {
                            combo.clearValue();
                        }
                    });
                }
            });
        }
    });

    Ext.reg('isimulate-newcountrycombo', iSimulate.NewCountryCombo);
    iSimulate.QuickHelpWindow = Ext.extend(iSimulate.GenericWindow, {
        initComponent: function () {
            var content =
                    "<div class='headerHelp'>" +
                    "<div id='repeatHelp'>" +
                    "<img src='/images/header-help.gif' alt='Header-help' />" +
                    "</div>" +
                    "</div>" +
                    "<div style='padding:10px'>" +
                    "<div style='font-size:14px;font-weight:bold;margin-bottom:15px'>How do I execute a simulation?</div>" +
                    "<b>In brief:</b><br>" +
                    "<img class='helpBullet' style='margin-left:7px;' src='/images/bullet_red.gif' alt='Bullet_red' />" +
                    "Click on grid cells to change data (the <?php echo $app ?> model only picks up changes between <?php echo $app->getStartusersolveperiod() ?> and <?php echo $app->getEndusersolveperiod() ?>).<br>" +
                    "<img class='helpBullet' style='margin-left:7px;' src='/images/bullet_red.gif' alt='Bullet_red' />" +
                    "Click the <i>Solve Model</i> button to execute a country-level simulation.<br>" +
                    "<img class='helpBullet' style='margin-left:7px;' src='/images/bullet_red.gif' alt='Bullet_red' />" +
                    "Click the <i>Update Model</i> button to execute a global simulation when you are happy with the latest results." +
                    "<br><br>" +
                    "<b>In detail:</b><br>" +
                    "There are two main operations you can perform while working with the <?php echo $app ?> model:<br><br>" +
                    "<img class='helpBullet' style='margin-left:7px;' src='/images/bullet_red.gif' alt='Bullet_red' />" +
                    "<b>Solve Model</b> - perform a country-level simulation<br>" +
                    "     &nbsp;&nbsp;&nbsp; Pressing the <i>Solve Model</i> button will execute a country-level simulation.  The results will only effect the current country.<br><br>" +
                    "<img class='helpBullet' style='margin-left:7px;' src='/images/bullet_red.gif' alt='Bullet_red' />" +
                    "<b>Update Model</b> - perform a global simulation (also called an aggregation)<br>" +
                    "    &nbsp;&nbsp;&nbsp; Pressing the <i>Update Model</i> button will push the current simulation results to the global model.  Certain global indicators (via " +
                    "    trade linkage variables) may be modified across relevant countries.	        " +
                    "<br><br>" +
                    "<b>Suggested Work Cycle:</b><br>" +
                    "We recommend working at the country-level (by using the <i>Solve Model</i> button) until you are happy with your simulation results. " +
                    "Once you are ready to push your results to the global model, run a global simulation (by pressing the <i>Update Model</i> button) to " +
                    "update global indicators with the most recent results. <b>We encourage you to perform global simulations " +
                    "frequently, since it ensures consistency across countries.</b><br><br>"
            "</div>";

            Ext.apply(this, {
                layout: 'fit',
                title: "Quick help",
                items: [{
                        html: content,
                        autoScroll: true
                    }]
            });

            iSimulate.QuickHelpWindow.superclass.initComponent.apply(this, arguments);
        }
    });

    Ext.reg('isimulate-quickhelpwindow', iSimulate.QuickHelpWindow);
    iSimulate.HelpWindow = Ext.extend(iSimulate.GenericWindow, {
        initComponent: function () {
            var tpl = new Ext.Template(
                    "<div class='headerHelp'>" +
                    "<div id='repeatHelp'>" +
                    "<img src='/images/header-help.gif' />" +
                    "</div>" +
                    "</div>" +
                    "<br>" +
                    "<div style='padding:10px'><b>{header}</b><br><br>{content}</div>"
                    );
            tpl.compile();

            var content = {
                home:
                        "Please select a topic on the left to view help information.<br><br><u>Quick Help</u>:<br><table style='font-size: 11px; line-height:150%'>" +
                        "	<tr>" +
                        "		<td valign='top'><img src='/images/bullet_red.gif' class='helpBullet' /></td>" +
                        "		<td>To run a simulation, click the 'Solve Model' button (The <?php echo $app ?> application solves from <?php echo $app->getStartusersolveperiod() ?> to <?php echo $app->getEndusersolveperiod() ?>).</td>" +
                        "	</tr>" +
                        "	<tr>" +
                        "		<td valign='top'><img src='/images/bullet_red.gif' class='helpBullet' /></td>" +
                        "		<td>To perform a global aggregation, click the 'Update Model' button.</td>" +
                        "	</tr>" +
                        "	<tr>" +
                        "		<td valign='top'><img src='/images/bullet_red.gif' class='helpBullet' /></td>" +
                        "		<td>To view and revert to previous simulation results, click the 'Country Log' button.</td>" +
                        "	</tr>" +
                        "</table>",
                countryCombo:
                        "The Country List dropdown on the top left corner of the grid is used to load country data to execute simulations.<br><br>" +
                        "Predefined country groups (called aggregates) can also be loaded via the country list drop down.  No simulations can be executed on an aggregate.",
                solve:
                        "The Solve Model button is used to execute a simulation.<br><br>After clicking on the solve button, a user is prompted to enter a comment which identifies the simulation.<br><br>" +
                        "Results of all simulations are available in real-time to group members.",
                update:
                        "The Update Model button is used to perform a global aggregation of the latest simulation result.<br><br>After clicking on the update button, a user is prompted to enter a comment which identifies the aggregation.<br><br>" +
                        "An update recalculates predefined country groups (called aggregates) and is recorded in the activity feed of the group.",
                revert:
                        "The Revert button displays a list of all the simulations performed on the country with corresponding user comments.<br><br>" +
                        "The country log can be used to revert to a previous simulation.<br><br>A user can only revert to simulations following the most recent update.",
                de:
                        "The D and E buttons are application specific buttons. They are used to enforce application developer defined configurations for the variable modes.<br><br>" +
                        "To operate the application in the suggested mode of operation, click the D button (\"default\" mode).<br><br>To operate the model in an exogenized mode of operation, click the E button (\"exogenous\" mode).",
                modelType:
                        "The BOP Model dropdown is used to select a configuration for operating the BOP model for a simulation. For example, in the World Bank's global macro model, there are two BOP model configurations:<br><br>" +
                        "1. The Complete BOP model where the Current Account Balance is calculated as the sum of its components.<br>" +
                        "2. The Simple BOP model where the Current Account Balance can be specified as a % of GDP and the Current Account Balance components are calculated using model-determined ratios.",
                dataset:
                        "The Current Data dropdown is used to display:<br><br>" +
                        "1. Data from the current simulation <br>" +
                        "2. Data from the previous simulation <br>" +
                        "3. The difference between the current and previous simulation.",
                colors:
                        "The Color On/Off dropdown is used to enable or disable the display of comparison colors on the grid.<br><br>" +
                        "Following a simulation, the grid displays colors for each cell corresponding to the difference in data across the two " +
                        "simulations.<br><br>" +
                        "&middot; A red cell color indicates a large change (>= 1%)<br>" +
                        "&middot; A yellow cell color indicates a moderate change (between 0.5% and 1%)<br>" +
                        "&middot; A green cell color indicates a small change (between 0.1% and 0.5%)<br>",
                sync:
                        "The Sync dropdown is used to enable or disable the \"sync\" functionality in iSimulate.<br><br>" +
                        "When sync is enabled, a user will receive notifications of simulations performed by other group members.<br><br>" +
                        "When sync is disabled, a user will no longer receive notifications.  However, other users will still be notified of the user's activity.",
                modeCol:
                        "The Mode column on the grid is used to define the mode of variables when executing simulations.<br><br>" +
                        "Variables can be operated in exogenous, endogenous or identity modes.<br><br>" +
                        "Not all modes are available for all variables. Clicking on the mode cell for a variable will provide a dropdown for selection of the mode.",
                dispCol:
                        "The Disp column on the grid is used to define the display type of variables. Variables can be displayed in levels (l)," +
                        "year-over-year growth rates (g), contribution to growth (contr) and percent of GDP (%gdp).<br><br>" +
                        "Add-factors are displayed as a contribution to growth of the corresponding indicator.<br><br>" +
                        "Not all display types are available for all variables. Clicking on the appropriate cell for a variable will provide a dropdown for selection of the display type."
            };

            Ext.apply(this, {
                layout: 'border',
                title: "Help Console",
                tpl: tpl,
                defaults: {
                    autoScroll: true
                },
                items: [{
                        region: 'west',
                        width: 250,
                        xtype: 'arraytree',
                        rootConfig: {
                            text: 'Help Topics',
                            visible: true,
                            contentId: 'home'
                        },
                        title: 'Help topics',
                        children: [{
                                text: 'Running simulations',
                                children: [{
                                        text: 'Solving the model',
                                        leaf: true,
                                        contentId: 'solve'
                                    }, {
                                        text: 'Updating the model',
                                        leaf: true,
                                        contentId: 'update'
                                    }, {
                                        text: 'Reverting',
                                        leaf: true,
                                        contentId: 'revert'
                                    }]
                            }, {
                                text: 'Toolbar options',
                                children: [{
                                        text: 'D and E buttons',
                                        leaf: true,
                                        contentId: 'de'
                                    }, {
                                        text: 'BOP model dropdown',
                                        leaf: true,
                                        contentId: 'modelType'
                                    }, {
                                        text: 'Dataset dropdown',
                                        leaf: true,
                                        contentId: 'dataset'
                                    }, {
                                        text: 'Colors dropdown',
                                        leaf: true,
                                        contentId: 'colors'
                                    }, {
                                        text: 'Sync dropdown',
                                        leaf: true,
                                        contentId: 'sync'
                                    }]
                            }, {
                                text: 'Grid column actions',
                                children: [{
                                        text: 'Mode column',
                                        leaf: true,
                                        contentId: 'modeCol'
                                    }, {
                                        text: 'Disp column',
                                        leaf: true,
                                        contentId: 'dispCol'
                                    }]
                            }, {
                                text: 'Loading a new country',
                                leaf: true,
                                contentId: 'countryCombo'
                            }]
                    }, {
                        region: 'center',
                        html: tpl.apply({
                            header: "Welcome to the Simulation Help Console",
                            content: content.home
                        })
                    }]
            });

            iSimulate.HelpWindow.superclass.initComponent.apply(this, arguments);

            this.tpl.compile();
            this.tree = this.items.itemAt(0);
            this.helpPanel = this.items.itemAt(1);

            this.tree.on({
                scope: this,
                click: function (node, e) {
                    if (node.attributes.contentId) {
                        this.tpl.overwrite(this.helpPanel.body, {
                            header: node.attributes.text,
                            content: content[node.attributes.contentId]
                        });
                    }
                }
            });
        }
    });

    Ext.reg('isimulate-helpwindow', iSimulate.HelpWindow);
    iSimulate.RevertWindow = Ext.extend(iSimulate.GenericWindow, {
        confirmRevert: function (transactionId) {
            Ext.Msg.confirm('Confirm', 'Are you sure you want to revert to this solution?', function (btn, text) {
                if (btn == 'yes') {
                    this.fireEvent('revert', this, transactionId);
                }
            }, this);
        },
        initComponent: function () {
            var detailsText = '<i>Select a transaction to see more information...</i>';
            var transactionTpl = new Ext.XTemplate(
                    '<h2 style="color:#336699;font-size:15px; margin-bottom: 10px">Transaction Information</h2>',
                    '<tpl if="id == iSimulate.info.transactionID">',
                    '<p style="margin-bottom: 10px; color: #FF0000">This is your current solution.</p>',
                    '</tpl>',
                    '<tpl if="error">',
                    '<p style="margin-bottom: 10px; color: #FF0000">This solution has an error, so you cannot revert to it.</p>',
                    '</tpl>',
                    '<tpl if="!error && !canRevert">',
                    '<p style="margin-bottom: 10px; color: #FF0000">An update has occurred more recently than this solution, so you cannot revert to it.</p>',
                    '</tpl>',
                    '<p style="margin-bottom: 5px"><b>User</b>: {user}</p>',
                    '<p style="margin-bottom: 5px"><b>Date/Time</b>: {time}</p>',
                    '<tpl if="this.hasComment(comment)">',
                    '<p style="margin-bottom: 5px"><b>Comment</b>: {comment}</p>',
                    '</tpl>', {
                        hasComment: function (comment) {
                            return comment.trim() != "";
                        }
                    });
            transactionTpl.compile();

            Ext.apply(this, {
                layout: 'border',
                title: "Country log",
                defaults: {
                    autoScroll: true
                },
                items: [{
                        xtype: 'treepanel',
                        region: 'west',
                        title: 'Transaction log',
                        split: true,
                        width: 250,
                        animate: true,
                        rootVisible: false,
                        dataUrl: '<?php echo url_for("@displayEntityLog?groupName=" . $group->getGroupname() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>',
                        root: {
                            nodeType: 'async',
                            text: 'Transactions',
                            draggable: false,
                            id: 'root',
                            expanded: true
                        }
                    }, {
                        region: 'center',
                        bodyStyle: 'background: #EEEEEE none repeat scroll 0 0; color: #555555; padding: 10px',
                        title: 'Transaction details',
                        html: detailsText,
                        bbar: [{
                                text: 'Revert',
                                disabled: true,
                                scope: this,
                                handler: function (button, e) {
                                    this.confirmRevert(tree.getSelectionModel().getSelectedNode().id);
                                }
                            }, '-', {
                                text: 'Revert to last update',
                                scope: this,
                                handler: function (button, e) {
                                    this.confirmRevert();
                                }
                            }]
                    }]
            });

            iSimulate.RevertWindow.superclass.initComponent.apply(this, arguments);

            this.addEvents('revert');

            var tree = this.items.itemAt(0);
            var rightPanel = this.items.itemAt(1);

            tree.on({
                scope: this,
                click: function (node, e) {
                    var revertButton = rightPanel.getBottomToolbar().items.itemAt(0);

                    if (node.parentNode != tree.getRootNode()) {
                        transactionTpl.overwrite(rightPanel.body, node.attributes);
                    } else {
                        rightPanel.body.update(detailsText);
                    }

                    if (node.attributes.canRevert && !node.attributes.error) {
                        revertButton.enable();
                    } else {
                        revertButton.disable();
                    }
                }
            });
        }
    });

    Ext.reg('isimulate-revertwindow', iSimulate.RevertWindow);
    iSimulate.Toolbar.CommentButton = Ext.extend(Ext.Button, {
        lastComment: "",
        initComponent: function () {
            Ext.apply(this, {
                scope: this,
                handler: function () {
                    iSimulate.SyncMgr.stop();
                    Ext.Msg.show({
                        title: "Comment box",
                        msg: "Please add a comment:",
                        width: 300,
                        buttons: Ext.Msg.OKCANCEL,
                        multiline: true,
                        animEl: this.getEl(),
                        value: this.lastComment,
                        scope: this,
                        fn: function (btn, text) {
                            this.lastComment = text;
                            if (btn == 'ok') {
                                this.fireEvent('finished', this, text);
                            } else {
                                iSimulate.enableSync();
                            }
                        }
                    });
                }
            });

            iSimulate.Toolbar.CommentButton.superclass.initComponent.apply(this, arguments);

            this.addEvents('finished');
        }
    });

    Ext.reg('isimulate-toolbar-commentbutton', iSimulate.Toolbar.CommentButton);
    iSimulate.Toolbar.ModelTypeCombo = Ext.extend(Ext.form.ComboBox, {
        MODEL_SIMPLE: "Simple BOP Model",
        MODEL_COMPLETE: "Complete BOP Model",
        setCompleteModel: function () {
            this.setValue(this.MODEL_COMPLETE);
        },
        setSimpleModel: function () {
            this.setValue(this.MODEL_SIMPLE);
        },
        initComponent: function () {
            Ext.apply(this, {
                displayField: 'text',
                valueField: 'id',
                triggerAction: 'all',
                mode: 'local',
                editable: false,
                width: 150,
                store: new Ext.data.SimpleStore({
                    fields: ['id', 'text'],
                    data: [[0, this.MODEL_SIMPLE], [1, this.MODEL_COMPLETE]]
                })
            });

            iSimulate.Toolbar.ModelTypeCombo.superclass.initComponent.apply(this, arguments);
        }
    });

    Ext.reg('isimulate-toolbar-modeltypecombo', iSimulate.Toolbar.ModelTypeCombo);
    iSimulate.Toolbar.ColorCombo = Ext.extend(Ext.form.ComboBox, {
        COLORS_ON: "Colors On",
        COLORS_OFF: "Colors Off",
        setColorsOn: function () {
            this.setValue(this.COLORS_ON);
        },
        setColorsOff: function () {
            this.setValue(this.COLORS_OFF);
        },
        initComponent: function () {
            Ext.apply(this, {
                displayField: 'text',
                valueField: 'id',
                triggerAction: 'all',
                mode: 'local',
                editable: false,
                width: 85,
                value: this.COLORS_ON,
                store: new Ext.data.SimpleStore({
                    fields: ['id', 'text'],
                    data: [[0, this.COLORS_ON], [1, this.COLORS_OFF]]
                })
            });

            iSimulate.Toolbar.ColorCombo.superclass.initComponent.apply(this, arguments);
        }
    });

    Ext.reg('isimulate-toolbar-colorcombo', iSimulate.Toolbar.ColorCombo);
    iSimulate.Toolbar.SyncCombo = Ext.extend(Ext.form.ComboBox, {
        SYNC_ON: "Sync Enabled",
        SYNC_OFF: "Sync Disabled",
        initComponent: function () {
            Ext.apply(this, {
                displayField: 'text',
                valueField: 'id',
                triggerAction: 'all',
                mode: 'local',
                editable: false,
                width: 100,
                value: this.SYNC_ON,
                store: new Ext.data.SimpleStore({
                    fields: ['id', 'text'],
                    data: [[0, this.SYNC_ON], [1, this.SYNC_OFF]]
                })
            });

            iSimulate.Toolbar.SyncCombo.superclass.initComponent.apply(this, arguments);

            this.on('select', function (combo, record, index) {
                if (record.data.text == this.SYNC_ON) {
                    iSimulate.SyncMgr.start();
                } else {
                    iSimulate.SyncMgr.stop();
                }
            });
        }
    });

    Ext.reg('isimulate-toolbar-synccombo', iSimulate.Toolbar.SyncCombo);
    iSimulate.Toolbar.ComparisonCombo = Ext.extend(Ext.form.ComboBox, {
        CURRENT: "Data - Current",
        PREVIOUS: "Data - Previous",
        LASTUPDATE: "Data - Last update",
        DIFFERENCEPREVIOUS: "Difference - Previous",
        DIFFERENCEUPDATE: "Difference - Last update",
        switchTo: function (dataset) {
            this.setValue(dataset);
            var index = this.store.findBy(function (record, id) {
                return record.get('text', dataset);
            }, this);
            this.onSelect(this.store.getAt(index), index);
        },
        initComponent: function () {
            Ext.apply(this, {
                displayField: 'text',
                valueField: 'id',
                triggerAction: 'all',
                mode: 'local',
                editable: false,
                width: 150,
                value: this.CURRENT,
                store: new Ext.data.SimpleStore({
                    fields: ['id', 'text'],
                    data: [[0, this.CURRENT], [1, this.PREVIOUS], [2, this.LASTUPDATE], [3, this.DIFFERENCEPREVIOUS], [4, this.DIFFERENCEUPDATE]]
                })
            });

            iSimulate.Toolbar.ComparisonCombo.superclass.initComponent.apply(this, arguments);
        }
    });

    Ext.reg('isimulate-toolbar-comparisoncombo', iSimulate.Toolbar.ComparisonCombo);
    iSimulate.TabPanel = Ext.extend(Ext.TabPanel, {
        activeTab: 0,
        tabPosition: 'bottom',
        onRemoteSuccess: function (response, options) {
            var jsonResponse = Ext.decode(response.responseText);
            if (jsonResponse.success) {
                this.loadGrids(jsonResponse);
                this.enableDefaultButtons();
                Ext.fly(document.body).unmask();
            } else {
                this.serverExceptionHandler(jsonResponse.errorText);
            }
        },
        onRemoteFailure: function (response, options) {
            Ext.fly(document.body).unmask();
            Ext.Msg.show({
                title: "Error",
                msg: "Unable to contact iSimulate solution engine. Please try again.",
                icon: Ext.Msg.ERROR
            });
        },
        updateIsimulateInfo: function (data) {
            var keys = ["transactionID", "isAggregate", "permissions", "updateAllowed"];
            for (var key in data) {
                if (data.hasOwnProperty(key) && keys.exists(key)) {
                    iSimulate.info[key] = data[key];
                }
            }
        },
        getGridByTitle: function (title) {
            var index = this.items.findIndex('title', title);
            if (index == -1) {
                return false;
            }

            return this.items.itemAt(index);
        },
        getGridData: function () {
            var data = [];
            this.items.each(function (grid, index, length) {
                if (grid.isInputGrid()) {
                    data = data.concat(grid.getData());
                }
            });
            return data;
        },
        loadGrids: function (gridData) {
            this.updateIsimulateInfo(gridData);
            for (var thisGrid in gridData['grids']) {
                var grid = this.getGridByTitle(thisGrid);
                if (grid) {
                    grid.getStore().proxy = new Ext.data.MemoryProxy(gridData['grids'][thisGrid]);
                    grid.getStore().load();
                }
            }

            // switch to current data view				
            var comparisonCombo = Ext.getCmp('comparisonCombo');
            comparisonCombo.switchTo(comparisonCombo.CURRENT);

            iSimulate.enableSync();
            this.getActiveTab().getView().scrollRight();
        },
        solveHandler: function (btn, comment) {
            Ext.fly(document.body).mask("Solving model...", "x-mask-loading");
            Ext.Ajax.request({
                url: '<?php echo url_for("@solveGrid?groupName=" . $group->getGroupName() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>',
                params: {
                    gridData: Ext.encode(this.getGridData()),
                    comment: comment
                },
                scope: this,
                success: function (response, options) {
                    var jsonResponse = Ext.decode(response.responseText);
                    if (jsonResponse.success) {
                        btn.lastComment = "";
                    }
                    this.onRemoteSuccess(response, options);
                },
                failure: this.onRemoteFailure
            });
        },
        updateHandler: function (btn, comment) {
            Ext.fly(document.body).mask("Updating model...", "x-mask-loading");
            Ext.Ajax.request({
                url: '<?php echo url_for("@updateGrid?groupName=" . $group->getGroupName() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>',
                params: {
                    comment: comment
                },
                scope: this,
                timeout: 240000,
                success: function (response, options) {
                    var jsonResponse = Ext.decode(response.responseText);
                    if (jsonResponse.success) {
                        Ext.fly(document.body).unmask();
                        btn.lastComment = "";
                        Ext.Msg.alert('Status', 'Update successful!');
                        this.updateIsimulateInfo(jsonResponse);
                        btn.disable(); // disable update button					
                        iSimulate.enableSync();
                    } else {
                        this.serverExceptionHandler(jsonResponse.errorText);
                    }
                },
                failure: this.onRemoteFailure
            });
        },
        revertHandler: function (win, transactionId) {
            if (isNaN(parseInt(transactionId))) {
                transactionId = "lastUpdate";
            }

            this.revertWindow.hide();
            Ext.fly(document.body).mask('Reverting...', 'x-mask-loading');
            Ext.Ajax.request({
                url: '<?php echo url_for("@revertToPrevious?groupName=" . $group->getGroupname() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>',
                params: {
                    revertToID: transactionId
                },
                scope: this,
                success: this.onRemoteSuccess,
                failure: this.onRemoteFailure
            });
        },
        serverExceptionHandler: function (errorText) {
            iSimulate.SyncMgr.stop();
            Ext.fly(document.body).unmask();
            Ext.Msg.confirm('Error', 'There has been an error while running your simulation.  Would you like to view the error log?', function (btn) {
                if (btn == 'yes') {
                    if (!this.errorWindow) {
                        this.errorWindow = new iSimulate.GenericWindow({
                            layout: 'fit',
                            width: 800,
                            height: 500,
                            minWidth: 300,
                            minHeight: 300,
                            resizable: true,
                            resizeHandles: 'se',
                            title: "Error log",
                            closeAction: 'hide',
                            modal: true,
                            items: [{
                                    html: errorText,
                                    autoScroll: true
                                }],
                            buttons: [{
                                    text: 'Close',
                                    scope: this,
                                    handler: function () {
                                        this.errorWindow.hide();
                                    }
                                }]
                        });
                    } else {
                        this.errorWindow.items.get(0).body.update(errorText);
                    }

                    this.errorWindow.show();
                }
            }, this);
        },
        enableDefaultButtons: function () {
            if (iSimulate.info.isAggregate) {
                Ext.getCmp('solveButton').disable();
                Ext.getCmp('updateButton').disable();
                Ext.getCmp('revertButton').disable();
                Ext.getCmp('endogenousModesButton').disable();
                Ext.getCmp('exogenizeModesButton').disable();
                Ext.getCmp('modelTypeCombo').disable();
            } else {
                var userPermissions = iSimulate.info.permissions;
                if (userPermissions.exists("solve")) {
                    Ext.getCmp('solveButton').enable();
                } else {
                    Ext.getCmp('solveButton').disable();
                }

                if (userPermissions.exists("update") && iSimulate.info.updateAllowed) {
                    Ext.getCmp('updateButton').enable();
                } else {
                    Ext.getCmp('updateButton').disable();
                }

                if (userPermissions.exists("revert")) {
                    Ext.getCmp('revertButton').enable();
                } else {
                    Ext.getCmp('revertButton').disable();
                }
            }
        },
        switchModelType: function (type) {
            var modelTypeCombo = Ext.getCmp('modelTypeCombo');
            if (type == modelTypeCombo.MODEL_SIMPLE) {
                this.getGridByTitle('NIA').switchModes('exogenousMode');
                this.getGridByTitle('BOP').switchModes('simpleMode');
                modelTypeCombo.setSimpleModel();
            } else {
                this.getGridByTitle('BOP').switchModes('exogenousMode');
                modelTypeCombo.setCompleteModel();
            }
        },
        modelTypeComboHandler: function (combo, record, index) {
            this.switchModelType(record.get('text'));
        },
        colorComboHandler: function (combo, record, index) {
            if (record.data.text == combo.COLORS_ON) {
                this.enableColors();
            } else {
                this.disableColors();
            }
        },
        enableColors: function () {
            this.items.each(function (grid, index, length) {
                grid.enableColors();
            });

            Ext.getCmp('colorCombo').setColorsOn();
        },
        disableColors: function () {
            this.items.each(function (grid, index, length) {
                grid.disableColors();
            });

            Ext.getCmp('colorCombo').setColorsOff();
        },
        comparisonComboHandler: function (combo, record, index) {
            var comboSelection = record.get('text');

            this.items.each(function (grid, index, length) {
                var store = grid.getStore();
                store.each(function (record) {
                    if (typeof record.dataComparison != "undefined") {
                        var currentMode = record.get('mode');
                        var currentDisplayType = record.get('displayType');
                        delete record.data;

                        if (comboSelection == combo.COMPARISON) {
                            record.data = Ext.ux.clone(record.dataComparison);
                            record.set('mode', currentMode);
                            record.set('displayType', currentDisplayType);
                            record.onScreenDataBackupPointer = record.dataComparisonBackup;
                        } else if (comboSelection == combo.COMPARISON2) {
                            record.data = Ext.ux.clone(record.dataComparison2);
                            record.set('mode', currentMode);
                            record.set('displayType', currentDisplayType);
                            record.onScreenDataBackupPointer = record.dataComparison2Backup;
                        } else { // current or one of the difference data sets
                            record.data = Ext.ux.clone(record.dataBackup);
                            record.set('mode', currentMode);
                            record.set('displayType', currentDisplayType);
                            record.onScreenDataBackupPointer = record.dataBackup;
                            store.renderRecordFromDisplayType(record, record.get('displayType'));

                            if (comboSelection == combo.DIFFERENCE || comboSelection == combo.DIFFERENCE2) {
                                for (var thisDataPoint in record.data) {
                                    if (thisDataPoint.substring(0, 1) == 'y') {
                                        record.data[thisDataPoint] = record.data[thisDataPoint] - record.dataComparison[thisDataPoint];
                                    }
                                }
                            }
                        }
                    }
                }, store);
            });


            this.getActiveTab().getView().refresh(); // refreshes the current grid

            if (comboSelection == combo.CURRENT) {
                this.enableDefaultButtons();
                this.enableColors();
            } else {
                Ext.getCmp('solveButton').disable();
                Ext.getCmp('updateButton').disable();
                this.disableColors();
            }
        },
        quickHelpHandler: function () {
            iSimulate.SyncMgr.stop();
            if (!this.quickHelpWindow) {
                this.quickHelpWindow = new iSimulate.QuickHelpWindow();
            }

            this.quickHelpWindow.show();
        },
        helpHandler: function () {
            iSimulate.SyncMgr.stop();
            if (!this.helpWindow) {
                this.helpWindow = new iSimulate.HelpWindow();
            }

            this.helpWindow.show();
        },
        afterGridEdit: function (e) {
            var cellsToProcess = [{
                    grid: e.grid,
                    mnemonic: e.grid.getStore().getAt(e.row).get('mnemonic'),
                    field: e.field,
                    value: e.value,
                    originalValue: e.originalValue
                }];

            var processedEffects = [];
            var gridsToRefresh = [];
            while (cellsToProcess.length) {
                var thisCell = cellsToProcess.pop();
                var sourceGrid = thisCell.grid;
                var thisRecord = sourceGrid.getStore().getRecordWithKeyValuePair('mnemonic', thisCell.mnemonic);
                if (thisCell.field == "displayType" && thisCell.originalValue != thisCell.value) {
                    sourceGrid.getStore().renderRecordFromDisplayType(thisRecord, thisRecord.get('displayType'));
                    sourceGrid.fireEvent('displayTypeChange', e.grid, thisRecord, thisCell.originalValue, thisCell.value);
                } else if (thisCell.field == "mode" && thisRecord.get('mnemonic') == "BNCABFUNDCD%") {
                    sourceGrid.fireEvent('modelTypeChange', sourceGrid, thisCell.value == "i" ? "complete" : "simple")
                }

                for (var thisTrigger in thisRecord.triggers) {
                    // check to see if this trigger should be activated
                    if (thisRecord.triggers.hasOwnProperty(thisTrigger) && thisRecord.triggers[thisTrigger].col == thisCell.field && thisRecord.triggers[thisTrigger].val == thisCell.value) {
                        for (var thisEffect in thisRecord.triggers[thisTrigger].effects) {
                            // get the unique ID for this trigger-effect
                            var thisEffectID = sourceGrid.title + "_" + thisRecord.get('mnemonic') + "_t" + thisTrigger + "_e" + thisEffect;
                            if (thisRecord.triggers[thisTrigger].effects.hasOwnProperty(thisEffect) && !processedEffects.exists(thisEffectID)) {
                                var effectGrid;
                                if (thisRecord.triggers[thisTrigger].effects[thisEffect].sheet) {
                                    effectGrid = this.items.find(function (grid) { // find grid with the name of the effect grid
                                        return grid.title == thisRecord.triggers[thisTrigger].effects[thisEffect].sheet;
                                    }, this);
                                } else {
                                    effectGrid = sourceGrid;
                                }
                                var effectRecord = effectGrid.getStore().getRecordWithKeyValuePair('mnemonic', thisRecord.triggers[thisTrigger].effects[thisEffect].mnemonic);
                                cellsToProcess.push({// add the cell this trigger-effect stack
                                    grid: effectGrid,
                                    mnemonic: thisRecord.triggers[thisTrigger].effects[thisEffect].mnemonic,
                                    field: thisRecord.triggers[thisTrigger].effects[thisEffect].col,
                                    value: thisRecord.triggers[thisTrigger].effects[thisEffect].val,
                                    originalValue: effectRecord.data[thisRecord.triggers[thisTrigger].effects[thisEffect].col]
                                });
                                // actually run the trigger-effect												
                                effectRecord.data[thisRecord.triggers[thisTrigger].effects[thisEffect].col] = thisRecord.triggers[thisTrigger].effects[thisEffect].val;
                                // add the trigger-effect unique ID to the array of processed trigger-effects
                                processedEffects.push(thisEffectID);
                                // add this grid as a grid to refresh
                                if (gridsToRefresh.exists(effectGrid) == false) {
                                    gridsToRefresh.push(effectGrid);
                                }
                            }
                        }
                    }
                }
            }

            // refresh all grids in need of refreshing
            Ext.each(gridsToRefresh, function (grid) {
                if (grid.rendered) {
                    grid.getView().refresh();
                }
            });
        },
        initComponent: function () {
            Ext.apply(this, {
                layoutOnTabChange: true,
                items: [{
                        title: "Loading",
                        html: "&nbsp;Loading..."
                    }],
                tbar: [{
                        xtype: 'isimulate-toolbar-commentbutton',
                        id: 'solveButton',
                        icon: '/images/control_play_blue.gif',
                        cls: 'x-btn-text-icon',
                        text: 'Solve',
                        listeners: {
                            scope: this,
                            finished: this.solveHandler
                        }
                    }, '-', {
                        xtype: 'isimulate-toolbar-commentbutton',
                        id: 'updateButton',
                        icon: '/images/resultset_up.gif',
                        cls: 'x-btn-text-icon',
                        text: 'Update',
                        listeners: {
                            scope: this,
                            finished: this.updateHandler
                        }
                    }, '-', {
                        xtype: 'button',
                        id: 'revertButton',
                        icon: '/images/undo2.gif',
                        cls: 'x-btn-text-icon',
                        text: 'Revert',
                        scope: this,
                        handler: function () {
                            iSimulate.SyncMgr.stop();
                            if (!this.revertWindow) {
                                this.revertWindow = new iSimulate.RevertWindow({
                                    listeners: {
                                        scope: this,
                                        revert: this.revertHandler
                                    }
                                });
                            } else {
                                this.revertWindow.items.itemAt(0).getRootNode().reload();
                            }

                            this.revertWindow.show();
                        }
                    }, '-', {
                        xtype: 'button',
                        id: 'endogenousModesButton',
                        text: 'D',
                        tooltip: 'Endogenous Mode',
                        scope: this,
                        handler: function (item, pressed) {
                            this.getActiveTab().switchModes("endogenousMode");
                        }
                    }, '-', {
                        xtype: 'button',
                        id: 'exogenizeModesButton',
                        text: 'E',
                        tooltip: 'Exogenous Mode',
                        scope: this,
                        handler: function (item, pressed) {
                            this.getActiveTab().switchModes("exogenousMode");
                        }
                    }, '-', {
                        xtype: 'isimulate-toolbar-modeltypecombo',
                        id: 'modelTypeCombo',
                        listeners: {
                            scope: this,
                            select: this.modelTypeComboHandler
                        }
                    }, '->', {
                        xtype: 'isimulate-toolbar-comparisoncombo',
                        id: 'comparisonCombo',
                        listeners: {
                            scope: this,
                            select: this.comparisonComboHandler
                        }
                    }, '-', {
                        xtype: 'isimulate-toolbar-colorcombo',
                        id: 'colorCombo',
                        listeners: {
                            scope: this,
                            select: this.colorComboHandler
                        }
                    }, '-', {
                        xtype: 'isimulate-toolbar-synccombo',
                        id: 'syncCombo'
                    }, '-', {
                        xtype: 'splitbutton',
                        icon: '/images/help.gif',
                        cls: 'x-btn-text-icon',
                        text: 'Help',
                        scope: this,
                        handler: this.quickHelpHandler,
                        menu: {
                            items: [{
                                    text: 'Quick Help',
                                    icon: '/images/wand.gif',
                                    scope: this,
                                    handler: this.quickHelpHandler
                                }, {
                                    text: 'Help Console',
                                    icon: '/images/information.gif',
                                    scope: this,
                                    handler: this.helpHandler
                                }]
                        }
                    }]
            });

            iSimulate.TabPanel.superclass.initComponent.apply(this, arguments);

            this.addEvents('ready');

            var loadingTab = this.items.get(0);

            this.on({
                scope: this,
                render: function (panel) {
                    Ext.Ajax.request({
                        url: '<?php echo url_for("@loadGrid?groupName=" . $group->getGroupname() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>',
                        scope: this,
                        success: function (response, options) {
                            try {
                                var responseData = Ext.decode(response.responseText);
                                this.updateIsimulateInfo(responseData);

                                for (var thisGrid in responseData["grids"]) {
                                    if (responseData["grids"][thisGrid]) {
                                        var grid = this.add({
                                            title: thisGrid,
                                            autoScroll: true,
                                            xtype: 'isimulate-grid',
                                            config: responseData["grids"][thisGrid],
                                            listeners: {
                                                scope: this,
                                                render: function (grid) {
                                                    if (this.items.get(0) == grid) { // is the first grid
                                                        this.fireEvent('ready');
                                                    }
                                                },
                                                modelTypeChange: function (grid, modelType) {
                                                    var modelTypeCombo = Ext.getCmp('modelTypeCombo');
                                                    if (modelType == "complete") {
                                                        modelTypeCombo.setCompleteModel();
                                                    } else {
                                                        modelTypeCombo.setSimpleModel();
                                                    }
                                                },
                                                displayTypeChange: function (grid, record, oldDisplayType, newDisplayType) {
                                                    var comparisonCombo = Ext.getCmp('comparisonCombo');
                                                    if (comparisonCombo.lastSelectionText == comparisonCombo.DIFFERENCE) {
                                                        for (var thisCell in record.data) {
                                                            if (thisCell.substring(0, 1) == 'y') {
                                                                record.data[thisCell] = record.data[thisCell] - record.dataComparison[thisCell];
                                                            }
                                                        }
                                                    }
                                                },
                                                afteredit: this.afterGridEdit
                                            }
                                        });

                                        this.remove(loadingTab);
                                    }
                                }

                                this.enableDefaultButtons();
                            } catch (e) { // some problem on the backend.  maybe a failed solve?
                                Ext.fly(document.body).unmask();
                                Ext.Msg.show({
                                    title: "Error",
                                    msg: "Unable to load data.  Please refresh this page and try again.",
                                    icon: Ext.Msg.ERROR,
                                    closable: false
                                });
                            }
                        },
                        failure: function (response, options) {
                            Ext.fly(document.body).unmask();
                            Ext.Msg.show({
                                title: "Error",
                                msg: "Unable to load data. Please refresh this page and try again.",
                                icon: Ext.Msg.ERROR,
                                closable: false
                            });
                        }
                    });
                },
                tabchange: function (tabpanel, grid) {
                    if (typeof grid.isInputGrid == "function") {
                        var comparisonCombo = Ext.getCmp('comparisonCombo');
                        if (grid.isInputGrid()) {
                            comparisonCombo.enable();
                        } else {
                            comparisonCombo.switchTo(comparisonCombo.CURRENT);
                            comparisonCombo.disable();
                        }
                    }
                }
            });

            iSimulate.SyncMgr.on({
                scope: this,
                acceptsSync: function (syncMgr, transactionInfo) {
                    Ext.fly(document.body).mask("Refreshing...", "x-mask-loading");
                    Ext.Ajax.request({
                        url: '<?php echo url_for("@loadGrid?groupName=" . $group->getGroupname() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>',
                        scope: this,
                        success: this.onRemoteSuccess,
                        failure: this.onRemoteFailure
                    });
                },
                declinesSync: function (syncMgr, transactionInfo) {
                    if (transactionInfo.isUpdate) {
                        Ext.getCmp('solveButton').disable();
                        Ext.getCmp('updateButton').disable();
                        Ext.getCmp('revertButton').disable();
                    }

                    Ext.Ajax.request({
                        url: '<?php echo url_for("@disableTransactionNotification") ?>',
                        params: {
                            transactionID: transactionInfo.transactionID
                        },
                        scope: this,
                        callback: function (options, success, response) {
                            iSimulate.enableSync();
                        }
                    });
                }
            });
        }
    });

    Ext.reg('isimulate-tabpanel', iSimulate.TabPanel);
    iSimulate.TabPanel = Ext.extend(Ext.TabPanel, {
        activeTab: 0,
        tabPosition: 'bottom',
        onRemoteSuccess: function (response, options) {
            var jsonResponse = Ext.decode(response.responseText);
            if (jsonResponse.success) {
                this.loadGrids(jsonResponse);
                this.enableDefaultButtons();
                Ext.fly(document.body).unmask();
            } else {
                this.serverExceptionHandler(jsonResponse.errorText);
            }
        },
        onRemoteFailure: function (response, options) {
            Ext.fly(document.body).unmask();
            Ext.Msg.show({
                title: "Error",
                msg: "Unable to contact iSimulate solution engine. Please try again.",
                icon: Ext.Msg.ERROR
            });
        },
        updateIsimulateInfo: function (data) {
            var keys = ["transactionID", "isAggregate", "permissions", "updateAllowed"];
            for (var key in data) {
                if (data.hasOwnProperty(key) && keys.exists(key)) {
                    iSimulate.info[key] = data[key];
                }
            }
        },
        getGridByTitle: function (title) {
            var index = this.items.findIndex('title', title);
            if (index == -1) {
                return false;
            }

            return this.items.itemAt(index);
        },
        getGridData: function () {
            var data = [];
            this.items.each(function (grid, index, length) {
                if (grid.isInputGrid()) {
                    data = data.concat(grid.getData());
                }
            });
            return data;
        },
        loadGrids: function (gridData) {
            this.updateIsimulateInfo(gridData);
            for (var thisGrid in gridData['grids']) {
                var grid = this.getGridByTitle(thisGrid);
                if (grid) {
                    grid.getStore().proxy = new Ext.data.MemoryProxy(gridData['grids'][thisGrid]);
                    grid.getStore().load();
                }
            }

            // switch to current data view				
            var comparisonCombo = Ext.getCmp('comparisonCombo');
            comparisonCombo.switchTo(comparisonCombo.CURRENT);

            iSimulate.enableSync();
            this.getActiveTab().getView().scrollRight();
        },
        solveHandler: function (btn, comment) {
            Ext.fly(document.body).mask("Solving model...", "x-mask-loading");
            Ext.Ajax.request({
                url: '<?php echo url_for("@solveGrid?groupName=" . $group->getGroupName() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>',
                params: {
                    gridData: Ext.encode(this.getGridData()),
                    comment: comment
                },
                scope: this,
                success: function (response, options) {
                    var jsonResponse = Ext.decode(response.responseText);
                    if (jsonResponse.success) {
                        btn.lastComment = "";
                    }
                    this.onRemoteSuccess(response, options);
                },
                failure: this.onRemoteFailure
            });
        },
        updateHandler: function (btn, comment) {
            Ext.fly(document.body).mask("Updating model...", "x-mask-loading");
            Ext.Ajax.request({
                url: '<?php echo url_for("@updateGrid?groupName=" . $group->getGroupName() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>',
                params: {
                    comment: comment
                },
                scope: this,
                timeout: 240000,
                success: function (response, options) {
                    var jsonResponse = Ext.decode(response.responseText);
                    if (jsonResponse.success) {
                        Ext.fly(document.body).unmask();
                        btn.lastComment = "";
                        Ext.Msg.alert('Status', 'Update successful!');
                        this.updateIsimulateInfo(jsonResponse);
                        btn.disable(); // disable update button					
                        iSimulate.enableSync();
                    } else {
                        this.serverExceptionHandler(jsonResponse.errorText);
                    }
                },
                failure: this.onRemoteFailure
            });
        },
        revertHandler: function (win, transactionId) {
            if (isNaN(parseInt(transactionId))) {
                transactionId = "lastUpdate";
            }

            this.revertWindow.hide();
            Ext.fly(document.body).mask('Reverting...', 'x-mask-loading');
            Ext.Ajax.request({
                url: '<?php echo url_for("@revertToPrevious?groupName=" . $group->getGroupname() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>',
                params: {
                    revertToID: transactionId
                },
                scope: this,
                success: this.onRemoteSuccess,
                failure: this.onRemoteFailure
            });
        },
        serverExceptionHandler: function (errorText) {
            iSimulate.SyncMgr.stop();
            Ext.fly(document.body).unmask();
            Ext.Msg.confirm('Error', 'There has been an error while running your simulation.  Would you like to view the error log?', function (btn) {
                if (btn == 'yes') {
                    if (!this.errorWindow) {
                        this.errorWindow = new iSimulate.GenericWindow({
                            layout: 'fit',
                            width: 800,
                            height: 500,
                            minWidth: 300,
                            minHeight: 300,
                            resizable: true,
                            resizeHandles: 'se',
                            title: "Error log",
                            closeAction: 'hide',
                            modal: true,
                            items: [{
                                    html: errorText,
                                    autoScroll: true
                                }],
                            buttons: [{
                                    text: 'Close',
                                    scope: this,
                                    handler: function () {
                                        this.errorWindow.hide();
                                    }
                                }]
                        });
                    } else {
                        this.errorWindow.items.get(0).body.update(errorText);
                    }

                    this.errorWindow.show();
                }
            }, this);
        },
        enableDefaultButtons: function () {
            if (iSimulate.info.isAggregate) {
                Ext.getCmp('solveButton').disable();
                Ext.getCmp('updateButton').disable();
                Ext.getCmp('revertButton').disable();
                Ext.getCmp('endogenousModesButton').disable();
                Ext.getCmp('exogenizeModesButton').disable();
                Ext.getCmp('modelTypeCombo').disable();
            } else {
                var userPermissions = iSimulate.info.permissions;
                if (userPermissions.exists("solve")) {
                    Ext.getCmp('solveButton').enable();
                } else {
                    Ext.getCmp('solveButton').disable();
                }

                if (userPermissions.exists("update") && iSimulate.info.updateAllowed) {
                    Ext.getCmp('updateButton').enable();
                } else {
                    Ext.getCmp('updateButton').disable();
                }

                if (userPermissions.exists("revert")) {
                    Ext.getCmp('revertButton').enable();
                } else {
                    Ext.getCmp('revertButton').disable();
                }
            }
        },
        switchModelType: function (type) {
            var modelTypeCombo = Ext.getCmp('modelTypeCombo');
            if (type == modelTypeCombo.MODEL_SIMPLE) {
                this.getGridByTitle('NIA').switchModes('exogenousMode');
                this.getGridByTitle('BOP').switchModes('simpleMode');
                modelTypeCombo.setSimpleModel();
            } else {
                this.getGridByTitle('BOP').switchModes('exogenousMode');
                modelTypeCombo.setCompleteModel();
            }
        },
        modelTypeComboHandler: function (combo, record, index) {
            this.switchModelType(record.get('text'));
        },
        colorComboHandler: function (combo, record, index) {
            if (record.data.text == combo.COLORS_ON) {
                this.enableColors();
            } else {
                this.disableColors();
            }
        },
        enableColors: function () {
            this.items.each(function (grid, index, length) {
                grid.enableColors();
            });

            Ext.getCmp('colorCombo').setColorsOn();
        },
        disableColors: function () {
            this.items.each(function (grid, index, length) {
                grid.disableColors();
            });

            Ext.getCmp('colorCombo').setColorsOff();
        },
        comparisonComboHandler: function (combo, record, index) {
            var comboSelection = record.get('text');

            this.items.each(function (grid, index, length) {
                var store = grid.getStore();
                store.each(function (record) {
                    if (typeof record.dataComparison != "undefined") {
                        var currentMode = record.get('mode');
                        var currentDisplayType = record.get('displayType');
                        delete record.data;

                        if (comboSelection == combo.PREVIOUS) {
                            record.data = Ext.ux.clone(record.dataComparison);
                            record.set('mode', currentMode);
                            record.set('displayType', currentDisplayType);
                            record.onScreenDataBackupPointer = record.dataComparisonBackup;
                        } else if (comboSelection == combo.LASTUPDATE) {
                            record.data = Ext.ux.clone(record.dataComparison2);
                            record.set('mode', currentMode);
                            record.set('displayType', currentDisplayType);
                            record.onScreenDataBackupPointer = record.dataComparison2Backup;
                        } else { // current or one of the difference data sets
                            record.data = Ext.ux.clone(record.dataBackup);
                            record.set('mode', currentMode);
                            record.set('displayType', currentDisplayType);
                            record.onScreenDataBackupPointer = record.dataBackup;
                            store.renderRecordFromDisplayType(record, record.get('displayType'));

                            if (comboSelection == combo.DIFFERENCEPREVIOUS) {
                                for (var thisDataPoint in record.data) {
                                    if (thisDataPoint.substring(0, 1) == 'y') {
                                        record.data[thisDataPoint] = record.data[thisDataPoint] - record.dataComparison[thisDataPoint];
                                    }
                                }
                            } else if (comboSelection == combo.DIFFERENCEUPDATE) {
                                for (var thisDataPoint in record.data) {
                                    if (thisDataPoint.substring(0, 1) == 'y') {
                                        record.data[thisDataPoint] = record.data[thisDataPoint] - record.dataComparison2[thisDataPoint];
                                    }
                                }
                            }
                        }
                    }
                }, store);
            });


            this.getActiveTab().getView().refresh(); // refreshes the current grid

            if (comboSelection == combo.CURRENT) {
                this.enableDefaultButtons();
                this.enableColors();
            } else {
                Ext.getCmp('solveButton').disable();
                Ext.getCmp('updateButton').disable();
                this.disableColors();
            }
        },
        quickHelpHandler: function () {
            iSimulate.SyncMgr.stop();
            if (!this.quickHelpWindow) {
                this.quickHelpWindow = new iSimulate.QuickHelpWindow();
            }

            this.quickHelpWindow.show();
        },
        helpHandler: function () {
            iSimulate.SyncMgr.stop();
            if (!this.helpWindow) {
                this.helpWindow = new iSimulate.HelpWindow();
            }

            this.helpWindow.show();
        },
        afterGridEdit: function (e) {
            var cellsToProcess = [{
                    grid: e.grid,
                    mnemonic: e.grid.getStore().getAt(e.row).get('mnemonic'),
                    field: e.field,
                    value: e.value,
                    originalValue: e.originalValue
                }];

            var processedEffects = [];
            var gridsToRefresh = [];
            while (cellsToProcess.length) {
                var thisCell = cellsToProcess.pop();
                var sourceGrid = thisCell.grid;
                var thisRecord = sourceGrid.getStore().getRecordWithKeyValuePair('mnemonic', thisCell.mnemonic);
                if (thisCell.field == "displayType" && thisCell.originalValue != thisCell.value) {
                    sourceGrid.getStore().renderRecordFromDisplayType(thisRecord, thisRecord.get('displayType'));
                    sourceGrid.fireEvent('displayTypeChange', e.grid, thisRecord, thisCell.originalValue, thisCell.value);
                } else if (thisCell.field == "mode" && thisRecord.get('mnemonic') == "BNCABFUNDCD%") {
                    sourceGrid.fireEvent('modelTypeChange', sourceGrid, thisCell.value == "i" ? "complete" : "simple")
                }

                for (var thisTrigger in thisRecord.triggers) {
                    // check to see if this trigger should be activated
                    if (thisRecord.triggers.hasOwnProperty(thisTrigger) && thisRecord.triggers[thisTrigger].col == thisCell.field && thisRecord.triggers[thisTrigger].val == thisCell.value) {
                        for (var thisEffect in thisRecord.triggers[thisTrigger].effects) {
                            // get the unique ID for this trigger-effect
                            var thisEffectID = sourceGrid.title + "_" + thisRecord.get('mnemonic') + "_t" + thisTrigger + "_e" + thisEffect;
                            if (thisRecord.triggers[thisTrigger].effects.hasOwnProperty(thisEffect) && !processedEffects.exists(thisEffectID)) {
                                var effectGrid;
                                if (thisRecord.triggers[thisTrigger].effects[thisEffect].sheet) {
                                    effectGrid = this.items.find(function (grid) { // find grid with the name of the effect grid
                                        return grid.title == thisRecord.triggers[thisTrigger].effects[thisEffect].sheet;
                                    }, this);
                                } else {
                                    effectGrid = sourceGrid;
                                }
                                var effectRecord = effectGrid.getStore().getRecordWithKeyValuePair('mnemonic', thisRecord.triggers[thisTrigger].effects[thisEffect].mnemonic);
                                cellsToProcess.push({// add the cell this trigger-effect stack
                                    grid: effectGrid,
                                    mnemonic: thisRecord.triggers[thisTrigger].effects[thisEffect].mnemonic,
                                    field: thisRecord.triggers[thisTrigger].effects[thisEffect].col,
                                    value: thisRecord.triggers[thisTrigger].effects[thisEffect].val,
                                    originalValue: effectRecord.data[thisRecord.triggers[thisTrigger].effects[thisEffect].col]
                                });
                                // actually run the trigger-effect												
                                effectRecord.data[thisRecord.triggers[thisTrigger].effects[thisEffect].col] = thisRecord.triggers[thisTrigger].effects[thisEffect].val;
                                // add the trigger-effect unique ID to the array of processed trigger-effects
                                processedEffects.push(thisEffectID);
                                // add this grid as a grid to refresh
                                if (gridsToRefresh.exists(effectGrid) == false) {
                                    gridsToRefresh.push(effectGrid);
                                }
                            }
                        }
                    }
                }
            }

            // refresh all grids in need of refreshing
            Ext.each(gridsToRefresh, function (grid) {
                if (grid.rendered) {
                    grid.getView().refresh();
                }
            });
        },
        initComponent: function () {
            Ext.apply(this, {
                layoutOnTabChange: true,
                items: [{
                        title: "Loading",
                        html: "&nbsp;Loading..."
                    }],
                tbar: [{
                        xtype: 'isimulate-toolbar-commentbutton',
                        id: 'solveButton',
                        icon: '/images/control_play_blue.gif',
                        cls: 'x-btn-text-icon',
                        text: 'Solve',
                        listeners: {
                            scope: this,
                            finished: this.solveHandler
                        }
                    }, '-', {
                        xtype: 'isimulate-toolbar-commentbutton',
                        id: 'updateButton',
                        icon: '/images/resultset_up.gif',
                        cls: 'x-btn-text-icon',
                        text: 'Update',
                        listeners: {
                            scope: this,
                            finished: this.updateHandler
                        }
                    }, '-', {
                        xtype: 'button',
                        id: 'revertButton',
                        icon: '/images/undo2.gif',
                        cls: 'x-btn-text-icon',
                        text: 'Revert',
                        scope: this,
                        handler: function () {
                            iSimulate.SyncMgr.stop();
                            if (!this.revertWindow) {
                                this.revertWindow = new iSimulate.RevertWindow({
                                    listeners: {
                                        scope: this,
                                        revert: this.revertHandler
                                    }
                                });
                            } else {
                                this.revertWindow.items.itemAt(0).getRootNode().reload();
                            }

                            this.revertWindow.show();
                        }
                    }, '-', {
                        xtype: 'button',
                        id: 'endogenousModesButton',
                        text: 'D',
                        tooltip: 'Endogenous Mode',
                        scope: this,
                        handler: function (item, pressed) {
                            this.getActiveTab().switchModes("endogenousMode");
                        }
                    }, '-', {
                        xtype: 'button',
                        id: 'exogenizeModesButton',
                        text: 'E',
                        tooltip: 'Exogenous Mode',
                        scope: this,
                        handler: function (item, pressed) {
                            this.getActiveTab().switchModes("exogenousMode");
                        }
                    }, '-', {
                        xtype: 'isimulate-toolbar-modeltypecombo',
                        id: 'modelTypeCombo',
                        listeners: {
                            scope: this,
                            select: this.modelTypeComboHandler
                        }
                    }, '->', {
                        xtype: 'isimulate-toolbar-comparisoncombo',
                        id: 'comparisonCombo',
                        listeners: {
                            scope: this,
                            select: this.comparisonComboHandler
                        }
                    }, '-', {
                        xtype: 'isimulate-toolbar-colorcombo',
                        id: 'colorCombo',
                        listeners: {
                            scope: this,
                            select: this.colorComboHandler
                        }
                    }, '-', {
                        xtype: 'isimulate-toolbar-synccombo',
                        id: 'syncCombo'
                    }, '-', {
                        xtype: 'splitbutton',
                        icon: '/images/help.gif',
                        cls: 'x-btn-text-icon',
                        text: 'Help',
                        scope: this,
                        handler: this.quickHelpHandler,
                        menu: {
                            items: [{
                                    text: 'Quick Help',
                                    icon: '/images/wand.gif',
                                    scope: this,
                                    handler: this.quickHelpHandler
                                }, {
                                    text: 'Help Console',
                                    icon: '/images/information.gif',
                                    scope: this,
                                    handler: this.helpHandler
                                }]
                        }
                    }]
            });

            iSimulate.TabPanel.superclass.initComponent.apply(this, arguments);

            this.addEvents('ready');

            var loadingTab = this.items.get(0);

            this.on({
                scope: this,
                render: function (panel) {
                    Ext.Ajax.request({
                        url: '<?php echo url_for("@loadGrid?groupName=" . $group->getGroupname() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>',
                        scope: this,
                        success: function (response, options) {
                            try {
                                var responseData = Ext.decode(response.responseText);
                                this.updateIsimulateInfo(responseData);

                                for (var thisGrid in responseData["grids"]) {
                                    if (responseData["grids"][thisGrid]) {
                                        var grid = this.add({
                                            title: thisGrid,
                                            autoScroll: true,
                                            xtype: 'isimulate-grid',
                                            config: responseData["grids"][thisGrid],
                                            listeners: {
                                                scope: this,
                                                render: function (grid) {
                                                    if (this.items.get(0) == grid) { // is the first grid
                                                        this.fireEvent('ready');
                                                    }
                                                },
                                                modelTypeChange: function (grid, modelType) {
                                                    var modelTypeCombo = Ext.getCmp('modelTypeCombo');
                                                    if (modelType == "complete") {
                                                        modelTypeCombo.setCompleteModel();
                                                    } else {
                                                        modelTypeCombo.setSimpleModel();
                                                    }
                                                },
                                                displayTypeChange: function (grid, record, oldDisplayType, newDisplayType) {
                                                    var comparisonCombo = Ext.getCmp('comparisonCombo');
                                                    if (comparisonCombo.lastSelectionText == comparisonCombo.DIFFERENCE) {
                                                        for (var thisCell in record.data) {
                                                            if (thisCell.substring(0, 1) == 'y') {
                                                                record.data[thisCell] = record.data[thisCell] - record.dataComparison[thisCell];
                                                            }
                                                        }
                                                    }
                                                },
                                                afteredit: this.afterGridEdit
                                            }
                                        });

                                        this.remove(loadingTab);
                                    }
                                }

                                this.enableDefaultButtons();
                            } catch (e) { // some problem on the backend.  maybe a failed solve?
                                Ext.fly(document.body).unmask();
                                Ext.Msg.show({
                                    title: "Error",
                                    msg: "Unable to load data.  Please refresh this page and try again.",
                                    icon: Ext.Msg.ERROR,
                                    closable: false
                                });
                            }
                        },
                        failure: function (response, options) {
                            Ext.fly(document.body).unmask();
                            Ext.Msg.show({
                                title: "Error",
                                msg: "Unable to load data. Please refresh this page and try again.",
                                icon: Ext.Msg.ERROR,
                                closable: false
                            });
                        }
                    });
                },
                tabchange: function (tabpanel, grid) {
                    if (typeof grid.isInputGrid == "function") {
                        var comparisonCombo = Ext.getCmp('comparisonCombo');
                        if (grid.isInputGrid()) {
                            comparisonCombo.enable();
                        } else {
                            comparisonCombo.switchTo(comparisonCombo.CURRENT);
                            comparisonCombo.disable();
                        }
                    }
                }
            });

            iSimulate.SyncMgr.on({
                scope: this,
                acceptsSync: function (syncMgr, transactionInfo) {
                    Ext.fly(document.body).mask("Refreshing...", "x-mask-loading");
                    Ext.Ajax.request({
                        url: '<?php echo url_for("@loadGrid?groupName=" . $group->getGroupname() . "&application=" . $app->getApplicationName() . "&entityCode=" . $entity->getEntityCode()) ?>',
                        scope: this,
                        success: this.onRemoteSuccess,
                        failure: this.onRemoteFailure
                    });
                },
                declinesSync: function (syncMgr, transactionInfo) {
                    if (transactionInfo.isUpdate) {
                        Ext.getCmp('solveButton').disable();
                        Ext.getCmp('updateButton').disable();
                        Ext.getCmp('revertButton').disable();
                    }

                    Ext.Ajax.request({
                        url: '<?php echo url_for("@disableTransactionNotification") ?>',
                        params: {
                            transactionID: transactionInfo.transactionID
                        },
                        scope: this,
                        callback: function (options, success, response) {
                            iSimulate.enableSync();
                        }
                    });
                }
            });
        }
    });

    Ext.reg('isimulate-tabpanel', iSimulate.TabPanel);
    Ext.override(Ext.grid.ColumnModel, {
        updateEditor: function (record, colIndex, rowIndex) {
            var dataIndex = this.getDataIndex(colIndex);
            dataIndex = 'possible' + dataIndex.substring(0, 1).toUpperCase() + dataIndex.substring(1, dataIndex.length) + 's';
            if (typeof record.json[dataIndex] !== "undefined") {
                var ourData = [];
                var i = 0;
                for (var item in record.json[dataIndex]) {
                    ourData[i] = [item, record.json[dataIndex][item]];
                    i++;
                }

                this.setEditor(colIndex, new Ext.grid.GridEditor(
                        new iSimulate.Grid.EditorCombo({configData: ourData})
                        ));
            }
        }
    });
    iSimulate.Grid = Ext.extend(Ext.grid.LockingEditorGridPanel, {
        autoScroll: true,
        stripeRows: true,
        clicksToEdit: 1,
        enableColumnMove: false,
        getData: function () {
            var data = [];
            this.getStore().each(function (record) {
                if (record.get('mnemonic') != "") {
                    var thisRow = {};
                    Ext.each(this.getColumnModel().config, function (col, index, allCols) {
                        var cellValue = record.get(col.dataIndex);
                        thisRow[col.dataIndex] = typeof cellValue == "string" || isFinite(cellValue) ? cellValue : 0;
                    }, this);
                    if (thisRow != "undefined") {
                        data.push(thisRow);
                    }
                }
            }, this);

            return data;
        },
        isInputGrid: function () {
            return typeof this.config.type == "undefined" || this.config.type != "external";
        },
        isExternalGrid: function () {
            return typeof this.config.type !== "undefined" && this.config.type == "external";
        },
        switchModes: function (modeType) {
            this.getStore().each(function (record) {
                var mode = record.json[modeType];
                if (typeof mode != "undefined" && mode != "") {
                    record.set('mode', mode);
                }
            });

            if (this.rendered) {
                this.getView().refresh();
            }
        },
        enableColors: function () {
            if (this.isInputGrid()) {
                this._changeColorHelper(true);
            }
        },
        disableColors: function () {
            if (this.isInputGrid()) {
                this._changeColorHelper(false);
            }
        },
        _changeColorHelper: function (enable) {
            var renderer = enable ? iSimulate.Grid.RendererWithColors : iSimulate.Grid.RendererWithoutColors;
            var cm = this.getColumnModel();
            var numColumns = cm.getColumnCount();
            for (var col = 0; col < numColumns; col++) {
                cm.setRenderer(col, renderer);
            }

            if (this.rendered) {
                this.getView().refresh();
            }
        },
        initComponent: function () {
            // set up column configuration for this grid
            var cols = [];
            for (var thisCol in this.config.columnModel) {
                var jsonCol = this.config.columnModel[thisCol];
                if (jsonCol && typeof jsonCol["header"] !== "undefined") {
                    var columnConfig = {
                        header: jsonCol['header'],
                        dataIndex: jsonCol['dataIndex'],
                        locked: typeof jsonCol['locked'] !== "undefined",
                        hidden: typeof jsonCol['hidden'] !== "undefined",
                        width: typeof jsonCol['width'] !== "undefined" ? parseInt(jsonCol['width']) : 20
                    };

                    // added for external data, need to put in excel plugin
                    if (typeof this.config["type"] !== "undefined" && this.config.type == "external") {
                        columnConfig.renderer = iSimulate.Grid.RendererExternalData;
                    }

                    if (typeof jsonCol['colType'] !== "undefined") {
                        columnConfig.renderer = iSimulate.Grid.RendererWithColors;

                        var editorConfig = {
                            allowBlank: typeof jsonCol['allowBlank'] !== "undefined",
                            selectOnFocus: typeof jsonCol['selectOnFocus'] !== "undefined"
                        };

                        if (jsonCol['colType'] == "number") {
                            columnConfig.editor = new Ext.form.NumberField(editorConfig);
                        } else {
                            columnConfig.editor = new Ext.form.TextField(editorConfig);
                        }
                    }

                    cols.push(columnConfig);
                }
            }

            // set up store for this grid
            var storeFields = [];
            for (var thisCol in this.config["displayColumns"]) {
                var jsonCol = this.config["displayColumns"][thisCol];
                if (jsonCol) {
                    storeFields.push({name: jsonCol});
                }
            }

            Ext.apply(this, {
                columns: cols,
                store: new iSimulate.Grid.Store({
                    reader: new Ext.data.JsonReader({root: "theData"}, Ext.data.Record.create(storeFields)),
                    proxy: new Ext.data.MemoryProxy(this.config),
                    autoLoad: true,
                    xmlVars: this.config.jsVars ? this.config.jsVars : {}
                })
            });

            iSimulate.Grid.superclass.initComponent.apply(this, arguments);

            this.addEvents('modelTypeChange', 'displayTypeChange');

            this.on({
                scope: this,
                beforeedit: function (e) {
                    e.grid.getColumnModel().updateEditor(e.record, e.column, e.row);
                }
            });

            this.getStore().on({
                scope: this,
                load: function (store, records, options) {
                    var index = store.findBy(function (record, id) {
                        return record.get('mnemonic') == "BNCABFUNDCD%";
                    });
                    if (index != -1) {
                        this.fireEvent('modelTypeChange', this, store.getAt(index).get('mode') == "i" ? "complete" : "simple");
                    }
                }
            });
        }
    });

    Ext.reg('isimulate-grid', iSimulate.Grid);
    iSimulate.Grid.Store = function () {
        iSimulate.Grid.Store.superclass.constructor.apply(this, arguments);

        this.on({
            scope: this,
            load: function (store, records, options) {
                store.each(function (record) {
                    delete record.dataBackup;
                    record.dataBackup = Ext.ux.clone(record.data);
                    record.triggers = record.json.triggers;
                    delete record.dataComparison;
                    if (typeof record.json.comparison != "undefined") {
                        record.dataComparison = new Ext.ux.clone(record.json.comparison);
                        delete record.dataComparisonBackup;
                        record.dataComparisonBackup = new Ext.ux.clone(record.json.comparison);
                    }
                    delete record.dataComparison2;
                    if (typeof record.json.comparison2 != "undefined") {
                        record.dataComparison2 = new Ext.ux.clone(record.json.comparison2);
                        delete record.dataComparison2Backup;
                        record.dataComparison2Backup = new Ext.ux.clone(record.json.comparison2);
                    }
                    record.onScreenDataBackupPointer = record.dataBackup;
                    //this.renderRecordFromDisplayType(record,record.data['displayType']);				
                }, this);
            }
        });
    };

    Ext.extend(iSimulate.Grid.Store, Ext.data.Store, {
        calcGrowthRate: function (numerator1, numerator2, denominator) {
            return ((numerator1 - numerator2) / denominator) * 100;
        },
        getRecordWithKeyValuePair: function (key, value) {
            var found = false;
            var i = 0;
            var toReturn;
            while (!found && i < this.data.items.length) {
                if (this.data.items[i].data[key] == value) {
                    found = true;
                    toReturn = this.data.items[i];
                }
                i++;
            }

            return toReturn;
        },
        renderRecordFromDisplayType: function (record, displayType) {
            switch (displayType) {
                case 'g':
                    this.renderAsGrowthRate(record);
                    break;
                case 'l':
                    this.renderAsLevel(record);
                    break;
                case 'contr':
                    this.renderAsContributionToGrowth(record);
                    break;
                case '%gdp':
                    this.renderAsPercentOfGDP(record);
                    break;
                case 'add':
                    this.renderAsAddFactor(record);
                    break;
                case 'qa':
                    this.renderAsQuarterlyAnnualized(record);
                    break;
                case 'ma':
                    this.renderAsMonthlyAnnualized(record);
                    break;
            }
        },
        renderAsGrowthRate: function (record) {
            var colCount = 0;
            for (var thisCell in record.data) {
                if (thisCell.substring(0, 1) == 'y') {
                    var year = thisCell.substring(1, 5);
                    var lastCell = "y" + (year - 1) + thisCell.substring(5);
                    record.data[thisCell] = Math.round(this.calcGrowthRate(record.onScreenDataBackupPointer[thisCell], record.onScreenDataBackupPointer[lastCell], record.onScreenDataBackupPointer[lastCell]) * 100000) / 100000;
                    record.dataComparison[thisCell] = Math.round(this.calcGrowthRate(record.dataComparisonBackup[thisCell], record.dataComparisonBackup[lastCell], record.dataComparisonBackup[lastCell]) * 100000) / 100000;
                    record.dataComparison2[thisCell] = Math.round(this.calcGrowthRate(record.dataComparison2Backup[thisCell], record.dataComparison2Backup[lastCell], record.dataComparison2Backup[lastCell]) * 100000) / 100000;
                }
                colCount++;
            }
        },
        renderAsLevel: function (record) {
            for (var thisCell in record.data) {
                if (thisCell.substring(0, 1) == 'y') {
                    var thisCellData = record.onScreenDataBackupPointer[thisCell] * 1;
                    record.data[thisCell] = Math.round(thisCellData * 100000) / 100000;
                    thisCellData = record.dataComparisonBackup[thisCell] * 1;
                    record.dataComparison[thisCell] = Math.round(thisCellData * 100000) / 100000;
                    thisCellData = record.dataComparison2Backup[thisCell] * 1;
                    record.dataComparison2[thisCell] = Math.round(thisCellData * 100000) / 100000;
                }
            }
        },
        renderAsContributionToGrowth: function (record) {
            var gdpRecord = this.getRecordWithKeyValuePair('mnemonic', this.xmlVars.gdpMnemonic);

            var colCount = 0;
            for (var thisCell in record.data) {
                if (thisCell.substring(0, 1) == 'y') {
                    var year = thisCell.substring(1, 5);
                    var lastCell = "y" + (year - 1) + thisCell.substring(5);
                    record.data[thisCell] = Math.round(this.calcGrowthRate(record.onScreenDataBackupPointer[thisCell], record.onScreenDataBackupPointer[lastCell], gdpRecord.onScreenDataBackupPointer[lastCell]) * 100000) / 100000;
                    record.dataComparison[thisCell] = Math.round(this.calcGrowthRate(record.dataComparisonBackup[thisCell], record.dataComparisonBackup[lastCell], gdpRecord.dataComparisonBackup[lastCell]) * 100000) / 100000;
                    record.dataComparison2[thisCell] = Math.round(this.calcGrowthRate(record.dataComparison2Backup[thisCell], record.dataComparison2Backup[lastCell], gdpRecord.dataComparison2Backup[lastCell]) * 100000) / 100000;
                }
                colCount++;
            }
        },
        renderAsPercentOfGDP: function (record) {
            var gdpRecord = this.getRecordWithKeyValuePair('mnemonic', this.xmlVars.gdpMnemonic);

            for (var thisCell in record.data) {
                if (thisCell.substring(0, 1) == 'y') {
                    var perGDP = (record.onScreenDataBackupPointer[thisCell] / gdpRecord.onScreenDataBackupPointer[thisCell]) * 100;
                    record.data[thisCell] = Math.round(perGDP * 100000) / 100000;

                    perGDP = (record.dataComparisonBackup[thisCell] / gdpRecord.dataComparisonBackup[thisCell]) * 100;
                    record.dataComparison[thisCell] = Math.round(perGDP * 100000) / 100000;

                    perGDP = (record.dataComparison2Backup[thisCell] / gdpRecord.dataComparison2Backup[thisCell]) * 100;
                    record.dataComparison2[thisCell] = Math.round(perGDP * 100000) / 100000;
                }
            }
        },
        renderAsAddFactor: function (record) {
            var varWithoutAddFactor;
            if (record.data['mnemonic'].indexOf(".ADD") > -1) { // aremos
                varWithoutAddFactor = record.data['mnemonic'].substring(0, record.data['mnemonic'].length - 4);
            } else { // eviews
                varWithoutAddFactor = record.data['mnemonic'].substring(0, record.data['mnemonic'].length - 2);
            }
            var varRecord = this.getRecordWithKeyValuePair('mnemonic', varWithoutAddFactor);

            var colCount = 0;
            for (var thisCell in record.data) {
                if (thisCell.substring(0, 1) == 'y') {
                    var year = thisCell.substring(1, 5);
                    var lastCell = "y" + (year - 1) + thisCell.substring(5);
                    var percentOfVar = (record.onScreenDataBackupPointer[thisCell] / varRecord.onScreenDataBackupPointer[lastCell]) * 100;
                    record.data[thisCell] = Math.round(percentOfVar * 100000) / 100000;

                    percentOfVar = (record.dataComparisonBackup[thisCell] / varRecord.dataComparisonBackup[lastCell]) * 100;
                    record.dataComparison[thisCell] = Math.round(percentOfVar * 100000) / 100000;

                    percentOfVar = (record.dataComparison2Backup[thisCell] / varRecord.dataComparison2Backup[lastCell]) * 100;
                    record.dataComparison2[thisCell] = Math.round(percentOfVar * 100000) / 100000;
                }
                colCount++;
            }
        },
        renderAsQuarterlyAnnualized: function (record) {
            var colCount = 0;
            for (var thisCell in record.data) {
                if (thisCell.substring(0, 1) == 'y' && thisCell.charAt(5).toUpperCase() == "Q") { // only works for quarterly data
                    var year = thisCell.substring(1, 5);
                    var quarter = thisCell.charAt(thisCell.length - 1);
                    var lastCell = "y" + (quarter == "1" ? ((year - 1) + "Q4") : (year + "Q" + (quarter - 1)));
                    record.data[thisCell] = Math.round((Math.pow((record.onScreenDataBackupPointer[thisCell] / record.onScreenDataBackupPointer[lastCell]), 4) - 1) * 100 * 100000) / 100000;
                    record.dataComparison[thisCell] = Math.round((Math.pow((record.dataComparisonBackup[thisCell] / record.dataComparisonBackup[lastCell]), 4) - 1) * 100 * 100000) / 100000;
                    record.dataComparison2[thisCell] = Math.round((Math.pow((record.dataComparison2Backup[thisCell] / record.dataComparison2Backup[lastCell]), 4) - 1) * 100 * 100000) / 100000;
                }
                colCount++;
            }
        },
        renderAsMonthlyAnnualized: function (record) {
            var colCount = 0;
            for (var thisCell in record.data) {
                if (thisCell.substring(0, 1) == 'y' && thisCell.charAt(5).toUpperCase() == "M") { // only works for monthly data
                    var year = thisCell.substring(1, 5);
                    var month = thisCell.substring(6);
                    if (month == "01") {
                        year--;
                        month = 12;
                    } else {
                        month--;
                        if (month < 10) {
                            month = "0" + month;
                        }
                    }
                    var lastCell = "y" + year + "M" + month;
                    record.data[thisCell] = Math.round((Math.pow((record.onScreenDataBackupPointer[thisCell] / record.onScreenDataBackupPointer[lastCell]), 12) - 1) * 100 * 100000) / 100000;
                    record.dataComparison[thisCell] = Math.round((Math.pow((record.dataComparisonBackup[thisCell] / record.dataComparisonBackup[lastCell]), 12) - 1) * 100 * 100000) / 100000;
                    record.dataComparison2[thisCell] = Math.round((Math.pow((record.dataComparison2Backup[thisCell] / record.dataComparison2Backup[lastCell]), 12) - 1) * 100 * 100000) / 100000;
                }
                colCount++;
            }
        }
    });
    iSimulate.Grid.RendererWithColors = function (data, cell, record, rowIndex, colIndex, store) {
        if (data == null || typeof data == "undefined")
            return data;

        if (typeof record.dataComparison != "undefined" && typeof record.dataComparison[this.name] != "undefined") {
            var difference;
            if (record.get('displayType') == "l") {
                difference = store.calcGrowthRate(record.data[this.name], record.dataComparison[this.name], record.dataComparison[this.name]);
            } else {
                difference = record.data[this.name] - record.dataComparison[this.name];
            }

            difference = Math.abs(difference);

            if (difference >= 1) {
                cell.css = "cellColorLarge";
            } else if (difference >= 0.5) {
                cell.css = "cellColorMiddle";
            } else if (difference >= 0.1) {
                cell.css = "cellColorSmall";
            }

            if (colIndex > (store.fields.keys.length - 13)) {
                switch (record.get('mode')) {
                    case "x":
                        cell.css += " exogenousCell";
                        break;
                    case "e":
                        cell.css += " endogenousCell";
                        break;
                }
            } else if (!this.locked) {
                cell.css += " historicalCell";
            }
        }

        if (typeof data.toFixed != "undefined") {
            return data.toFixed(1);
        }

        return data;
    };

    iSimulate.Grid.RendererWithoutColors = function (data, cell, record, rowIndex, colIndex, store) {
        if (data == null || typeof data == "undefined")
            return data;

        cell.css = !this.locked && colIndex <= (store.fields.keys.length - 13) ? "historicalCell" : "";

        if (typeof data.toFixed != "undefined") {
            return data.toFixed(1);
        }

        return data;
    };

    iSimulate.Grid.RendererExternalData = function (data, cell, record, rowIndex, colIndex, store) {
        if (data == null || typeof data == "undefined")
            return data;

        if (typeof data.toFixed != "undefined") {
            return data.toFixed(1);
        }

        return data;
    };
    iSimulate.Grid.EditorCombo = Ext.extend(Ext.form.ComboBox, {
        initComponent: function () {
            Ext.apply(this, {
                valueField: 'id',
                displayField: 'text',
                selectOnFocus: true,
                forceSelection: true,
                triggerAction: 'all',
                typeAhead: true,
                mode: 'local',
                editable: false,
                lazyRender: true,
                listWidth: 115,
                store: new Ext.data.SimpleStore({
                    fields: ['id', 'text'],
                    data: this.configData
                })
            });

            iSimulate.Grid.EditorCombo.superclass.initComponent.apply(this, arguments);
        }
    });

    Ext.reg('isimulate-grid-editorcombo', iSimulate.Grid.EditorCombo);
    Ext.onReady(function () {
        Ext.QuickTips.init();
        Ext.fly(document.body).mask("Loading <?php echo $entity->getEntityName() ?>", "x-mask-loading");

        Ext.apply(iSimulate, {
            info: {},
            enableSync: function () {
                var syncCombo = Ext.getCmp('syncCombo');
                if (syncCombo.getValue() == syncCombo.SYNC_ON) {
                    iSimulate.SyncMgr.start();
                }
            }
        });

        var viewport = new Ext.Viewport({
            layout: 'border',
            items: [{
                    region: 'north',
                    height: 25,
                    items: [{
                            xtype: 'toolbar',
                            items: [{
                                    xtype: 'isimulate-newcountrycombo'
                                }, ' ', ' ', ' ', ' ', ' ',
                                'Currently viewing <b><?php echo $entity->getEntityName() ?></b> using the ' +
                                        '<?php echo link_to($app->getApplicationName(), "@applicationIndex?applicationName=" . $app->getApplicationName()) ?> application in the ' +
                                        '<?php echo link_to($group->getGroupname(), "@groupShow?groupName=" . $group->getGroupname()) ?> group. '
                                        , '->',
                                '<?php echo link_to("Home", "@homepage", array("style" => "margin-right:8px")) ?>' +
                                        '<?php echo link_to("My Profile", "@userProfile", array("style" => "margin-right:8px")) ?>' +
                                        '<?php echo link_to("Log Out", "@logout") ?>'
                            ]
                        }]
                }, {
                    region: 'center',
                    xtype: 'isimulate-tabpanel',
                    listeners: {
                        ready: function () {
                            var body = Ext.fly(document.body);
                            body.unmask.defer(1000, body);
                            iSimulate.SyncMgr.start();
                        }
                    }
                }, {
                    region: 'south',
                    html: '<div style="background-color: #DFE8F6">&copy; 2007 - ' + (new Date()).getFullYear() + ' <a target="out" href="http://www.worldbank.org">The World Bank</a></div>'
                }]
        });
    });

</script>

