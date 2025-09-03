// SummernoteCodeManager.js
// Dépendances : jQuery ($), Summernote, Prism (optionnel pour la classe language-*)

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
    highlightInEditor: false      // si true, ré-exécute Prism dans l’éditeur (plus “lourd”)
  };

  static _escape(html) {
    return String(html)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }

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

  static _computeOptions($textarea, opts = {}) {
    const isDisabled = $textarea.prop('disabled');
    const merged = { ...SummernoteCodeManager.defaultOptions, ...opts };
    const lang   = merged.defaultLang;

    // Déclare le bouton custom
    const buttons = {
      codeblock: (context) => SummernoteCodeManager._buildCodeButton(context, lang)
    };

    // Base config Summernote
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

    // Optionnel : re-highlighter les <code> dans l’éditeur
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

  static initTextArea($textarea, opts = {}) {
    // 1) Init Summernote
    const config = SummernoteCodeManager._computeOptions($textarea, opts);
    $textarea.summernote(config);

    // 2) Sync textarea -> Summernote (utile si la valeur change via JS)
    $textarea.on('input', function () {
      $textarea.summernote('code', $textarea.val());
    });

    // 3) Sync Summernote -> textarea (soumission de formulaire)
    $textarea.on('summernote.change', function (e, contents) {
      $textarea.val(contents);
    });
  }

  static initAll(selector = '.richText', opts = {}) {
    $(selector).each(function () {
      SummernoteCodeManager.initTextArea($(this), opts);
    });
  }
}
