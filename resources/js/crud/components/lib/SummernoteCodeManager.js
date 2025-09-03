// SummernoteCodeManager.js
// Dépendances : jQuery ($), Summernote, Prism (global), CodeJar
// npm: prismjs, codejar
import { CodeJar } from 'codejar';

export class SummernoteCodeManager {
    
  static defaultOptions = {
    height: 80,
    defaultLang: 'php',          // language-php | language-js | language-html...
    toolbar: [
      ['style',   ['style']],                         // permet 'pre' via styleTags
      ['para',    ['ul', 'ol', 'paragraph']],
      ['insert',  ['link', 'picture', 'table', 'codeblock', 'codelang']], // + codelang
      ['view',    ['fullscreen', 'codeview']]
    ],
    styleTags: ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3'],

    // —— Option B — inline editor CodeJar ——
    inlineCodeEdit: true,                   // double-clic pour éditer un bloc de code
    inlineExitKeys: ['Escape', 'Ctrl+Enter'], // touches pour fermer l’éditeur
    highlightInEditor: false,               // re-highlighter global (Prism) : off par défaut

    // —— Langages disponibles pour le sélecteur ——
    languages: ['php','javascript','html','css','json','bash']
  };

  // ===== utilitaires =====
  static _escape(html) {
    return String(html)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }
  static _langFromCodeEl(codeEl) {
    const cls = [...codeEl.classList].find(c => c.startsWith('language-'));
    return cls ? cls.replace('language-', '') : 'markup';
  }
  static _hlElementIfPossible(codeEl){
    if (typeof Prism !== 'undefined' && codeEl) Prism.highlightElement(codeEl);
  }
  static _getSelectionCodeEl() {
    const sel = window.getSelection();
    if (!sel || !sel.anchorNode) return null;
    const node = sel.anchorNode.nodeType === 1 ? sel.anchorNode : sel.anchorNode.parentElement;
    return node?.closest ? node.closest('pre code') : null;
  }
  static _insertText(text) {
    document.execCommand('insertText', false, text);
  }


  static _buildLangDropdown(context, languages) {
    const ui = $.summernote.ui;

    // enfants NON rendus, seul le group est rendu
    const group = ui.buttonGroup([
      ui.button({
        className: 'dropdown-toggle',
        contents: '<i class="note-icon-code"></i>',
        tooltip: 'Langage du code',
        data: { toggle: 'dropdown' }
      }),
      ui.dropdown({
        className: 'dropdown-language',
        items: languages.map(l => l.toUpperCase()),
        click: function (e) {
          e.preventDefault();
          const label = $(e.target).text().trim().toLowerCase();
          const lang  = languages.includes(label) ? label : languages[0];

          const codeEl = SummernoteCodeManager._getSelectionCodeEl();
          if (codeEl) {
            codeEl.className = `language-${lang}`;
            SummernoteCodeManager._hlElementIfPossible(codeEl);
          } else {
            const node = $(`<pre><code class="language-${lang}">/* code */</code></pre>`)[0];
            context.invoke('editor.insertNode', node);
            context.invoke('editor.afterCommand');
            SummernoteCodeManager._hlElementIfPossible(node.querySelector('code'));
          }
        }
      })
    ]);

    return group.render(); // <<< IMPORTANT
  }


  // + Ajouter cette méthode
static _insertParagraphAfter(pre){
  const p = document.createElement('p');
  p.innerHTML = '<br>'; // paragraphe vide visible pour Summernote
  pre.insertAdjacentElement('afterend', p);

  // placer le caret dans le paragraphe
  const sel = window.getSelection();
  const range = document.createRange();
  range.setStart(p, 0);
  range.collapse(true);
  sel.removeAllRanges();
  sel.addRange(range);
}

// + Ajouter cette méthode
static _buildExitButton(context){
  const ui = $.summernote.ui;
  return ui.button({
    contents: '<span style="font-weight:600">¶+</span>',
    tooltip: 'Paragraphe après le code',
    click: function(){
      const sel = window.getSelection();
      if (!sel || sel.rangeCount === 0) return;
      const node = sel.anchorNode.nodeType === 1 ? sel.anchorNode : sel.anchorNode.parentElement;
      const codeEl = node?.closest('pre code');
      if (codeEl){
        SummernoteCodeManager._insertParagraphAfter(codeEl.closest('pre'));
      } else {
        // si pas dans un code, insérer un paragraphe standard
        document.execCommand('insertParagraph', false);
      }
    }
  }).render();
}


  // ===== options Summernote =====
  static _ensureToolbarHasInsert(mergedToolbar) {
    const tb = JSON.parse(JSON.stringify(mergedToolbar || []));
    let insertIdx = tb.findIndex(row => row && row[0] === 'insert');

    if (insertIdx === -1) {
      tb.push(['insert', ['codeblock','codelang']]);
    } else {
      const items = new Set(tb[insertIdx][1]);
      items.add('codeblock'); 
      items.add('codelang');
      items.add('exitcode');
      tb[insertIdx][1] = Array.from(items);
    }
    return tb;
  }

  static _computeOptions($textarea, opts = {}) {
    const isDisabled = $textarea.prop('disabled');
    const merged = { ...SummernoteCodeManager.defaultOptions, ...opts };
    const lang   = merged.defaultLang;

    const buttons = {
    codelang:  (context) => SummernoteCodeManager._buildLangDropdown(context, merged.languages),
    exitcode:  (context) => SummernoteCodeManager._buildExitButton(context)
    };

    const toolbarFixed = SummernoteCodeManager._ensureToolbarHasInsert(merged.toolbar);

    const baseConfig = {
      height: merged.height,
      styleTags: merged.styleTags,
      buttons,
      toolbar: isDisabled ? false : toolbarFixed,
      airMode: isDisabled ? false : undefined,
      callbacks: isDisabled ? {
        onInit: function () {
          $textarea
            .siblings('.note-editor')
            .find('.note-editable')
            .attr('contenteditable', false);
        }
      } : {}
    };

    // Coller en texte brut si caret dans un bloc code
    baseConfig.callbacks = baseConfig.callbacks || {};
    const prevPaste = baseConfig.callbacks.onPaste;
    baseConfig.callbacks.onPaste = function (e) {
      if (typeof prevPaste === 'function') prevPaste(e);
      const codeEl = SummernoteCodeManager._getSelectionCodeEl();
      if (codeEl) {
        e.preventDefault();
        const text = (e.originalEvent || e).clipboardData.getData('text');
        SummernoteCodeManager._insertText(text);
      }
    };

    // (optionnel) re-highlight global
    if (merged.highlightInEditor && typeof Prism !== 'undefined') {
      const prevInit = baseConfig.callbacks.onInit;
      baseConfig.callbacks.onChange = function () {
        const root = $textarea.siblings('.note-editor')[0];
        if (root) Prism.highlightAllUnder(root);
      };
      baseConfig.callbacks.onInit = function () {
        if (typeof prevInit === 'function') prevInit();
        const root = $textarea.siblings('.note-editor')[0];
        if (root) Prism.highlightAllUnder(root);
      };
    }

    return baseConfig;
  }

  // ===== Option B — Édition “in-place” avec CodeJar =====
  static _codeBlockToJar(codeEl, opts = {}) {
    const lang     = SummernoteCodeManager._langFromCodeEl(codeEl);
    const langCls  = `language-${lang}`;
    const exitKeys = (opts.inlineExitKeys && Array.isArray(opts.inlineExitKeys))
      ? opts.inlineExitKeys
      : SummernoteCodeManager.defaultOptions.inlineExitKeys;

    const pre      = codeEl.parentElement;
    const jarDiv   = document.createElement('div');
    jarDiv.className = 'codejar-inline';
    jarDiv.textContent = codeEl.textContent || '/* code */';
    jarDiv.dataset.lang = lang;
    pre.replaceWith(jarDiv);

    const highlight = (ed) => {
      if (typeof Prism === 'undefined') return;
      const grammar = Prism.languages[lang] || Prism.languages.markup;
      ed.innerHTML  = Prism.highlight(ed.textContent, grammar, lang);
    };

    const jar = CodeJar(jarDiv, highlight);
    jarDiv.focus();

    function closeJar() {
      if (!jarDiv.isConnected) return;
      const newPre  = document.createElement('pre');
      const newCode = document.createElement('code');
      newCode.className = langCls;
      newCode.textContent = jarDiv.textContent;
      newPre.appendChild(newCode);
      jarDiv.replaceWith(newPre);
      SummernoteCodeManager._hlElementIfPossible(newCode);
    }

    jarDiv.addEventListener('keydown', (e) => {
      const key = e.key;
      const isCtrlEnter = (e.ctrlKey && key === 'Enter');
      if (exitKeys.includes('Escape') && key === 'Escape') {
        e.preventDefault(); closeJar();
      } else if (exitKeys.includes('Ctrl+Enter') && isCtrlEnter) {
        e.preventDefault(); closeJar();
      }
    });

    jarDiv.addEventListener('blur', () => closeJar(), { once: true });
    jarDiv.__closeJar = closeJar;
  }

  static _closeAllInlineEditors(editorRoot) {
    if (!editorRoot) return;
    const openEditors = editorRoot.querySelectorAll('.codejar-inline');
    openEditors.forEach(jarDiv => {
      const lang  = jarDiv.dataset.lang || 'markup';
      const pre   = document.createElement('pre');
      const code  = document.createElement('code');
      code.className = `language-${lang}`;
      code.textContent = jarDiv.textContent;
      pre.appendChild(code);
      jarDiv.replaceWith(pre);
      SummernoteCodeManager._hlElementIfPossible(code);
    });
  }

// REMPLACER entièrement la méthode
static _enableEnterAsNewlineInPre(editable) {
  // petit état pour le "double Enter rapide"
  if (!editable.__enterState) editable.__enterState = { lastAt: 0, lastCode: null };
  const state = editable.__enterState;

  editable.addEventListener('keydown', (e) => {
    if (e.key !== 'Enter') return;

    const sel = window.getSelection();
    if (!sel || sel.rangeCount === 0) return;

    const node = sel.anchorNode.nodeType === 1 ? sel.anchorNode : sel.anchorNode.parentElement;
    const codeEl = node?.closest('pre code');
    const insideJar = node?.closest('.codejar-inline');

    // Agir uniquement si on est dans <pre><code> et pas dans CodeJar
    if (!codeEl || insideJar) return;

    e.preventDefault();
    e.stopPropagation();

    const pre = codeEl.closest('pre');

    // --- Cas A : Ctrl+Enter => sortir du bloc (paragraphe après)
    if (e.ctrlKey) {
      SummernoteCodeManager._insertParagraphAfter(pre);
      state.lastAt = 0; state.lastCode = null;
      return;
    }

    // --- Cas B : double Enter rapide dans le même bloc => sortir
    const now = Date.now();
    if (state.lastCode === codeEl && (now - state.lastAt) < 450) {
      SummernoteCodeManager._insertParagraphAfter(pre);
      state.lastAt = 0; state.lastCode = null;
      return;
    }

    // --- Cas C : Enter normal => ajouter une nouvelle ligne dans le code
    const range = sel.getRangeAt(0);
    range.deleteContents();
    const newline = document.createTextNode('\n');
    range.insertNode(newline);
    range.setStartAfter(newline);
    range.collapse(true);
    sel.removeAllRanges();
    sel.addRange(range);

    // mémoriser pour détecter le double Enter
    state.lastAt = now;
    state.lastCode = codeEl;
  }, true); // capture pour passer avant Summernote
}


  static _enableInlineCodeEditing($textarea, opts = {}) {
    const isDisabled = $textarea.prop('disabled');
    if (isDisabled) return;

    const editorRoot = $textarea.siblings('.note-editor')[0];
    const editable   = editorRoot?.querySelector('.note-editable');
    if (!editable) return;

    SummernoteCodeManager._enableEnterAsNewlineInPre(editable);

    editable.addEventListener('dblclick', (e) => {
      const target = e.target;
      const codeEl = target && target.closest ? target.closest('pre code') : null;
      if (!codeEl) return;
      SummernoteCodeManager._codeBlockToJar(codeEl, opts);
    });

    $textarea.on('summernote.blur', () => {
      SummernoteCodeManager._closeAllInlineEditors(editorRoot);
    });

    $textarea.on('summernote.codeview.toggled', () => {
      SummernoteCodeManager._closeAllInlineEditors(editorRoot);
    });

    document.addEventListener('mousedown', (e) => {
      if (!editorRoot.contains(e.target)) {
        SummernoteCodeManager._closeAllInlineEditors(editorRoot);
      }
    });
  }

  // ===== API publique =====
  static initTextArea($textarea, opts = {}) {

    const config = SummernoteCodeManager._computeOptions($textarea, opts);
    $textarea.summernote(config);

    $textarea.on('input', function () {
      $textarea.summernote('code', $textarea.val());
    });

    $textarea.on('summernote.change', function (e, contents) {
      $textarea.val(contents);
    });

    const merged = { ...SummernoteCodeManager.defaultOptions, ...opts };
    if (merged.inlineCodeEdit) {
      SummernoteCodeManager._enableInlineCodeEditing($textarea, merged);
    }
  }

  static initAll(selector = '.richText', opts = {}) {
    $(selector).each(function () {
      SummernoteCodeManager.initTextArea($(this), opts);
    });
  }
}
