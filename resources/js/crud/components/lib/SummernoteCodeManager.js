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
    // insertText suffit pour <pre><code> (le <pre> gère les newlines)
    document.execCommand('insertText', false, text);
  }

  // ===== boutons toolbar =====
  static _buildCodeButton(context, lang) {
    const ui = $.summernote.ui;
    return ui.button({
      contents: '<i class="note-icon-code"></i>',
      tooltip: 'Insérer un bloc de code',
      click: function () {
        const rng  = context.invoke('editor.createRange');
        const sel  = rng && rng.toString ? rng.toString() : '';
        const safe = SummernoteCodeManager._escape(sel || '/* code */');
        const node = $(`<pre><code class="language-${lang}">${safe}</code></pre>`)[0];
        context.invoke('editor.insertNode', node);
        context.invoke('editor.afterCommand');
        SummernoteCodeManager._hlElementIfPossible(node.querySelector('code'));
      }
    });
  }

  static _buildLangDropdown(context, languages) {
    const ui = $.summernote.ui;

    // IMPORTANT : ne pas appeler .render() sur les enfants
    const group = ui.buttonGroup([
      ui.button({
        className: 'dropdown-toggle',
        contents: '<i class="note-icon-magic"></i> <span class="note-icon-caret"></span>',
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

          // Si caret dans un bloc code => changer la classe language-*
          const codeEl = SummernoteCodeManager._getSelectionCodeEl();
          if (codeEl) {
            codeEl.className = `language-${lang}`;
            SummernoteCodeManager._hlElementIfPossible(codeEl);
          } else {
            // sinon insérer un bloc vide
            const node = $(`<pre><code class="language-${lang}">/* code */</code></pre>`)[0];
            context.invoke('editor.insertNode', node);
            context.invoke('editor.afterCommand');
            SummernoteCodeManager._hlElementIfPossible(node.querySelector('code'));
          }
        }
      })
    ]);

    return group;
  }

  // ===== options Summernote =====
  static _ensureToolbarHasInsert(mergedToolbar) {
    // S’assure que 'insert' existe et contient 'codeblock' + 'codelang'
    const tb = JSON.parse(JSON.stringify(mergedToolbar || []));
    let insertIdx = tb.findIndex(row => row && row[0] === 'insert');

    if (insertIdx === -1) {
      tb.push(['insert', ['codeblock','codelang']]);
    } else {
      const items = new Set(tb[insertIdx][1]);
      items.add('codeblock'); items.add('codelang');
      tb[insertIdx][1] = Array.from(items);
    }
    return tb;
  }

  static _computeOptions($textarea, opts = {}) {
    const isDisabled = $textarea.prop('disabled');
    const merged = { ...SummernoteCodeManager.defaultOptions, ...opts };
    const lang   = merged.defaultLang;

    const buttons = {
      codeblock: (context) => SummernoteCodeManager._buildCodeButton(context, lang),
      codelang:  (context) => SummernoteCodeManager._buildLangDropdown(context, merged.languages)
    };

    // S’assurer que la toolbar possède 'insert' avec nos boutons
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

    // Optionnel : re-highlighter global (lourd)
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

    // conteneur éditeur (remplace <pre>)
    const pre      = codeEl.parentElement;
    const jarDiv   = document.createElement('div');
    jarDiv.className = 'codejar-inline';
    jarDiv.textContent = codeEl.textContent || '/* code */';
    jarDiv.dataset.lang = lang; // pour fermeture globale
    pre.replaceWith(jarDiv);

    const highlight = (ed) => {
      if (typeof Prism === 'undefined') return;
      const grammar = Prism.languages[lang] || Prism.languages.markup;
      ed.innerHTML  = Prism.highlight(ed.textContent, grammar, lang);
    };

    const jar = CodeJar(jarDiv, highlight);
    jarDiv.focus();

    // fermeture & commit (pour cet éditeur)
    function closeJar() {
      if (!jarDiv.isConnected) return; // déjà fermé
      const newPre  = document.createElement('pre');
      const newCode = document.createElement('code');
      newCode.className = langCls;
      newCode.textContent = jarDiv.textContent; // texte brut (sécurité XSS)
      newPre.appendChild(newCode);
      jarDiv.replaceWith(newPre);
      SummernoteCodeManager._hlElementIfPossible(newCode);
    }

    // touches de sortie
    jarDiv.addEventListener('keydown', (e) => {
      const key = e.key;
      const isCtrlEnter = (e.ctrlKey && key === 'Enter');
      if (exitKeys.includes('Escape') && key === 'Escape') {
        e.preventDefault(); closeJar();
      } else if (exitKeys.includes('Ctrl+Enter') && isCtrlEnter) {
        e.preventDefault(); closeJar();
      }
    });

    // fermeture automatique si blur
    jarDiv.addEventListener('blur', () => closeJar(), { once: true });

    // expose une méthode utilitaire sur l’élément pour fermeture globale
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

  static _enableEnterAsNewlineInPre(editable) {
    // Dans l’éditeur Summernote classique (hors CodeJar), Enter doit créer une newline dans <pre><code>
    editable.addEventListener('keydown', (e) => {
      if (e.key !== 'Enter') return;

      // si on tape Enter dans un bloc code (et pas dans notre .codejar-inline)
      const target = e.target;
      const codeEl = target && target.closest ? target.closest('pre code') : null;
      const insideJar = target && target.closest ? target.closest('.codejar-inline') : null;

      if (codeEl && !insideJar) {
        e.preventDefault();
        SummernoteCodeManager._insertText('\n');
      }
    });
  }

  static _enableInlineCodeEditing($textarea, opts = {}) {
    const isDisabled = $textarea.prop('disabled');
    if (isDisabled) return;

    const editorRoot = $textarea.siblings('.note-editor')[0];
    const editable   = editorRoot?.querySelector('.note-editable');
    if (!editable) return;

    // Enter => newline dans <pre><code> (mode normal)
    SummernoteCodeManager._enableEnterAsNewlineInPre(editable);

    // Double-clic sur un bloc <pre><code> => bascule en éditeur CodeJar
    editable.addEventListener('dblclick', (e) => {
      const target = e.target;
      const codeEl = target && target.closest ? target.closest('pre code') : null;
      if (!codeEl) return;
      SummernoteCodeManager._codeBlockToJar(codeEl, opts);
    });

    // ——— Fermeture automatique si l’utilisateur “oublie” ———

    // 1) Perte de focus de l’éditeur Summernote
    $textarea.on('summernote.blur', () => {
      SummernoteCodeManager._closeAllInlineEditors(editorRoot);
    });

    // 2) Bascule en codeview
    $textarea.on('summernote.codeview.toggled', () => {
      SummernoteCodeManager._closeAllInlineEditors(editorRoot);
    });

    // 3) Clic en dehors de la zone éditeur
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

    // textarea -> Summernote (si valeur changée via JS)
    $textarea.on('input', function () {
      $textarea.summernote('code', $textarea.val());
    });

    // Summernote -> textarea (pour submit etc.)
    $textarea.on('summernote.change', function (e, contents) {
      $textarea.val(contents);
    });

    // Activer Option B (inline CodeJar)
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
