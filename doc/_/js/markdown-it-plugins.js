/*! markdown-it-container 2.0.0 https://github.com//markdown-it/markdown-it-container @license MIT */(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.markdownitContainer = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
// Process block-level custom containers
//
    'use strict';


    module.exports = function container_plugin(md, name, options) {

        function validateDefault(params) {
            return params.trim().split(' ', 2)[0] === name;
        }

        function renderDefault(tokens, idx, _options, env, self) {

            // add a class to the opening tag
            if (tokens[idx].nesting === 1) {
                tokens[idx].attrPush([ 'class', name ]);
            }

            return self.renderToken(tokens, idx, _options, env, self);
        }

        options = options || {};

        var min_markers = 3,
            marker_str  = options.marker || ':',
            marker_char = marker_str.charCodeAt(0),
            marker_len  = marker_str.length,
            validate    = options.validate || validateDefault,
            render      = options.render || renderDefault;

        function container(state, startLine, endLine, silent) {
            var pos, nextLine, marker_count, markup, params, token,
                old_parent, old_line_max,
                auto_closed = false,
                start = state.bMarks[startLine] + state.tShift[startLine],
                max = state.eMarks[startLine];

            // Check out the first character quickly,
            // this should filter out most of non-containers
            //
            if (marker_char !== state.src.charCodeAt(start)) { return false; }

            // Check out the rest of the marker string
            //
            for (pos = start + 1; pos <= max; pos++) {
                if (marker_str[(pos - start) % marker_len] !== state.src[pos]) {
                    break;
                }
            }

            marker_count = Math.floor((pos - start) / marker_len);
            if (marker_count < min_markers) { return false; }
            pos -= (pos - start) % marker_len;

            markup = state.src.slice(start, pos);
            params = state.src.slice(pos, max);
            if (!validate(params)) { return false; }

            // Since start is found, we can report success here in validation mode
            //
            if (silent) { return true; }

            // Search for the end of the block
            //
            nextLine = startLine;

            for (;;) {
                nextLine++;
                if (nextLine >= endLine) {
                    // unclosed block should be autoclosed by end of document.
                    // also block seems to be autoclosed by end of parent
                    break;
                }

                start = state.bMarks[nextLine] + state.tShift[nextLine];
                max = state.eMarks[nextLine];

                if (start < max && state.sCount[nextLine] < state.blkIndent) {
                    // non-empty line with negative indent should stop the list:
                    // - ```
                    //  test
                    break;
                }

                if (marker_char !== state.src.charCodeAt(start)) { continue; }

                if (state.sCount[nextLine] - state.blkIndent >= 4) {
                    // closing fence should be indented less than 4 spaces
                    continue;
                }

                for (pos = start + 1; pos <= max; pos++) {
                    if (marker_str[(pos - start) % marker_len] !== state.src[pos]) {
                        break;
                    }
                }

                // closing code fence must be at least as long as the opening one
                if (Math.floor((pos - start) / marker_len) < marker_count) { continue; }

                // make sure tail has spaces only
                pos -= (pos - start) % marker_len;
                pos = state.skipSpaces(pos);

                if (pos < max) { continue; }

                // found!
                auto_closed = true;
                break;
            }

            old_parent = state.parentType;
            old_line_max = state.lineMax;
            state.parentType = 'container';

            // this will prevent lazy continuations from ever going past our end marker
            state.lineMax = nextLine;

            token        = state.push('container_' + name + '_open', 'div', 1);
            token.markup = markup;
            token.block  = true;
            token.info   = params;
            token.map    = [ startLine, nextLine ];

            state.md.block.tokenize(state, startLine + 1, nextLine);

            token        = state.push('container_' + name + '_close', 'div', -1);
            token.markup = state.src.slice(start, pos);
            token.block  = true;

            state.parentType = old_parent;
            state.lineMax = old_line_max;
            state.line = nextLine + (auto_closed ? 1 : 0);

            return true;
        }

        md.block.ruler.before('fence', 'container_' + name, container, {
            alt: [ 'paragraph', 'reference', 'blockquote', 'list' ]
        });
        md.renderer.rules['container_' + name + '_open'] = render;
        md.renderer.rules['container_' + name + '_close'] = render;
    };

},{}]},{},[1])(1)
});/*! markdown-it-footnote 3.0.1 https://github.com//markdown-it/markdown-it-footnote @license MIT */(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.markdownitFootnote = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
// Process footnotes
//
    'use strict';

////////////////////////////////////////////////////////////////////////////////
// Renderer partials

    function render_footnote_anchor_name(tokens, idx, options, env/*, slf*/) {
        var n = Number(tokens[idx].meta.id + 1).toString();
        var prefix = '';

        if (typeof env.docId === 'string') {
            prefix = '-' + env.docId + '-';
        }

        return prefix + n;
    }

    function render_footnote_caption(tokens, idx/*, options, env, slf*/) {
        var n = Number(tokens[idx].meta.id + 1).toString();

        if (tokens[idx].meta.subId > 0) {
            n += ':' + tokens[idx].meta.subId;
        }

        return '[' + n + ']';
    }

    function render_footnote_ref(tokens, idx, options, env, slf) {
        var id      = slf.rules.footnote_anchor_name(tokens, idx, options, env, slf);
        var caption = slf.rules.footnote_caption(tokens, idx, options, env, slf);
        var refid   = id;

        if (tokens[idx].meta.subId > 0) {
            refid += ':' + tokens[idx].meta.subId;
        }

        return '<sup class="footnote-ref"><a href="#fn' + id + '" id="fnref' + refid + '">' + caption + '</a></sup>';
    }

    function render_footnote_block_open(tokens, idx, options) {
        return (options.xhtmlOut ? '<hr class="footnotes-sep" />\n' : '<hr class="footnotes-sep">\n') +
            '<section class="footnotes">\n' +
            '<ol class="footnotes-list">\n';
    }

    function render_footnote_block_close() {
        return '</ol>\n</section>\n';
    }

    function render_footnote_open(tokens, idx, options, env, slf) {
        var id = slf.rules.footnote_anchor_name(tokens, idx, options, env, slf);

        if (tokens[idx].meta.subId > 0) {
            id += ':' + tokens[idx].meta.subId;
        }

        return '<li id="fn' + id + '" class="footnote-item">';
    }

    function render_footnote_close() {
        return '</li>\n';
    }

    function render_footnote_anchor(tokens, idx, options, env, slf) {
        var id = slf.rules.footnote_anchor_name(tokens, idx, options, env, slf);

        if (tokens[idx].meta.subId > 0) {
            id += ':' + tokens[idx].meta.subId;
        }

        /* ↩ with escape code to prevent display as Apple Emoji on iOS */
        return ' <a href="#fnref' + id + '" class="footnote-backref">\u21a9\uFE0E</a>';
    }


    module.exports = function footnote_plugin(md) {
        var parseLinkLabel = md.helpers.parseLinkLabel,
            isSpace = md.utils.isSpace;

        md.renderer.rules.footnote_ref          = render_footnote_ref;
        md.renderer.rules.footnote_block_open   = render_footnote_block_open;
        md.renderer.rules.footnote_block_close  = render_footnote_block_close;
        md.renderer.rules.footnote_open         = render_footnote_open;
        md.renderer.rules.footnote_close        = render_footnote_close;
        md.renderer.rules.footnote_anchor       = render_footnote_anchor;

        // helpers (only used in other rules, no tokens are attached to those)
        md.renderer.rules.footnote_caption      = render_footnote_caption;
        md.renderer.rules.footnote_anchor_name  = render_footnote_anchor_name;

        // Process footnote block definition
        function footnote_def(state, startLine, endLine, silent) {
            var oldBMark, oldTShift, oldSCount, oldParentType, pos, label, token,
                initial, offset, ch, posAfterColon,
                start = state.bMarks[startLine] + state.tShift[startLine],
                max = state.eMarks[startLine];

            // line should be at least 5 chars - "[^x]:"
            if (start + 4 > max) { return false; }

            if (state.src.charCodeAt(start) !== 0x5B/* [ */) { return false; }
            if (state.src.charCodeAt(start + 1) !== 0x5E/* ^ */) { return false; }

            for (pos = start + 2; pos < max; pos++) {
                if (state.src.charCodeAt(pos) === 0x20) { return false; }
                if (state.src.charCodeAt(pos) === 0x5D /* ] */) {
                    break;
                }
            }

            if (pos === start + 2) { return false; } // no empty footnote labels
            if (pos + 1 >= max || state.src.charCodeAt(++pos) !== 0x3A /* : */) { return false; }
            if (silent) { return true; }
            pos++;

            if (!state.env.footnotes) { state.env.footnotes = {}; }
            if (!state.env.footnotes.refs) { state.env.footnotes.refs = {}; }
            label = state.src.slice(start + 2, pos - 2);
            state.env.footnotes.refs[':' + label] = -1;

            token       = new state.Token('footnote_reference_open', '', 1);
            token.meta  = { label: label };
            token.level = state.level++;
            state.tokens.push(token);

            oldBMark = state.bMarks[startLine];
            oldTShift = state.tShift[startLine];
            oldSCount = state.sCount[startLine];
            oldParentType = state.parentType;

            posAfterColon = pos;
            initial = offset = state.sCount[startLine] + pos - (state.bMarks[startLine] + state.tShift[startLine]);

            while (pos < max) {
                ch = state.src.charCodeAt(pos);

                if (isSpace(ch)) {
                    if (ch === 0x09) {
                        offset += 4 - offset % 4;
                    } else {
                        offset++;
                    }
                } else {
                    break;
                }

                pos++;
            }

            state.tShift[startLine] = pos - posAfterColon;
            state.sCount[startLine] = offset - initial;

            state.bMarks[startLine] = posAfterColon;
            state.blkIndent += 4;
            state.parentType = 'footnote';

            if (state.sCount[startLine] < state.blkIndent) {
                state.sCount[startLine] += state.blkIndent;
            }

            state.md.block.tokenize(state, startLine, endLine, true);

            state.parentType = oldParentType;
            state.blkIndent -= 4;
            state.tShift[startLine] = oldTShift;
            state.sCount[startLine] = oldSCount;
            state.bMarks[startLine] = oldBMark;

            token       = new state.Token('footnote_reference_close', '', -1);
            token.level = --state.level;
            state.tokens.push(token);

            return true;
        }

        // Process inline footnotes (^[...])
        function footnote_inline(state, silent) {
            var labelStart,
                labelEnd,
                footnoteId,
                token,
                tokens,
                max = state.posMax,
                start = state.pos;

            if (start + 2 >= max) { return false; }
            if (state.src.charCodeAt(start) !== 0x5E/* ^ */) { return false; }
            if (state.src.charCodeAt(start + 1) !== 0x5B/* [ */) { return false; }

            labelStart = start + 2;
            labelEnd = parseLinkLabel(state, start + 1);

            // parser failed to find ']', so it's not a valid note
            if (labelEnd < 0) { return false; }

            // We found the end of the link, and know for a fact it's a valid link;
            // so all that's left to do is to call tokenizer.
            //
            if (!silent) {
                if (!state.env.footnotes) { state.env.footnotes = {}; }
                if (!state.env.footnotes.list) { state.env.footnotes.list = []; }
                footnoteId = state.env.footnotes.list.length;

                state.md.inline.parse(
                    state.src.slice(labelStart, labelEnd),
                    state.md,
                    state.env,
                    tokens = []
                );

                token      = state.push('footnote_ref', '', 0);
                token.meta = { id: footnoteId };

                state.env.footnotes.list[footnoteId] = { tokens: tokens };
            }

            state.pos = labelEnd + 1;
            state.posMax = max;
            return true;
        }

        // Process footnote references ([^...])
        function footnote_ref(state, silent) {
            var label,
                pos,
                footnoteId,
                footnoteSubId,
                token,
                max = state.posMax,
                start = state.pos;

            // should be at least 4 chars - "[^x]"
            if (start + 3 > max) { return false; }

            if (!state.env.footnotes || !state.env.footnotes.refs) { return false; }
            if (state.src.charCodeAt(start) !== 0x5B/* [ */) { return false; }
            if (state.src.charCodeAt(start + 1) !== 0x5E/* ^ */) { return false; }

            for (pos = start + 2; pos < max; pos++) {
                if (state.src.charCodeAt(pos) === 0x20) { return false; }
                if (state.src.charCodeAt(pos) === 0x0A) { return false; }
                if (state.src.charCodeAt(pos) === 0x5D /* ] */) {
                    break;
                }
            }

            if (pos === start + 2) { return false; } // no empty footnote labels
            if (pos >= max) { return false; }
            pos++;

            label = state.src.slice(start + 2, pos - 1);
            if (typeof state.env.footnotes.refs[':' + label] === 'undefined') { return false; }

            if (!silent) {
                if (!state.env.footnotes.list) { state.env.footnotes.list = []; }

                if (state.env.footnotes.refs[':' + label] < 0) {
                    footnoteId = state.env.footnotes.list.length;
                    state.env.footnotes.list[footnoteId] = { label: label, count: 0 };
                    state.env.footnotes.refs[':' + label] = footnoteId;
                } else {
                    footnoteId = state.env.footnotes.refs[':' + label];
                }

                footnoteSubId = state.env.footnotes.list[footnoteId].count;
                state.env.footnotes.list[footnoteId].count++;

                token      = state.push('footnote_ref', '', 0);
                token.meta = { id: footnoteId, subId: footnoteSubId, label: label };
            }

            state.pos = pos;
            state.posMax = max;
            return true;
        }

        // Glue footnote tokens to end of token stream
        function footnote_tail(state) {
            var i, l, j, t, lastParagraph, list, token, tokens, current, currentLabel,
                insideRef = false,
                refTokens = {};

            if (!state.env.footnotes) { return; }

            state.tokens = state.tokens.filter(function (tok) {
                if (tok.type === 'footnote_reference_open') {
                    insideRef = true;
                    current = [];
                    currentLabel = tok.meta.label;
                    return false;
                }
                if (tok.type === 'footnote_reference_close') {
                    insideRef = false;
                    // prepend ':' to avoid conflict with Object.prototype members
                    refTokens[':' + currentLabel] = current;
                    return false;
                }
                if (insideRef) { current.push(tok); }
                return !insideRef;
            });

            if (!state.env.footnotes.list) { return; }
            list = state.env.footnotes.list;

            token = new state.Token('footnote_block_open', '', 1);
            state.tokens.push(token);

            for (i = 0, l = list.length; i < l; i++) {
                token      = new state.Token('footnote_open', '', 1);
                token.meta = { id: i, label: list[i].label };
                state.tokens.push(token);

                if (list[i].tokens) {
                    tokens = [];

                    token          = new state.Token('paragraph_open', 'p', 1);
                    token.block    = true;
                    tokens.push(token);

                    token          = new state.Token('inline', '', 0);
                    token.children = list[i].tokens;
                    token.content  = '';
                    tokens.push(token);

                    token          = new state.Token('paragraph_close', 'p', -1);
                    token.block    = true;
                    tokens.push(token);

                } else if (list[i].label) {
                    tokens = refTokens[':' + list[i].label];
                }

                state.tokens = state.tokens.concat(tokens);
                if (state.tokens[state.tokens.length - 1].type === 'paragraph_close') {
                    lastParagraph = state.tokens.pop();
                } else {
                    lastParagraph = null;
                }

                t = list[i].count > 0 ? list[i].count : 1;
                for (j = 0; j < t; j++) {
                    token      = new state.Token('footnote_anchor', '', 0);
                    token.meta = { id: i, subId: j, label: list[i].label };
                    state.tokens.push(token);
                }

                if (lastParagraph) {
                    state.tokens.push(lastParagraph);
                }

                token = new state.Token('footnote_close', '', -1);
                state.tokens.push(token);
            }

            token = new state.Token('footnote_block_close', '', -1);
            state.tokens.push(token);
        }

        md.block.ruler.before('reference', 'footnote_def', footnote_def, { alt: [ 'paragraph', 'reference' ] });
        md.inline.ruler.after('image', 'footnote_inline', footnote_inline);
        md.inline.ruler.after('footnote_inline', 'footnote_ref', footnote_ref);
        md.core.ruler.after('inline', 'footnote_tail', footnote_tail);
    };

},{}]},{},[1])(1)
});/*! markdown-it-ins 2.0.0 https://github.com//markdown-it/markdown-it-ins @license MIT */(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.markdownitIns = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
    'use strict';


    module.exports = function ins_plugin(md) {
        // Insert each marker as a separate text token, and add it to delimiter list
        //
        function tokenize(state, silent) {
            var i, scanned, token, len, ch,
                start = state.pos,
                marker = state.src.charCodeAt(start);

            if (silent) { return false; }

            if (marker !== 0x2B/* + */) { return false; }

            scanned = state.scanDelims(state.pos, true);
            len = scanned.length;
            ch = String.fromCharCode(marker);

            if (len < 2) { return false; }

            if (len % 2) {
                token         = state.push('text', '', 0);
                token.content = ch;
                len--;
            }

            for (i = 0; i < len; i += 2) {
                token         = state.push('text', '', 0);
                token.content = ch + ch;

                state.delimiters.push({
                    marker: marker,
                    jump:   i,
                    token:  state.tokens.length - 1,
                    level:  state.level,
                    end:    -1,
                    open:   scanned.can_open,
                    close:  scanned.can_close
                });
            }

            state.pos += scanned.length;

            return true;
        }


        // Walk through delimiter list and replace text tokens with tags
        //
        function postProcess(state) {
            var i, j,
                startDelim,
                endDelim,
                token,
                loneMarkers = [],
                delimiters = state.delimiters,
                max = state.delimiters.length;

            for (i = 0; i < max; i++) {
                startDelim = delimiters[i];

                if (startDelim.marker !== 0x2B/* + */) {
                    continue;
                }

                if (startDelim.end === -1) {
                    continue;
                }

                endDelim = delimiters[startDelim.end];

                token         = state.tokens[startDelim.token];
                token.type    = 'ins_open';
                token.tag     = 'ins';
                token.nesting = 1;
                token.markup  = '++';
                token.content = '';

                token         = state.tokens[endDelim.token];
                token.type    = 'ins_close';
                token.tag     = 'ins';
                token.nesting = -1;
                token.markup  = '++';
                token.content = '';

                if (state.tokens[endDelim.token - 1].type === 'text' &&
                    state.tokens[endDelim.token - 1].content === '+') {

                    loneMarkers.push(endDelim.token - 1);
                }
            }

            // If a marker sequence has an odd number of characters, it's splitted
            // like this: `~~~~~` -> `~` + `~~` + `~~`, leaving one marker at the
            // start of the sequence.
            //
            // So, we have to move all those markers after subsequent s_close tags.
            //
            while (loneMarkers.length) {
                i = loneMarkers.pop();
                j = i + 1;

                while (j < state.tokens.length && state.tokens[j].type === 'ins_close') {
                    j++;
                }

                j--;

                if (i !== j) {
                    token = state.tokens[j];
                    state.tokens[j] = state.tokens[i];
                    state.tokens[i] = token;
                }
            }
        }

        md.inline.ruler.before('emphasis', 'ins', tokenize);
        md.inline.ruler2.before('emphasis', 'ins', postProcess);
    };

},{}]},{},[1])(1)
});/*! markdown-it-mark 2.0.0 https://github.com//markdown-it/markdown-it-mark @license MIT */(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.markdownitMark = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
    'use strict';


    module.exports = function ins_plugin(md) {
        // Insert each marker as a separate text token, and add it to delimiter list
        //
        function tokenize(state, silent) {
            var i, scanned, token, len, ch,
                start = state.pos,
                marker = state.src.charCodeAt(start);

            if (silent) { return false; }

            if (marker !== 0x3D/* = */) { return false; }

            scanned = state.scanDelims(state.pos, true);
            len = scanned.length;
            ch = String.fromCharCode(marker);

            if (len < 2) { return false; }

            if (len % 2) {
                token         = state.push('text', '', 0);
                token.content = ch;
                len--;
            }

            for (i = 0; i < len; i += 2) {
                token         = state.push('text', '', 0);
                token.content = ch + ch;

                state.delimiters.push({
                    marker: marker,
                    jump:   i,
                    token:  state.tokens.length - 1,
                    level:  state.level,
                    end:    -1,
                    open:   scanned.can_open,
                    close:  scanned.can_close
                });
            }

            state.pos += scanned.length;

            return true;
        }


        // Walk through delimiter list and replace text tokens with tags
        //
        function postProcess(state) {
            var i, j,
                startDelim,
                endDelim,
                token,
                loneMarkers = [],
                delimiters = state.delimiters,
                max = state.delimiters.length;

            for (i = 0; i < max; i++) {
                startDelim = delimiters[i];

                if (startDelim.marker !== 0x3D/* = */) {
                    continue;
                }

                if (startDelim.end === -1) {
                    continue;
                }

                endDelim = delimiters[startDelim.end];

                token         = state.tokens[startDelim.token];
                token.type    = 'mark_open';
                token.tag     = 'mark';
                token.nesting = 1;
                token.markup  = '==';
                token.content = '';

                token         = state.tokens[endDelim.token];
                token.type    = 'mark_close';
                token.tag     = 'mark';
                token.nesting = -1;
                token.markup  = '==';
                token.content = '';

                if (state.tokens[endDelim.token - 1].type === 'text' &&
                    state.tokens[endDelim.token - 1].content === '=') {

                    loneMarkers.push(endDelim.token - 1);
                }
            }

            // If a marker sequence has an odd number of characters, it's splitted
            // like this: `~~~~~` -> `~` + `~~` + `~~`, leaving one marker at the
            // start of the sequence.
            //
            // So, we have to move all those markers after subsequent s_close tags.
            //
            while (loneMarkers.length) {
                i = loneMarkers.pop();
                j = i + 1;

                while (j < state.tokens.length && state.tokens[j].type === 'mark_close') {
                    j++;
                }

                j--;

                if (i !== j) {
                    token = state.tokens[j];
                    state.tokens[j] = state.tokens[i];
                    state.tokens[i] = token;
                }
            }
        }

        md.inline.ruler.before('emphasis', 'mark', tokenize);
        md.inline.ruler2.before('emphasis', 'mark', postProcess);
    };

},{}]},{},[1])(1)
});/*! markdown-it-sup 1.0.0 https://github.com//markdown-it/markdown-it-sup @license MIT */(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.markdownitSup = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
// Process ^superscript^

'use strict';

// same as UNESCAPE_MD_RE plus a space
var UNESCAPE_RE = /\\([ \\!"#$%&'()*+,.\/:;<=>?@[\]^_`{|}~-])/g;

function superscript(state, silent) {
  var found,
      content,
      token,
      max = state.posMax,
      start = state.pos;

  if (state.src.charCodeAt(start) !== 0x5E/* ^ */) { return false; }
  if (silent) { return false; } // don't run any pairs in validation mode
  if (start + 2 >= max) { return false; }

  state.pos = start + 1;

  while (state.pos < max) {
    if (state.src.charCodeAt(state.pos) === 0x5E/* ^ */) {
      found = true;
      break;
    }

    state.md.inline.skipToken(state);
  }

  if (!found || start + 1 === state.pos) {
    state.pos = start;
    return false;
  }

  content = state.src.slice(start + 1, state.pos);

  // don't allow unescaped spaces/newlines inside
  if (content.match(/(^|[^\\])(\\\\)*\s/)) {
    state.pos = start;
    return false;
  }

  // found!
  state.posMax = state.pos;
  state.pos = start + 1;

  // Earlier we checked !silent, but this implementation does not need it
  token         = state.push('sup_open', 'sup', 1);
  token.markup  = '^';

  token         = state.push('text', '', 0);
  token.content = content.replace(UNESCAPE_RE, '$1');

  token         = state.push('sup_close', 'sup', -1);
  token.markup  = '^';

  state.pos = state.posMax + 1;
  state.posMax = max;
  return true;
}


module.exports = function sup_plugin(md) {
  md.inline.ruler.after('emphasis', 'sup', superscript);
};

},{}]},{},[1])(1)
});/*! markdown-it-sup 1.0.0 https://github.com//markdown-it/markdown-it-sup @license MIT */(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.markdownitSup = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){

'use strict';


    var TOC_REGEXP = /^@\[toc\](?:\((?:\s+)?([^\)]+)(?:\s+)?\)?)?(?:\s+?)?$/im;
    var TOC_DEFAULT = 'Table of Contents';
    var gstate;

    function toc(state, silent) {
        while (state.src.indexOf('\n') >= 0 && state.src.indexOf('\n') < state.src.indexOf('@[toc]')) {
            if (state.tokens.slice(-1)[0].type === 'softbreak') {
                state.src = state.src.split('\n').slice(1).join('\n');
                state.pos = 0;
            }
        }
        var token;

        // trivial rejections
        if (state.src.charCodeAt(state.pos) !== 0x40 /* @ */ ) {
            return false;
        }
        if (state.src.charCodeAt(state.pos + 1) !== 0x5B /* [ */ ) {
            return false;
        }

        var match = TOC_REGEXP.exec(state.src);
        if (!match) {
            return false;
        }
        match = match.filter(function(m) {
            return m;
        });
        if (match.length < 1) {
            return false;
        }
        if (silent) { // don't run any pairs in validation mode
            return false;
        }

        token = state.push('toc_open', 'toc', 1);
        token.markup = '@[toc]';

        token = state.push('toc_body', '', 0);
        var label = state.env.tocHeader || TOC_DEFAULT;
        if (match.length > 1) {
            label = match.pop();
        }
        token.content = label;

        token = state.push('toc_close', 'toc', -1);

        var offset = 0;
        var newline = state.src.indexOf('\n');
        if (newline !== -1) {
            offset = state.pos + newline;
        } else {
            offset = state.pos + state.posMax + 1;
        }
        state.pos = offset;

        return true;
    }
    var makeSafe = function(label) {
        return label.replace(/[^\w\s]/gi, '').split(' ').join('_');
    };

    module.exports = function table_of_content(md) {
    md.renderer.rules.heading_open = function(tokens, index) {
        var level = tokens[index].tag;
        var label = tokens[index + 1];
        if (label.type === 'inline') {
            var anchor = makeSafe(label.content) + '_' + label.map[0];
            return '<' + level + '><a id="' + anchor + '"></a>';
        } else {
            return '</h1>';
        }

    md.renderer.rules.toc_open = function(tokens, index) {
        return '';
    };

    md.renderer.rules.toc_close = function(tokens, index) {
        return '';
    };

    md.renderer.rules.toc_body = function(tokens, index) {
        // Wanted to avoid linear search through tokens here,
        // but this seems the only reliable way to identify headings
        var headings = [];
        var gtokens = gstate.tokens;
        var size = gtokens.length;
        for (var i = 0; i < size; i++) {
            if (gtokens[i].type !== 'heading_close') {
                continue;
            }
            var token = gtokens[i];
            var heading = gtokens[i - 1];
            if (heading.type === 'inline') {
                headings.push({
                    level: +token.tag.substr(1, 1),
                    anchor: makeSafe(heading.content) + '_' + heading.map[0],
                    content: heading.content
                });
            }
        }

        var indent = 0;
        var list = headings.map(function(heading) {
            var res = [];
            if (heading.level > indent) {
                var ldiff = (heading.level - indent);
                for (var i = 0; i < ldiff; i++) {
                    res.push('<ul>');
                    indent++;
                }
            } else if (heading.level < indent) {
                var ldiff = (indent - heading.level);
                for (var i = 0; i < ldiff; i++) {
                    res.push('</ul>');
                    indent--;
                }
            }
            res = res.concat(['<li><a href="#', heading.anchor, '">', heading.content, '</a></li>']);
            return res.join('');
        });

        return '<h3>' + tokens[index].content + '</h3>' + list.join('') + new Array(indent + 1).join('</ul>');
    };

    md.core.ruler.push('grab_state', function(state) {
        gstate = state;
    });
    md.inline.ruler.after('emphasis', 'toc', toc);
    };
};

},{}]},{},[1])(1)
});