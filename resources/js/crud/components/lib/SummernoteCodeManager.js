// SummernoteCodeManager.js
// Intégration Summernote (toolbar, dblclick, Enter/Tab, paste, clean avant sauvegarde, autoStart)
// S’appuie sur CodeHelpers + CodeBlockEditor

import CodeHelpers from './CodeHelpers.js';
import CodeBlockEditor from './CodeBlockEditor.js';

export default class SummernoteCodeManager {
  static defaultOptions = {
    languages: {
      php: 'PHP',
      javascript: 'JavaScript',
      html: 'HTML',
      css: 'CSS',
      json: 'JSON',
      bash: 'Bash'
    },
    placeholderCommentByLang: (lang) => ({
      php: "/* code */",
      javascript: "/* code */",
      html: "<!-- code -->",
      css: "/* code */",
      json: "{\n  \"key\": \"value\"\n}",
      bash: "# code"
    }[lang] || "/* code */"),
    doubleEnterDelay: 320,
    codeJarTab: '  ',
    height: 120,
    autoStart: false
  };

  // ---------- PUBLIC API ----------
  static initAll(selector, opts = {}) {
    const $ = window.jQuery || window.$;
    $(selector).each((_, el) => this.initTextArea($(el), opts));
  }

  static initTextArea($textarea, opts = {}) {
    const $ = window.jQuery || window.$;
    const options = { ...this.defaultOptions, ...opts };

    if (!$ || !$.summernote) throw new Error('SummernoteCodeManager: Summernote requis.');

    // 🔹 Détection disabled (ancien comportement)
    const isDisabled = !!$textarea.prop('disabled');

    // Initialiser Summernote (avec bouton dropdown "langue" si non disabled)
    if (!$textarea.data('summernote')) {
      $textarea.summernote({
        height: options.height,
        // ✅ Ancien comportement : retirer la toolbar si disabled
        toolbar: isDisabled ? false : [
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link', 'picture', 'table']],
          ['view', ['fullscreen', 'codeview']],
          ['codetools', ['codeLanguage']]
        ],
        airMode: isDisabled ? false : undefined,
        styleTags: ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3'],
        buttons: isDisabled ? {} : {
          codeLanguage: this._buildLanguageDropdownFactory(options)
        },
        callbacks: {
          // ✅ Ancien comportement : forcer contenteditable=false si disabled
          onInit: () => {
            if (isDisabled) {
              $textarea
                .siblings('.note-editor')
                .find('.note-editable')
                .attr('contenteditable', false);
            }
          },
          // ✅ Synchroniser les CodeJar actifs (déjà présent)
          onChange: function (contents, $editable) {
            const instances = (window.codejarInstances || window.CodeJarInstances || null);
            if (instances && typeof instances.forEach === 'function') {
              instances.forEach((jar, editor) => {
                if (!editor || !editor.isConnected) return;
                try {
                  let pos = jar.save();
                  jar.updateCode(editor.textContent);
                  jar.restore(pos);
                } catch (_) {}
              });
            }
          }
        }
      });
    } else if (!isDisabled) {
      // Si déjà initialisé ET non disabled → injecter bouton langue si absent
      this._injectLanguageButton($textarea, this._buildLanguageDropdownFactory(options));
    }

    const $editor = $textarea.next('.note-editor');
    const $editable = $editor.find('.note-editable');

    // État local pour cet éditeur
    const state = {
      lastEnterTime: 0,
      editor: new CodeBlockEditor({
        codeJarTab: options.codeJarTab,
        onFinish: () => {}
      })
    };
    $editable.data('sncm-state', state);

    // ⚠️ Si disabled → on n’attache pas les handlers qui modifient le contenu
    if (!isDisabled) {
      // Double-clic sur <pre><code> => activer CodeJar
      $editable.on('dblclick', 'pre code', (e) => {
        e.preventDefault();
        state.editor.startOnCodeEl(e.currentTarget);
      });

      // Enter/Tab dans <pre><code> quand CodeJar n’est PAS actif
      $editable.on('keydown', (e) => {
        if (state.editor.isActive()) return; // géré par CodeJar
        const codeEl = CodeHelpers.getCodeAncestorFromSelection();
        if (!codeEl) return;

        if (e.key === 'Enter') {
          const now = Date.now();
          const isDoubleEnter = (now - state.lastEnterTime) <= options.doubleEnterDelay;
          state.lastEnterTime = now;
          e.preventDefault();

          if (e.ctrlKey || isDoubleEnter) {
            const pre = codeEl.closest('pre');
            CodeHelpers.cleanCodeElement(codeEl);
            const p = document.createElement('p'); p.innerHTML = '<br>';
            pre.parentNode.insertBefore(p, pre.nextSibling);
            CodeHelpers.placeCaretAtStart(p);
          } else {
            CodeHelpers.insertTextAtCursor('\n');
          }
        } else if (e.key === 'Tab') {
          e.preventDefault();
          CodeHelpers.insertTextAtCursor(options.codeJarTab);
        }
      });

      // Coller en texte brut quand on est dans un bloc code (ou dans CodeJar)
      $editable.on('paste', (we) => {
        const e = we.originalEvent || we;
        const codeEl = CodeHelpers.getCodeAncestorFromSelection();
        if (codeEl || state.editor.isActive()) {
          e.preventDefault();
          let text = (e.clipboardData || window.clipboardData)?.getData('text/plain') || '';
          text = text.replace(/\r\n?/g, '\n');
          CodeHelpers.insertTextAtCursor(text);
        }
      });
    }

    // ✅ Summernote → textarea (avec nettoyage Prism déjà présent)
    $textarea.on('summernote.change', (_, contents) => {
      $textarea.val(CodeHelpers.cleanPrismFromHtml(contents));
    });

    // ✅ textarea → Summernote (ancien comportement rétabli)
    $textarea.on('input', function () {
      // NB: même en disabled, cela ne devrait pas boucler si la valeur est inchangée
      if ($textarea.data('summernote')) {
        $textarea.summernote('code', $textarea.val());
      }
    });

    // Nettoyage juste avant submit du formulaire
    const $form = $textarea.closest('form');
    if ($form.length) {
      $form.on('submit', () => {
        const cleaned = CodeHelpers.cleanPrismFromHtml($textarea.summernote('code'));
        $textarea.val(cleaned);
      });
    }

    // 👉 Option : autoStart (inchangé)
    if (options.autoStart && !isDisabled) {
      setTimeout(() => {
        const firstCode = $editable.find('pre code').get(0);
        if (firstCode && !state.editor.isActive()) {
          state.editor.startOnCodeEl(firstCode);
        }
      }, 0);
    }
  }

  // ---------- UI: bouton dropdown ----------
  static _buildLanguageDropdownFactory(options) {
    return (context) => {
      const $ = window.jQuery || window.$;
      const ui = ($.summernote && $.summernote.ui) || (context && context.ui);
      const $note = context && context.$note ? context.$note : null;
      if (!ui) throw new Error('SummernoteCodeManager: UI helper indisponible.');

      const itemsLabels = Object.values(options.languages);
      const group = ui.buttonGroup([
        ui.button({
          className: 'dropdown-toggle',
          contents: '<i class="note-icon-code"></i> <span class="caret"></span>',
          tooltip: 'Code language',
          data: { toggle: 'dropdown' }
        }),
        ui.dropdown({
          className: 'dropdown-menu sn-code-lang',
          items: itemsLabels,
          click: (e) => {
            e.preventDefault();
            const label = $(e.target).text().trim();
            const lang = SummernoteCodeManager._langFromLabel(label, options) || 'javascript';
            if ($note) SummernoteCodeManager._onPickLanguage(lang, $note, options);
          }
        })
      ]);
      return group.render();
    };
  }

  static _injectLanguageButton($textarea, buttonFactory) {
    const context = $textarea.data('summernote');
    if (!context) return;
    const $toolbar = $textarea.next('.note-editor').find('.note-toolbar');
    if ($toolbar.find('.sn-code-lang').length) return;
    const html = buttonFactory(context);
    $toolbar.append(html);
  }

  static _langFromLabel(label, options) {
    for (const [k, v] of Object.entries(options.languages)) if (v === label) return k;
    return null;
  }

  // ---------- Choix de langue ----------
  static _onPickLanguage(lang, $textarea, options) {
    const $editor = $textarea.next('.note-editor');
    const $editable = $editor.find('.note-editable');
    const state = $editable.data('sncm-state');

    // Si CodeJar est actif → changer la langue et rehighlight en live
    if (state && state.editor && state.editor.isActive()) {
      state.editor.setLanguage(lang);
      return;
    }

    // Sinon, si curseur dans <pre><code> → MAJ classe seulement
    const codeEl = CodeHelpers.getCodeAncestorFromSelection();
    if (codeEl) {
      CodeHelpers.setCodeLanguage(codeEl, lang);
      return;
    }

    // Sinon → insérer un nouveau bloc code
    const pre = document.createElement('pre');
    const code = document.createElement('code');
    code.className = `language-${lang}`;
    code.textContent = options.placeholderCommentByLang(lang);
    pre.appendChild(code);
    CodeHelpers.insertNodeAtCursor(pre);
    CodeHelpers.placeCaretAtEnd(code);
  }
}


