// SummernoteCodeManager.js
// Dépendances : jQuery ($), Summernote, Prism, CodeJar
// npm: prismjs, codejar
import { CodeJar } from 'codejar';

export class SummernoteCodeManager {
  static defaultOptions = {
    height: 80,
    defaultLang: 'php',          // language-php | language-js | language-html...
    toolbar: [
      ['style',   ['style']],                       // permet 'pre' via styleTags
      ['para',    ['ul', 'ol', 'paragraph']],
      ['insert',  ['link', 'picture', 'table', 'codeblock']],
      ['view',    ['fullscreen', 'codeview']]
    ],
    styleTags: ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3'],

    // —— Option B — inline editor CodeJar ——
    inlineCodeEdit: true,               // double-clic pour éditer un bloc de code
    inlineExitKeys: ['Escape', 'Ctrl+Enter'], // touches pour fermer l’éditeur
    highlightInEditor: false            // re-highlighter global (Prism) : off par défaut
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

  // ===== bouton d’insertion d’un bloc code =====
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
      }
    }).render();
  }

  // ===== options Summernote =====
  static _computeOptions($textarea, opts = {}) {
    const isDisabled = $textarea.prop('disabled');
    const merged = { ...SummernoteCodeManager.defaultOptions, ...opts };
    const lang   = merged.defaultLang;

    const buttons = {
      codeblock: (context) => SummernoteCodeManager._buildCodeButton(context, lang)
    };

    const baseConfig = {
      height: merged.height,
      styleTags: merged.styleTags,
      buttons,
      toolbar: isDisabled ? false : merged.toolbar,
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

    if (merged.highlightInEditor && typeof Prism !== 'undefined') {
      baseConfig.callbacks = baseConfig.callbacks || {};
      baseConfig.callbacks.onChange = function () {
        const root = $textarea.siblings('.note-editor')[0];
        if (root) Prism.highlightAllUnder(root);
      };
      baseConfig.callbacks.onInit = (function (prev) {
        return function () {
          if (typeof prev === 'function') prev();
          const root = $textarea.siblings('.note-editor')[0];
          if (root) Prism.highlightAllUnder(root);
        };
      })(baseConfig.callbacks.onInit);
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

    pre.replaceWith(jarDiv);

    const highlight = (ed) => {
      if (typeof Prism === 'undefined') return;
      const grammar = Prism.languages[lang] || Prism.languages.markup;
      ed.innerHTML  = Prism.highlight(ed.textContent, grammar, lang);
    };

    const jar = CodeJar(jarDiv, highlight);
    jarDiv.focus();

    // fermeture & commit
    function closeJar() {
      const newPre  = document.createElement('pre');
      const newCode = document.createElement('code');
      newCode.className = langCls;
      // On écrit en texte brut (pas d’HTML) — sécurité XSS
      newCode.textContent = jarDiv.textContent;
      newPre.appendChild(newCode);
      jarDiv.replaceWith(newPre);
      SummernoteCodeManager._hlElementIfPossible(newCode);
    }

    // gestion des touches de sortie
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
  }

  static _enableInlineCodeEditing($textarea, opts = {}) {
    const isDisabled = $textarea.prop('disabled');
    if (isDisabled) return;

    const editorRoot = $textarea.siblings('.note-editor')[0];
    const editable   = editorRoot?.querySelector('.note-editable');
    if (!editable) return;

    // Double-clic sur un bloc <pre><code> => bascule en éditeur CodeJar
    editable.addEventListener('dblclick', (e) => {
      const target = e.target;
      const codeEl = target && target.closest ? target.closest('pre code') : null;
      if (!codeEl) return;
      SummernoteCodeManager._codeBlockToJar(codeEl, opts);
    });
  }

  // ===== API publique =====
  static initTextArea($textarea, opts = {}) {
    const config = SummernoteCodeManager._computeOptions($textarea, opts);
    $textarea.summernote(config);

    // textarea -> Summernote
    $textarea.on('input', function () {
      $textarea.summernote('code', $textarea.val());
    });

    // Summernote -> textarea
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
